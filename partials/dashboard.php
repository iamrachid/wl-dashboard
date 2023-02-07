<?php

wp_head();
$response = wp_remote_get( get_rest_url(null, 'dashboard/dates') );
$dates     = json_decode(wp_remote_retrieve_body( $response ));
?>

<body>
<div class="container p-4">
    <div class="d-flex">
        <!-- <div class="flex-shrink-0 me-2 rounded">
            1
        </div> -->
        <div class="d-flex">
            <button class="btn py-0 d-flex justify-content-center align-items-center text-secondary disabled" disabled id="date-prev">
                <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" fill="currentColor"
                     class="bi bi-arrow-left-square" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                          d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
                </svg>
            </button>
            <select class="custom-select" id="date">
                <?php foreach ($dates as $i=>$date):?>
                    <option value="<?= $date ?>" <?= $i == 0 ? "selected" : ""?> > <?= $date ?> </option>
                <?php endforeach ?>
            </select>
            <button class="btn py-0 d-flex justify-content-center align-items-center text-secondary" id="date-next">
                <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" fill="currentColor"
                     class="bi bi-arrow-right-square" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                          d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm4.5 5.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z" />
                </svg>
            </button>
        </div>
        <div class="d-flex mx-2" id="venue-raceno">
            <!-- Venue & raceno -->
        </div>
    </div>
    <div class="d-flex mt-2 align-items-center justify-content-between">
        <div class="mx-3">
            <h3 class="mb-0" id="title-1"></h3>
                <h4  id="title-2"></h4>
        </div>
        <div class="px-3 py-0 mx-3 bg-info d-flex justify-content-center align-items-center">
            <h4 class="mx-2 my-1" id="start-time"></h4>
        </div>
        <div class="d-flex">
            <div class="step step-1"></div>
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 0" aria-valuenow="0" aria-valuemin="0"
                     aria-valuemax="100"></div>
            </div>
            <div class="step step-2"></div>
            <div class="progress" >
                <div class="progress-bar" role="progressbar" style="width: 0" aria-valuenow="25"
                     aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="step step-3"></div>
            <div class="progress" >
                <div class="progress-bar" role="progressbar" style="width: 0" aria-valuenow="50"
                     aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="step step-4"></div>
            <div class="progress" >
                <div class="progress-bar" role="progressbar" style="width: 0" aria-valuenow="75" aria-valuemin="0"
                     aria-valuemax="100"></div>
            </div>
            <div class="step step-5"></div>
            <div class="progress" >
                <div class="progress-bar" role="progressbar" style="width: 0" aria-valuenow="75" aria-valuemin="0"
                     aria-valuemax="100"></div>
            </div>
            <div class="step step-6"></div>
        </div>
    </div>
</div>
<div class="">
    <table id="table" class="display table table-sm" style="width:100%">
    </table>
</div>
<div class="py-3"></div>
<div id="slider" class="carousel" data-ride="carousel" data-interval="false">
    <div class="d-flex justify-content-center">
        <a href="#slider" class="mx-2 d-flex" role="button" data-slide="prev">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                 class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                      d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
            </svg>
        </a>
        <input type="range" class="form-range w-75" id="range" value="0">
        <a href="#slider" class="mx-2 d-flex" role="button" data-slide="next">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                 class="bi bi-arrow-right-circle" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                      d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z" />
            </svg>
        </a>
    </div>
    <div class="carousel-inner" id="charts">

    </div>
</div>
</body>

</html>
