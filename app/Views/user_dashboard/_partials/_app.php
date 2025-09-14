<?php
$sidebarMenuJson = \App\Twebsol\UserSidebarMenu::getSideberMenuJson(cache: true);
$dashboardUrl = route('user.home');
$logoDirectory = base_url('assets/images/brand');
$profilePicture = \App\Models\UserModel::getAvatar(user());
$profileUrl = route('user.profile.profileUpdate');
$changePasswordUrl = route('user.profile.changePassword');
$companyName = data('company_name');
$developer = data('developer');
$currentYear = date('Y');
$userName = user('full_name');

// Theme Preferences
$theme = request()->getCookie('theme-preferences');

?>


<!doctype html>
<html lang="en" dir="ltr">

<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>" id="csrf">
    <meta name="user_id" content="<?= user('user_id') ?>" id="meta_user_id">


    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('assets/images/brand/favicon.ico') ?>" />


    <title>
        <?= isset($page_title) ? "$page_title | " : '' ?>
        <?= data('company_name') ?>
    </title>

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="<?= base_url('assets/plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet" />

    <!-- STYLE CSS -->
    <link href="<?= base_url('assets/css/style.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/dark-style.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/transparent-style.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/skin-modes.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('twebsol/plugins/nprogress/nprogress.css') ?>" rel="stylesheet" />

    <link rel="stylesheet" href="<?= base_url('twebsol/plugins/tippy@6.3.7/tippy-custom-theme.css') ?>">
    <link rel="stylesheet" href="<?= base_url('twebsol/plugins/tippy@6.3.7/tippy.animation.min.css') ?>">
    <!--- FONT-ICONS CSS -->
    <link href="<?= base_url('assets/css/icons.css') ?>" rel="stylesheet" />

    <!-- COLOR SKIN CSS -->
    <link id="theme" rel="stylesheet" type="text/css" media="all" href="<?= base_url('assets/colors/color1.css') ?>" />

    <link href="<?= base_url('twebsol/styles/main.css') ?>" rel="stylesheet" />


    <script src="<?= base_url('twebsol/scripts/head.js') ?>"></script>

    <?= $this->renderSection('style'); ?>

    <style>
        .slide-menu li .slide-item::before {
            content: ''
        }
    </style>

</head>

<body class="app sidebar-mini ltr <?= $theme ?? '' ?>">

    <div id="transparent-bg-spin"></div>


    <!-- GLOBAL-LOADER -->
    <div id="global-loader">
        <img src="<?= base_url('assets/images/loader.svg') ?>" class="loader-img" alt="Loader">
    </div>
    <!-- /GLOBAL-LOADER -->

    <!-- PAGE -->
    <div class="page">
        <div class="page-main">

            <!-- header -->
            <div class="app-header header sticky" id="header-parent"></div>

            <!-- sidebar -->
            <div class="sticky" id="sidebar-parent"></div>


            <!--app-content open-->
            <div class="main-content app-content mt-0">
                <div class="side-app">

                    <!-- CONTAINER -->
                    <div class="main-container container-fluid">

                        <!-- PAGE-HEADER -->
                        <div class="page-header mb-0">
                            <?php if (isset($dashboard_title)): ?>
                                <h1 class="page-title">
                                    <?= $dashboard_title ?>
                                </h1>
                            <?php endif; ?>

                            <?= $this->include('user_dashboard/_partials/_breadcrumbs') ?>
                        </div>
                        <!-- PAGE-HEADER END -->


                        <?= $this->renderSection('slot'); ?>

                    </div>
                    <!-- CONTAINER CLOSED -->

                </div>
            </div>
            <!--app-content closed-->
        </div>



        <footer class="footer" id="footer-parent"></footer>

    </div>

    <!-- BACK-TO-TOP -->
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

    <form method="post" action="<?= route('logoutPost') ?>" id="logoutForm" style="display:none;">
        <?= csrf_field() ?>
    </form>


    <!-- JQUERY JS -->
    <script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>

    <script src="<?= base_url('twebsol/plugins/jquery-validator/jquery.validate.min.js') ?>"></script>
    <script src="<?= base_url('twebsol/plugins/jquery-validator/jquery.validate.extend.js') ?>"></script>
    <script src="<?= base_url('twebsol/plugins/jquery-validator/additional-methods.min.js') ?>"></script>


    <!-- dashboard scripts -->
    <script src="<?= base_url('twebsol/user/dashboard.js') ?>?v=1.5"></script>
    <script>
        const sidebar = <?= $sidebarMenuJson ?>;

        const dashboardUrl = "<?= $dashboardUrl ?>";
        const logoDirectory = "<?= $logoDirectory ?>";


        setupHeader({
            profilePicUrl: "<?= $profilePicture ?>",
            profileUrl: "<?= $profileUrl ?>",
            changePasswordUrl: "<?= $changePasswordUrl ?>",
            userName: "<?= $userName ?>",
            logoDirectory: logoDirectory,
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
    <!-- dashboard scripts -->


    <!-- BOOTSTRAP JS -->
    <script src="<?= base_url('assets/plugins/bootstrap/js/popper.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.min.js') ?>"></script>



    <!-- SIDE-MENU JS -->
    <script src="<?= base_url('assets/plugins/sidemenu/sidemenu.js') ?>"></script>


    <!-- Perfect SCROLLBAR JS-->
    <script src="<?= base_url('assets/plugins/p-scroll/perfect-scrollbar.js') ?>"></script>

    <script src="<?= base_url('assets/plugins/sweet-alert/sweetalert2.min.js') ?>"></script>
    <script src="<?= base_url('twebsol/plugins/nprogress/nprogress.js') ?>"></script>
    <script src="<?= base_url('twebsol/plugins/tippy@6.3.7/tippy.min.js') ?>"></script>

    <!-- Color Theme js -->
    <script src="<?= base_url('assets/js/themeColors.js') ?>"></script>

    <!-- Sticky js -->
    <script src="<?= base_url('assets/js/sticky.js') ?>"></script>

    <!-- CUSTOM JS -->
    <script src="<?= base_url('assets/js/custom.js') ?>"></script>

    <script src="<?= base_url('twebsol/scripts/user-script.js') ?>"></script>

    <script src="<?= base_url('twebsol/scripts/main.js') ?>"></script>


    <?= $this->renderSection('script'); ?>



</body>

</html>