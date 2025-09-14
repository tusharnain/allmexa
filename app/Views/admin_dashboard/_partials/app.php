<?php

$sidebarMenuJson = \App\Twebsol\AdminSidebarMenu::getSideberMenuJson(cache: true);

$adminName = admin('full_name');
$dashboardUrl = route('admin.home');
$profilePic = base_url('images/logo.png');
$logoDirectory = base_url('xassets/images');
$companyName = data('company_name');
$developer = data('developer');
$currentYear = date('Y');
?>


<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>
        <?= $page_title ?? 'Admin Dashboard' ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="<?= admin_asset('images/favicon.ico') ?>">

    <meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>" id="csrf">

    <!-- Bootstrap Css -->
    <?php if (request()->getGet('dark')): ?>
        <link href="<?= admin_asset('css/bootstrap-dark.min.css') ?>" id="bootstrap-style" rel="stylesheet"
            type="text/css" />

        <!-- App Css-->
        <link href="<?= admin_asset('css/app-dark.min.css') ?>" id="app-style" rel="stylesheet" type="text/css" />

    <?php else: ?>

        <link href="<?= admin_asset('css/bootstrap.min.css') ?>" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="<?= admin_asset('css/app.min.css') ?>" id="app-style" rel="stylesheet" type="text/css" />

    <?php endif; ?>

    <!-- Icons Css -->
    <link href="<?= admin_asset('css/icons.min.css') ?>" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="<?= admin_asset('libs/izitoast/izitoast.min.css') ?>">


    <link href="<?= admin_asset('libs/sweetalert2/sweetalert2.min.css') ?>" rel="stylesheet" type="text/css" />

    <link href="<?= admin_asset('libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') ?>" rel="stylesheet"
        type="text/css" />

    <link href="<?= admin_asset('css/custom.css') ?>" rel="stylesheet" type="text/css" />

    <link href="<?= base_url('twebsol/styles/main.css') ?>" rel="stylesheet" />

    <script src="<?= base_url('twebsol/scripts/head.js') ?>"></script>


    <?= $this->renderSection('style') ?>

    <style>
        .highlight {
            background-color: yellow;
        }
    </style>
    <script>
        let FF_FOUC_FIX;
    </script>
</head>

<body data-topbar="dark">

    <?php if (_setting('admin_dashboard_preloader')): ?>
        <!-- Loader -->
        <div id="preloader">
            <div id="status">
                <div class="spinner">
                    <i class="ri-loader-line spin-icon"></i>
                </div>
            </div>
        </div>
    <?php endif; ?>


    <!-- Begin page -->
    <div id="layout-wrapper">


        <header id="page-topbar"></header>


        <div class="vertical-menu" id="sidebar-parent"></div>

        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid p-0">

                    <!-- start page title -->
                    <div class="row px-md-3">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">
                                    <?= $dashboard_title ?? '' ?>
                                </h4>

                                <?= $this->include('admin_dashboard/_partials/_breadcrumbs') ?>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <?= $this->renderSection('slot') ?>

                </div> <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <footer class="footer" id="footer-parent"></footer>

        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <form action="<?= route('admin.logoutPost') ?>" method="POST" id="logoutForm" style="display:none;">
        <?= csrf_field() ?>
    </form>


    <!-- JAVASCRIPT -->
    <script src="<?= admin_asset('libs/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('twebsol/plugins/jquery-validator/jquery.validate.min.js') ?>"></script>
    <script src="<?= base_url('twebsol/plugins/jquery-validator/jquery.validate.extend.js') ?>"></script>


    <!-- placed right, no displacement required -->
    <script src="<?= base_url('twebsol/admin/dashboard.js') ?>?v=1"></script>
    <script>
        const sidebar = <?= $sidebarMenuJson ?>;

        setupSidebar(sidebar, {
            profilePic: "<?= $profilePic ?>",
            adminName: "<?= $adminName ?>",
        });

        setupHeader({
            profilePic: "<?= $profilePic ?>",
            adminName: "<?= $adminName ?>",
            logoDirectory: "<?= $logoDirectory ?>",
            dashboardUrl: "<?= $dashboardUrl ?>",
        });

        setupFooter({
            companyName: "<?= $companyName ?>",
            developer: "<?= $developer ?>",
            currentYear: "<?= $currentYear ?>",
        });
    </script>

    <script src="<?= admin_asset('libs/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= admin_asset('libs/metismenu/metisMenu.min.js') ?>"></script>

    <script src="<?= admin_asset('libs/sweetalert2/sweetalert2.min.js') ?>"></script>
    <script src="<?= admin_asset('libs/simplebar/simplebar.min.js') ?>"></script>
    <script src="<?= admin_asset('libs/node-waves/waves.min.js') ?>"></script>

    <script src="<?= admin_asset('libs/datatables.net/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= admin_asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>

    <script src="<?= admin_asset('libs/izitoast/izitoast.min.js') ?>"></script>

    <script src="<?= admin_asset('js/app.js') ?>"></script>

    <script src="<?= base_url('twebsol/scripts/admin-script.js') ?>?v=1"></script>

    <script src="<?= base_url('twebsol/scripts/main.js') ?>?v=1"></script>


    <?= $this->renderSection('script') ?>
</body>

</html>