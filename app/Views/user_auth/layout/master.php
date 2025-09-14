<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        <?= ($page_title ?? '') ?> |
        <?= data('company_name') ?>
    </title>
    <!-- Favicon -->
    <meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>" id="csrf">
    <link rel="icon" href="<?= base_url('images/favicon.png') ?>?v=0.2" type="image/x-icon">
    <link rel="stylesheet" href="<?= base_url('coinex/css/libs.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('coinex/css/coinex.css?v=1.0.0') ?>">
    <link rel="stylesheet" type="text/css" href="<?= user_asset('css/loaders.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= user_asset('css/font-awesome/css/font-awesome.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('twebsol/plugins/sweetalert2/sweetalert2.min.css') ?>">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <link rel="stylesheet" type="text/css" href="<?= user_asset('main.css') ?>">

    <style>
        #sid_alert,
        #login_success_alert {
            display: none;
        }

        .swal2-popup {
            background-color: #202022;
        }

        .select2-selection {
            background-color: #202022 !important;
            border: 1px solid #313135 !important;
            /* color: #ffffff !important; */
        }

        .select2-selection__rendered {
            color: #ffffff !important;
        }

        .select2-dropdown {
            background-color: #202022 !important;
            border: 1px solid #313135 !important;
            color: #ffffff !important;
        }

        .select2-search__field {
            background-color: #202022 !important;
            color: #ffffff !important;
            border: 1px solid #313135 !important;
        }

        
    </style>

    <?= $this->renderSection('style') ?>

    <script src="<?= base_url('twebsol/scripts/head.js') ?>"></script>
</head>

<body class="" data-bs-spy="scroll" data-bs-target="#elements-section" data-bs-offset="0" tabindex="0">
    <!-- loader Start -->
    <div id="loading">
        <div class="loader simple-loader">
            <div class="loader-body"></div>
        </div>
    </div>
    <!-- loader END -->
    <div style="background-image: url('<?= base_url('coinex/images/auth/01.png') ?>')">
        <div class="wrapper">
            <section class="vh-100 bg-image">
                <div class="container h-100">
                    <div class="row justify-content-center h-100 align-items-center">
                        <?php $this->renderSection('slot') ?>
                    </div>
                </div>
            </section>
        </div>
    </div>


    <script src="<?= user_asset('js/jquery-3.5.1.min.js') ?>"></script>


    <!-- Backend Bundle JavaScript -->

    <!-- widgetchart JavaScript -->
    <script src="<?= base_url('coinex/js/charts/widgetcharts.js') ?>"></script>
    <!-- fslightbox JavaScript -->
    <script src="<?= base_url('coinex/js/fslightbox.js') ?>"></script>
    <!-- app JavaScript -->
    <script src="<?= base_url('coinex/js/app.js') ?>"></script>

    <script src="<?= base_url('twebsol/plugins/jquery-validator/jquery.validate.min.js') ?>"></script>
    <script src="<?= base_url('twebsol/plugins/jquery-validator/additional-methods.min.js') ?>"></script>
    <script src="<?= base_url('twebsol/plugins/jquery-validator/jquery.validate.extend.js') ?>"></script>

    <script src="<?= base_url('twebsol/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="<?= base_url('twebsol/scripts/show-passwords.js') ?>"></script>

    <script src="<?= base_url('twebsol/scripts/user-script.js') ?>"></script>

    <script src="<?= base_url('twebsol/scripts/main.js') ?>"></script>

    <?= $this->renderSection('script') ?>

    <script>
        $(function() {
            $('.select2').select2({
                theme: 'bootstrap-5'
            });
        });
    </script>

</body>

</html>