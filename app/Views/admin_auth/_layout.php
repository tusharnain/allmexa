<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>
        <?= $page_title ? "$page_title | " . data('company_name') : '' ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- App favicon -->
    <link rel="shortcut icon" href="<?= admin_asset('images/favicon.ico') ?>">

    <!-- Bootstrap Css -->
    <link href="<?= admin_asset('css/bootstrap.min.css') ?>" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?= admin_asset('css/icons.min.css') ?>" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?= admin_asset('css/app.min.css') ?>" id="app-style" rel="stylesheet" type="text/css" />

    <link href="<?= admin_asset('libs/sweetalert2/sweetalert2.min.css') ?>" rel="stylesheet" type="text/css" />

    <link href="<?= base_url('twebsol/styles/main.css') ?>" rel="stylesheet" />

    <script src="<?= base_url('twebsol/scripts/head.js') ?>"></script>

    <?= $this->renderSection('style') ?>

</head>

<body class="auth-body-bg">
    <div class="bg-overlay"></div>
    <div class="wrapper-page">


        <?= $this->renderSection('slot'); ?>

    </div>


    <!-- JAVASCRIPT -->
    <script src="<?= admin_asset('libs/jquery/jquery.min.js') ?>"></script>


    <script src="<?= base_url('twebsol/plugins/jquery-validator/jquery.validate.min.js') ?>"></script>
    <script src="<?= base_url('twebsol/plugins/jquery-validator/jquery.validate.extend.js') ?>"></script>


    <script src="<?= admin_asset('libs/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= admin_asset('libs/node-waves/waves.min.js') ?>"></script>

    <script src="<?= admin_asset('libs/sweetalert2/sweetalert2.min.js') ?>"></script>

    <script src="<?= admin_asset('js/show-password.js') ?>"></script>

    <script src="<?= base_url('twebsol/scripts/main.js') ?>"></script>

    <script src="<?= base_url('twebsol/scripts/admin-script.js') ?>"></script>

    <script>
        Waves.init()
    </script>

    <?= $this->renderSection('script'); ?>
</body>

</html>