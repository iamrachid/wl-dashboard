(function ($) {
	$(document).ready(function () {

      let dateschedule = [];
      const loadVenueRaceno = function (date) {
        $.get(constants['rest-url'] + 'dashboard/schedule/' + date).then((data) => {
          dateschedule = data;
          const venues = {};
          $.each(data, function (i, r) {
            venues[r.venue] = venues[r.venue] || [];
            venues[r.venue].push(r.raceno);
          });
          $('#venue-raceno').text('');
          $.each(Object.keys(venues), function (i, v) {
            let venue = $('<div></div>').addClass(['d-flex', 'align-items-center', 'mx-2']);
            let venueTitle = $('<span></span>').addClass('me-1').text(v);
            let container = $('<div></div>').addClass(["btn-group", "mx-2"]).attr('role', "group");
            venue.append(venueTitle);
            $.each(venues[v], function (j, r) {
              let input = $('<input />').attr('type', "radio")
                  .addClass("btn-check")
                  .attr('name', "venue-raceno")
                  .attr('id', v + '-' + r);
              let label = $('<label></label>').addClass(["btn", "btn-outline-primary", "m-0"])
                  .attr('for', v + '-' + r)
                  .text(r);
              container.append(input).append(label);
            })
            $('#venue-raceno').append(venue.append(container));
            const radios = $("input[name='venue-raceno']");
            if (!$.makeArray(radios).some((r) => $(r).is(':checked'))) {
              $(radios[0]).prop('checked', true);
              loadTableCharts.apply($(radios[0]));
            }
            $.each(radios, function (i, r) {
              $(r).on('click', loadTableCharts);
            });
          });
        })
      }

      loadVenueRaceno($('#date').val())
      $('#date').on('change', function (e) {
        if (this.selectedIndex == 0)
          $('#date-prev').addClass('disabled').prop('disabled', true);
        else
          $('#date-prev').removeClass('disabled').prop('disabled', false);

        if (this.selectedIndex == this.length-1)
          $('#date-next').addClass('disabled').prop('disabled', true);
        else
          $('#date-next').removeClass('disabled').prop('disabled', false);
        loadVenueRaceno(this.value);
      });
      const loadTableCharts = function () {

        const [venue, raceno] = $(this).attr('id').split('-');
        const date = $('#date').val();
        dateschedule.forEach((r) => {
          if (r.rdate != date || r.venue != venue || r.raceno != raceno)
            return;
          $('#start-time').text(r.starttime);
          $('#title-1').text(r.title1);
          $('#title-2').text(r.title2);
          const remaining = (new Date(date + ' ' + r.starttime) - Date.now()) / (1000 * 60);
          $.each($('.progress-bar'), function (i, b) {
            if (remaining < 20 - 5 * i)
              $(b).css({width: '100%'});
          })
        });

        $.get(constants['rest-url'] + 'dashboard/table/' + date + '/' + venue + '/' + raceno).then((data) => {

          if (!!$("#table").text().trim()) {
            $('#table').DataTable().clear().destroy();
            $("#table").text('');
          }

          // Build table header
          let head = $('<thead></thead>')
          let tr = $('<tr></tr>');
          head.append(tr);
          $.each(data.cols, function (i, col) {
            let th = $('<th></th>').text(col).css({'vertical-align': 'middle'});
            tr.append(th);
          })
          $('#table').append(head);
          let dateCol = 0;
          // Cells preprocessing
          const columnDefs = [

            // center all cells
            {className: "dt-body-center", targets: "_all"},
          ];

          // Add columnA checkbox
          if (data.cols.includes('columnA')) {
            dateCol++;
            const def = {
              targets: data.cols.indexOf('columnA'),
              createdCell: function (cell, cellData, rowData, rowIndex, colIndex) {
                $(cell).text('');
                let input = $('<input>').attr('type', "checkbox")
                    .attr('name', 'columnA.' + rowIndex)
                    .prop("checked", cellData == 1)
                    .addClass('form-check-input')
                    .appendTo(cell);
              }
            }
            columnDefs.push(def)
          }

          // Add columnB checkbox
          if (data.cols.includes('columnB')) {
            dateCol++;
            const def = {
              targets: data.cols.indexOf('columnB'),
              createdCell: function (cell, cellData, rowData, rowIndex, colIndex) {
                $(cell).text('');
                let input = $('<input>').attr('type', "checkbox")
                    .attr('name', 'columnA.' + rowIndex)
                    .prop("checked", cellData == 1)
                    .addClass('form-check-input')
                    .appendTo(cell);
              }
            }
            columnDefs.push(def)
          }

          // format colored cells
          columnDefs.push({
            targets: '_all',
            createdCell: function (cell, cellData, rowData, rowIndex, colIndex) {
              if (("" + cellData).includes('value')) {
                const style = {};
                let value;
                cellData.split(';').forEach(element => {
                  [a, b] = element.split('=');
                  $(cell).text('');
                  switch (a) {
                    case 'textcolor':
                      style.color = b;
                      break;
                    case 'bgcolor':
                      style['background-color'] = b;
                      break;
                    case 'value':
                      value = b;
                      break;
                    case 'icon':
                      $('<i class="bi bi-arrow-' + b.split('.')[0] + '-circle-fill ml-2"></i>').appendTo($(cell));
                  }
                });
                $(cell).css(style);
                $(cell).prepend($('<span></span>').text(value));
              }
            }
          })

          // hide date, venue and raceno
          columnDefs.push({
            targets: [dateCol, dateCol + 1, dateCol + 2],
            visible: false,
          })

          $('#table').DataTable({
            data: Object.values(data.rows),
            columnDefs,
            scrollY: "100%",
            scrollX: false,
            paging: false,
            fixedColumns: {
              left: dateCol == 0 ? 2 : 4,
            }
          });
        });

        // Building the chart
        $.get(constants['rest-url'] + 'dashboard/charts/' + date + '/' + venue + '/' + raceno).then((rows) => {
          $('#range').attr('min', "0")
              .attr('max', rows.length);
          $.each(rows, (i, r) => {
            const data = JSON.parse(r.docdata);
            $('<div></div>')
                .attr('id', 'chart-' + i)
                .addClass('carousel-item')
                .addClass(i == 0 ? 'active' : '')
                .appendTo($('#charts'));
            let stack1 = [];
            let stack2 = [];
            let stack3 = [];
            $.each(data[0].rows, (i, row) => {
              stack1.push(row[6])
              stack2.push(row[7])
              stack3.push(row[8])
            })
            const config = {
              accessibility: {
                enabled: false
              },
              title: {
                text: data[0].title,
              },
              chart: {
                type: 'column',
              },
              tooltip: {
                formatter: function () {
                  return 'The value of <b>stack ' + (3 - this.colorIndex) +
                      '</b> is <b>' + this.y + '</b>';
                }
              },
              legend: {
                enabled: false
              },
              xAxis: {
                categories: data[0].rows,
                labels: {
                  useHTML: true,
                  formatter: function () {
                    return '<div class="d-flex flex-column align-items-center">\
                    <div class="">' + this.value[10] + '</div>\
                    <div class="rounded-circle bg-dark text-white p-2">' + this.value[3] + '</div>\
                    <div class="">' + this.value[4] + '</div>\
                  </div>';
                  }
                },
              },

              plotOptions: {
                series: {
                  stacking: 'normal',
                  states: {
                    hover: {
                      enabled: false
                    }
                  }
                }
              },

              series: [
                {
                  states: {
                    hover: {
                      enabled: false
                    }
                  },
                  data: stack1,
                  dataLabels: {
                    enabled: true,
                    useHTML: true,
                    inside: false,
                    formatter: function () {
                      if (this.key[13] != 0)
                        return '<div class="rounded-circle bg-danger text-white p-2 mt-n3">' + this.key[13] + '</div>';

                      if (this.key[14] != 0)
                        return '<div class="rounded-circle border border-danger text-danger p-2 mt-n3">' + this.key[14] + '</div>';
                    }
                  }
                },
                {
                  data: stack2,
                  dataLabels: {
                    enabled: false,
                  }
                },
                {
                  data: stack3,
                  dataLabels: {
                    enabled: true,
                    formatter: function () {
                      return this.key[12]
                    }
                  }
                }]
            };
            const chart = Highcharts.chart('chart-' + i, config);
          });
          $('#slider').on('slide.bs.carousel', function (e) {
            $('#range').val(e.to)
          });
          $('#range').on('change', function () {
            $('#slider').carousel(1 * this.value);
          })
        });
      }

      $('button[id^="date"]').on('click', function (){
        const option = $('#date > option:selected')
            .prop("selected", false);
        option.next()
            .prop("selected", this.id == 'date-next');
        option.prev()
            .prop("selected", this.id == 'date-prev');
        $('#date').trigger('change');
      });

    });
})(jQuery)
