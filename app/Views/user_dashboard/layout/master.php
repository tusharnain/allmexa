<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>" id="csrf">
    <meta name="user_id" content="<?= user('user_id') ?>" id="meta_user_id">
    <title> <?= isset($page_title) ? $page_title : '' ?></title>

    <!-- Favicon -->
    <link rel="icon" href="<?= base_url('images/favicon.png') ?>?v=0.2" type="image/x-icon">
    <link rel="stylesheet" href="<?= base_url('coinex/css/libs.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= user_asset('css/font-awesome/css/font-awesome.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('twebsol/plugins/tippy/tippy-custom-theme.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('twebsol/plugins/tippy/tippy.animation.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('twebsol/plugins/sweetalert2/sweetalert2.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('coinex/css/coinex.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('twebsol/styles/main.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= user_asset('main.css') ?>">
    <script src="<?= base_url('twebsol/scripts/head.js') ?>"></script>

    <style>
        /* .dataTables_wrapper .dataTables_paginate .paginate_button {

            color: #FFF !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {

            color: #ff971d !important;
        } */

        tr,
        td,
        select {
            background-color: #202022 !important;
        }

        select {
            color: white;
        }

        .dataTables_length {
            margin-bottom: 30px;
        }

        .dataTables_filter input {
            background-color: #202022;
            border: 1px solid white;
            color: white;
        }

        table.dataTable {
            width: 100% !important;
        }

        .swal2-popup {
            background-color: #202022;
        }
    </style>
    <?= view('user_dashboard/layout/style_override') ?>
    <?= $this->renderSection('style') ?>
</head>

<body class=" ">
    <!-- loader Start -->
    <div id="loading">
        <div class="loader simple-loader">
            <div class="loader-body"></div>
        </div>
    </div>
    <!-- loader END -->

    <?= view('user_dashboard/layout/sidebar') ?>

    <main class="main-content">
        <div class="position-relative">
            <?= view('user_dashboard/layout/navbar') ?>

        </div>
        <div class="container-fluid content-inner pb-0">
            <div>
                <?= $this->renderSection('slot') ?>
            </div>
        </div>

        <?= view('user_dashboard/layout/footer') ?>

    </main>

    <form method="post" action="<?= route('logoutPost') ?>" id="logoutForm" style="display:none;">
        <?= csrf_field() ?>
    </form>

    <!-- Wrapper End-->
    <!-- offcanvas start -->

    <script src="<?= user_asset('js/jquery-3.5.1.min.js') ?>"></script>

    <!-- Backend Bundle JavaScript -->
    <script src="<?= base_url('coinex/js/libs.min.js') ?>"></script>
    <!-- widgetchart JavaScript -->
    <script src="<?= base_url('coinex/js/charts/widgetcharts.js') ?>"></script>
    <script src="<?= base_url('twebsol/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
    <script src="<?= user_asset('js/notify/bootstrap-notify.min.js') ?>"></script>
    <!-- fslightbox JavaScript -->
    <script src="<?= base_url('coinex/js/fslightbox.js') ?>"></script>
    <!-- app JavaScript -->
    <script src="<?= base_url('coinex/js/app.js') ?>"></script>
    <!-- apexchart JavaScript -->
    <script src="<?= base_url('coinex/js/charts/apexcharts.js') ?>"></script>
    <script src="<?= base_url('twebsol/plugins/tippy/tippy.min.js') ?>"></script>
    <script src="<?= base_url('twebsol/plugins/jquery-validator/jquery.validate.min.js') ?>"></script>
    <script src="<?= base_url('twebsol/plugins/jquery-validator/jquery.validate.extend.js') ?>"></script>

    <script src="<?= base_url('twebsol/scripts/user-script.js') ?>"></script>
    <script src="<?= base_url('twebsol/scripts/main.js') ?>"></script>

    <script src="<?= base_url('twebsol/user/dashboard.js') ?>?v=1.6"></script>


    <?= $this->renderSection('script') ?>

    <script>
        <?= view('user_dashboard/layout/_notifs'); ?>
    </script>
</body>

</html>