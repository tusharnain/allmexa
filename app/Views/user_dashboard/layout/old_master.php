<?php
$sidebarMenuJson = \App\Twebsol\UserSidebarMenu::getSideberMenuJson(cache: false);
$dashboardUrl = route('user.home');
$logoDirectory = user_asset('images/logo');
$userAssetDir = user_asset();
$profilePicture = \App\Models\UserModel::getAvatar(user());
$profileUrl = route('user.profile.profileUpdate');
$changePasswordUrl = route('user.profile.changePassword');
$companyName = data('company_name');
$developer = data('developer');
$currentYear = date('Y');
$userName = user('full_name');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>" id="csrf">
    <meta name="user_id" content="<?= user('user_id') ?>" id="meta_user_id">
    <link rel="icon" href="<?= user_asset('images/favicon.png') ?>" type="image/x-icon">
    <link rel="shortcut icon" href="<?= user_asset('images/favicon.png') ?>" type="image/x-icon">
    <title>
        <?= isset($page_title) ? $page_title : '' ?>
    </title>
    <style>
        body {
            padding: 0 !important;
        }
    </style>

    <link rel="stylesheet" href="<?= user_asset('text-fonts/roboto.css') ?>">
    <link rel="stylesheet" href="<?= user_asset('text-fonts/rubik.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= user_asset('css/font-awesome/css/font-awesome.css') ?>">
    <link rel="stylesheet" href="<?= user_asset('css/vendors/bootstrap.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= user_asset('css/vendors/themify.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('twebsol/plugins/sweetalert2/sweetalert2.min.css') ?>">

    <link rel="stylesheet" type="text/css" href="<?= base_url('twebsol/plugins/tippy/tippy-custom-theme.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('twebsol/plugins/tippy/tippy.animation.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= user_asset('css/vendors/feather-icon.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= user_asset('css/vendors/slick.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= user_asset('css/vendors/slick-theme.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= user_asset('css/vendors/scrollbar.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= user_asset('css/vendors/animate.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= user_asset('css/vendors/prism.css') ?>">
    <?= $this->renderSection('style') ?>

    <link rel="stylesheet" type="text/css" href="<?= base_url('coinex/css/coinex.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('coinex/css/libs.min.css') ?>">

    <!-- <link rel="stylesheet" type="text/css" href="<?= user_asset('css/style.css') ?>"> -->
    <link rel="stylesheet" type="text/css" href="<?= user_asset('css/loaders.css') ?>">
    <link id="color" rel="stylesheet" href="<?= user_asset('css/color-1.css') ?>" media="screen">
    <link rel="stylesheet" type="text/css" href="<?= user_asset('css/responsive.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('twebsol/styles/main.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= user_asset('main.css') ?>">
    <style>
        .sidebar-link i.side-icon {
            font-size: 20px;
        }

        body.dark-only .sidebar-link i.side-icon,
        body.dark-sidebar .sidebar-link i.side-icon {
            color: #fff;
        }
    </style>
    <script src="<?= base_url('twebsol/scripts/head.js') ?>"></script>
    <script>
        let FF_FOUC_FIX;
    </script>
</head>

<body <?= route('user.home') ?>?onload="startTime()" :null>
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
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        <div class="page-header" id="header-parent"></div>
        <div class="page-body-wrapper">
            <div class="sidebar-wrapper" sidebar-layout="stroke-svg" id="sidebar-parent"></div>
            <div class="page-body">
                <div class="container-fluid">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-12">
                                <?php if (isset($dashboard_title)): ?>
                                    <h6>
                                        <?= $dashboard_title ?? 'Dashboard' ?>
                                    </h6>
                                <?php endif; ?>
                            </div>
                            <div class="col-6">
                                <?php if (isset($breadcrumb)): ?>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a href="<?= route('user.home') ?>">
                                                <svg class="stroke-icon">
                                                    <use href="<?= user_asset('svg/icon-sprite.svg#stroke-home') ?>">
                                                    </use>
                                                </svg></a>
                                        </li>
                                        <li class="breadcrumb-item active">
                                            <?= $breadcrumb ?>
                                        </li>
                                    </ol>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?= $this->renderSection('slot') ?>
            </div>
            <footer class="footer" id="footer-parent"></footer>
        </div>
    </div>
    <form method="post" action="<?= route('logoutPost') ?>" id="logoutForm" style="display:none;">
        <?= csrf_field() ?>
    </form>

    <script src="<?= user_asset('js/bootstrap/popper.min.js') ?>"></script>
    <script src="<?= user_asset('js/jquery-3.5.1.min.js') ?>"></script>
    <script src="<?= base_url('twebsol/plugins/jquery-validator/jquery.validate.min.js') ?>"></script>
    <!-- <script src="<?= base_url('twebsol/plugins/jquery-validator/additional-methods.min.js') ?>"></script> -->
    <script src="<?= user_asset('js/bootstrap/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('twebsol/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
    <script src="<?= base_url('twebsol/plugins/tippy/tippy.min.js') ?>"></script>

    <script src="<?= base_url('twebsol/plugins/jquery-validator/jquery.validate.extend.js') ?>"></script>
    <script src="<?= base_url('twebsol/user/dashboard.js') ?>?v=1.5"></script>
    <script>
        const sidebar = <?= $sidebarMenuJson ?>;
        const dashboardUrl = "<?= $dashboardUrl ?>";
        const logoDirectory = "<?= $logoDirectory ?>";
        const userAssetDir = "<?= $userAssetDir ?>";
        setupHeader({
            profilePicUrl: "<?= $profilePicture ?>",
            profileUrl: "<?= $profileUrl ?>",
            changePasswordUrl: "<?= $changePasswordUrl ?>",
            userName: "<?= $userName ?>",
            logoDirectory: logoDirectory,
            userAssetDir: userAssetDir,
            dashboardUrl: dashboardUrl,
        });
        setupSidebar(sidebar, {
            dashboardUrl: dashboardUrl,
            logoDirectory: logoDirectory
        });
        setupFooter({
            companyName: "<?= $companyName ?>",
            developer: "<?= $developer ?>",
            currentYear: "<?= $currentYear ?>",
            dashboardUrl: dashboardUrl,
        });
    </script>
    <script src="<?= user_asset('js/notify/bootstrap-notify.min.js') ?>"></script>
    <script src="<?= user_asset('js/icons/feather-icon/feather.min.js') ?>"></script>
    <script src="<?= user_asset('js/icons/feather-icon/feather-icon.js') ?>"></script>
    <script src="<?= user_asset('js/scrollbar/simplebar.js') ?>"></script>
    <script src="<?= user_asset('js/scrollbar/custom.js') ?>"></script>
    <script src="<?= user_asset('js/config.js') ?>"></script>
    <script src="<?= user_asset('js/sidebar-menu.js') ?>"></script>
    <script src="<?= user_asset('js/slick/slick.min.js') ?>"></script>
    <script src="<?= user_asset('js/slick/slick.js') ?>"></script>
    <script src="<?= user_asset('js/header-slick.js') ?>"></script>
    <script src="<?= user_asset('js/chart/apex-chart/apex-chart.js') ?>"></script>
    <script src="<?= user_asset('js/animation/wow/wow.min.js') ?>"></script>
    <script src="<?= user_asset('js/prism/prism.min.js') ?>"></script>
    <script src="<?= base_url('twebsol/scripts/user-script.js') ?>"></script>
    <script src="<?= base_url('twebsol/scripts/main.js') ?>"></script>
    <?= $this->renderSection('script') ?>
    <script src="<?= user_asset('js/script.js') ?>"></script>
    <script src="<?= user_asset('js/theme-customizer/customizer.js') ?>"></script>
    <script>
        new WOW().init();

        <?= view('user_dashboard/layout/_notifs'); ?>
    </script>
</body>

</html>