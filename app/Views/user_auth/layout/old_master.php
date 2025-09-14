<?php

$darkMode = _setting('user_dashboard_dark_mode', default: false);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>" id="csrf">

    <link rel="icon" href="<?= user_asset('images/favicon.png') ?>" type="image/x-icon">

    <title>
        <?= ($page_title ?? '') ?> |
        <?= data('company_name') ?>
    </title>


    <?php if (isProduction()): ?>
        <link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap"
            rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap"
            rel="stylesheet">
    <?php else: ?>
        <link rel="stylesheet" href="<?= user_asset('text-fonts/roboto.css') ?>">
        <link rel="stylesheet" href="<?= user_asset('text-fonts/rubik.css') ?>">
    <?php endif; ?>


    <style>
        body {
            overflow-y: scroll !important;
        }

        .login-card {
            background: linear-gradient(178.1deg, rgb(60, 55, 106) 8.5%, rgb(23, 20, 69) 82.4%) !important;
        }

        #sid_alert,
        #login_success_alert {
            display: none;
        }
    </style>

    <?php if (isProduction()): ?>

        <link rel="stylesheet" type="text/css"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <link rel="stylesheet" type="text/css" href="<?= user_asset('css/vendors/bootstrap-production.css') ?>">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/themify-icons/0.1.2/css/themify-icons.css">

        <link rel="stylesheet" type="text/css"
            href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.2/dist/sweetalert2.min.css">

    <?php else: ?>

        <link rel="stylesheet" type="text/css" href="<?= user_asset('css/font-awesome/css/font-awesome.css') ?>">

        <link rel="stylesheet" href="<?= user_asset('css/vendors/bootstrap.css') ?>">

        <link rel="stylesheet" type="text/css" href="<?= user_asset('css/vendors/themify.css') ?>">

        <link rel="stylesheet" type="text/css" href="<?= base_url('twebsol/plugins/sweetalert2/sweetalert2.min.css') ?>">

    <?php endif; ?>




    <link rel="stylesheet" type="text/css" href="<?= user_asset('css/style.css') ?>">
    <link id="color" rel="stylesheet" href="<?= user_asset('css/color-1.css') ?>" media="screen">
    <link rel="stylesheet" type="text/css" href="<?= user_asset('css/responsive.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= user_asset('css/loaders.css') ?>">

    <link rel="stylesheet" type="text/css" href="<?= user_asset('main.css') ?>">


    <?= $this->renderSection('style') ?>

    <script src="<?= base_url('twebsol/scripts/head.js') ?>"></script>
</head>

<body>
    <div class="loader-wrapper">
        <div class="loader-index"><span></span></div>
        <svg>
            <defs></defs>
            <filter id="goo">
                <fegaussianblur in="SourceGraphic" stddeviation="11" result="blur"></fegaussianblur>
                <fecolormatrix in="blur" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo">
                </fecolormatrix>
            </filter>
        </svg>
    </div>

    <!-- tap on top starts-->
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->

    <!-- page-wrapper Start-->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">

        <!-- main Content -->
        <?= $this->renderSection('slot') ?>

    </div>

    <?php if (isProduction()): ?>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.0-beta3/js/bootstrap.bundle.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.2/dist/sweetalert2.all.min.js"></script>

    <?php else: ?>

        <script src="<?= user_asset('js/jquery-3.5.1.min.js') ?>"></script>

        <script src="<?= base_url('twebsol/plugins/jquery-validator/jquery.validate.min.js') ?>"></script>
        <script src="<?= base_url('twebsol/plugins/jquery-validator/additional-methods.min.js') ?>"></script>

        <script src="<?= user_asset('js/bootstrap/bootstrap.bundle.min.js') ?>"></script>

        <script src="<?= base_url('twebsol/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>

    <?php endif; ?>

    <script src="<?= base_url('twebsol/plugins/jquery-validator/jquery.validate.extend.js') ?>"></script>


    <script src="<?= user_asset('js/config.js') ?>"></script>

    <script src="<?= base_url('twebsol/scripts/show-passwords.js') ?>"></script>

    <script src="<?= base_url('twebsol/scripts/user-script.js') ?>"></script>

    <script src="<?= base_url('twebsol/scripts/main.js') ?>"></script>

    <?= $this->renderSection('script') ?>

    <script>
        $(document).ready(function () {
            $(".loader-wrapper").fadeOut("fast", function () {
                $(this).remove();
            });
        });
    </script>
</body>

</html>