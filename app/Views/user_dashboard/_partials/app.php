<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Cuba admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, Cuba admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="<?= user_asset('images/favicon.png') ?>" type="image/x-icon">
    <link rel="shortcut icon" href="<?= user_asset('images/favicon.png') ?>" type="image/x-icon">

    <title>
        <?= isset($page_title) ? "$page_title | " : '' ?>
        <?= data('company_name') ?>
    </title>



    <?php if (isProduction()): ?>

        <link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap"
            rel="stylesheet">

        <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap"
            rel="stylesheet">

        <link rel="stylesheet" type="text/css"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <link rel="stylesheet" type="text/css" href="<?= user_asset('css/vendors/bootstrap-production.css') ?>">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/themify-icons/0.1.2/css/themify-icons.css">

        <link rel="stylesheet" type="text/css"
            href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.2/dist/sweetalert2.min.css">

    <?php else: ?>

        <link rel="stylesheet" href="<?= user_asset('text-fonts/roboto.css') ?>">

        <link rel="stylesheet" href="<?= user_asset('text-fonts/rubik.css') ?>">

        <link rel="stylesheet" type="text/css" href="<?= user_asset('fonts/font-awesome/css/font-awesome.css') ?>">

        <link rel="stylesheet" href="<?= user_asset('css/vendors/bootstrap.css') ?>">

        <link rel="stylesheet" type="text/css" href="<?= user_asset('css/vendors/themify.css') ?>">

        <link rel="stylesheet" type="text/css" href="<?= base_url('twebsol/plugins/sweetalert2/sweetalert2.min.css') ?>">

    <?php endif; ?>


    <link rel="stylesheet" type="text/css" href="<?= user_asset('css/vendors/icofont.css') ?>">

    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="<?= user_asset('css/vendors/flag-icon.css') ?>">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="<?= user_asset('css/vendors/feather-icon.css') ?>">



    <link rel="stylesheet" type="text/css" href="<?= user_asset('css/vendors/scrollbar.css') ?>">

    <link rel="stylesheet" type="text/css" href="<?= user_asset('css/style.css') ?>">
    <link id="color" rel="stylesheet" href="<?= user_asset('css/color-1.css') ?>" media="screen">
    <link rel="stylesheet" type="text/css" href="<?= user_asset('css/responsive.css') ?>">

    <style>
        .page-wrapper.compact-wrapper .nav-right .nav-menus {
            margin-right: 0px !important;
        }

        .page-wrapper .page-header .header-wrapper {
            padding: 15px 30px 7px 30px !important;
        }

        .page-wrapper .page-header .header-wrapper .nav-right ul li i,
        .navbar-icon {
            font-size: 18px !important;
        }

        .page-wrapper .page-header .header-wrapper .nav-right.right-header ul li .mode {
            width: inherit !important;
            text-align: inherit !important;
        }

        .page-wrapper .page-body-wrapper .page-title {
            margin: -19px -27px 38px;
        }

        @media only screen and (max-width: 990.98px) {
            .page-wrapper .page-body-wrapper .page-title {
                margin-top: -2px;
            }
        }

        @media only screen and (max-width: 580.98px) {
            .page-wrapper .page-body-wrapper .page-title {
                margin-top: -12px;
            }
        }
    </style>
</head>

<body>
    <!-- loader starts-->
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
    <!-- loader ends-->
    <!-- tap on top starts-->
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->
    <!-- page-wrapper Start-->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        <!-- Page Header Start-->
        <div class="page-header">
            <div class="header-wrapper row m-0">
                <form class="form-inline search-full col" action="#" method="get">
                    <div class="form-group w-100">
                        <div class="Typeahead Typeahead--twitterUsers">
                            <div class="u-posRelative">
                                <input class="demo-input Typeahead-input form-control-plaintext w-100" type="text"
                                    placeholder="Search Cuba .." name="q" title="" autofocus>
                                <div class="spinner-border Typeahead-spinner" role="status"><span
                                        class="sr-only">Loading...</span></div><i class="close-search"
                                    data-feather="x"></i>
                            </div>
                            <div class="Typeahead-menu"></div>
                        </div>
                    </div>
                </form>
                <div class="header-logo-wrapper col-auto p-0">
                    <div class="logo-wrapper"><a href="index.html"><img class="img-fluid"
                                src="<?= user_asset('images/logo/logo.png') ?>?v=0.1" alt=""></a></div>
                    <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle"
                            data-feather="align-center"></i></div>
                </div>
                <div class="left-header col horizontal-wrapper ps-0">

                </div>
                <div class="nav-right col-8 pull-right right-header p-0">
                    <ul class="nav-menus">
                        <li class="onhover-dropdown">
                            <div class="notification-box">
                                <i class="fa-regular fa-bell navbar-icon"> </i>
                                <span class="badge rounded-pill badge-secondary">4 </span>
                            </div>
                            <div class="onhover-show-div notification-dropdown">
                                <h6 class="f-18 mb-0 dropdown-title">Notitications </h6>
                                <ul>
                                    <li class="b-l-primary border-4">
                                        <p>Delivery processing <span class="font-danger">10 min.</span></p>
                                    </li>
                                    <li class="b-l-success border-4">
                                        <p>Order Complete<span class="font-success">1 hr</span></p>
                                    </li>
                                    <li class="b-l-info border-4">
                                        <p>Tickets Generated<span class="font-info">3 hr</span></p>
                                    </li>
                                    <li class="b-l-warning border-4">
                                        <p>Delivery Complete<span class="font-warning">6 hr</span></p>
                                    </li>
                                    <li><a class="f-w-700" href="#">Check all</a></li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <div class="mode">
                                <i class="fa-solid fa-moon navbar-icon"></i>
                            </div>
                        </li>
                        <li class=" onhover-dropdown p-0 me-0">
                            <div class="media profile-media ms-2"><img class="b-r-10"
                                    src="<?= user_asset('images/dashboard/profile.jpg') ?>" alt="">
                                <div class="media-body"><span>Emay Walter</span>
                                    <p class="mb-0 font-roboto">Admin <i class="middle fa-solid fa-angle-down"></i></p>
                                </div>
                            </div>
                            <ul class="profile-dropdown onhover-show-div">
                                <li><a href="#"><i data-feather="user"></i><span>Account </span></a></li>
                                <li><a href="#"><i data-feather="mail"></i><span>Inbox</span></a></li>
                                <li><a href="#"><i data-feather="file-text"></i><span>Taskboard</span></a></li>
                                <li><a href="#"><i data-feather="settings"></i><span>Settings</span></a></li>
                                <li><a href="#"><i data-feather="log-in"> </i><span>Log in</span></a></li>
                            </ul>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
        <!-- Page Header Ends                              -->
        <!-- Page Body Start-->
        <div class="page-body-wrapper">
            <!-- Page Sidebar Start-->
            <div class="sidebar-wrapper">
                <div>
                    <div class="logo-wrapper"><a href="index.html"><img class="img-fluid for-light"
                                src="<?= user_asset('images/logo/logo.png') ?>?v=0.1" alt=""><img
                                class="img-fluid for-dark" src="<?= user_asset('images/logo/logo_dark.png') ?>?v=0.1"
                                alt=""></a>
                        <div class="back-btn"><i class="fa-solid fa-angle-left"></i></div>
                        <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid">
                            </i></div>
                    </div>
                    <div class="logo-icon-wrapper"><a href="index.html"><img class="img-fluid"
                                src="<?= user_asset('images/logo/logo-icon.png') ?>" alt=""></a></div>
                    <nav class="sidebar-main">
                        <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
                        <div id="sidebar-menu">
                            <ul class="sidebar-links" id="simple-bar">
                                <li class="back-btn"><a href="index.html"><img class="img-fluid"
                                            src="<?= user_asset('images/logo/logo-icon.png') ?>" alt=""></a>
                                    <div class="mobile-back text-end"><span>Back</span><i
                                            class="fa-solid fa-angle-right ps-2" aria-hidden="true"></i></div>
                                </li>
                                <li class="sidebar-main-title">
                                    <div>
                                        <h6 class="lan-1">General</h6>
                                        <p class="lan-2">Dashboards,widgets & layout.</p>
                                    </div>
                                </li>
                                <li class="sidebar-list">
                                    <label class="badge badge-light-primary">2</label><a
                                        class="sidebar-link sidebar-title" href="#"><i data-feather="home"></i><span
                                            class="lan-3">Dashboard </span></a>
                                    <ul class="sidebar-submenu">
                                        <li><a class="lan-4" href="index.html">Default</a></li>
                                        <li><a class="lan-5" href="dashboard-02.html">Ecommerce</a></li>
                                    </ul>
                                </li>
                                <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#"><i
                                            data-feather="airplay"></i><span class="lan-6">Widgets</span></a>
                                    <ul class="sidebar-submenu">
                                        <li><a href="general-widget.html">General</a></li>
                                        <li><a href="chart-widget.html">Chart</a></li>
                                    </ul>
                                </li>
                                <li class="sidebar-list"><a class="sidebar-link sidebar-title" href="#"><i
                                            data-feather="layout"></i><span class="lan-7">Page layout</span></a>
                                    <ul class="sidebar-submenu">
                                        <li><a href="box-layout.html">Boxed</a></li>
                                        <li><a href="layout-rtl.html">RTL</a></li>
                                        <li><a href="layout-dark.html">Dark Layout</a></li>
                                        <li><a href="hide-on-scroll.html">Hide Nav Scroll</a></li>
                                        <li><a href="footer-light.html">Footer Light</a></li>
                                        <li><a href="footer-dark.html">Footer Dark</a></li>
                                        <li><a href="footer-fixed.html">Footer Fixed</a></li>
                                    </ul>
                                </li>

                            </ul>
                        </div>
                        <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
                    </nav>
                </div>
            </div>
            <!-- Page Sidebar Ends-->
            <div class="page-body">
                <div class="container-fluid">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-6">
                                <h3>Sample Page</h3>
                            </div>
                            <div class="col-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html"> <i data-feather="home"></i></a>
                                    </li>
                                    <li class="breadcrumb-item">Pages</li>
                                    <li class="breadcrumb-item active">Sample Page</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Sample Card</h5><span>lorem ipsum dolor sit amet, consectetur adipisicing
                                        elit</span>
                                </div>
                                <div class="card-body">
                                    <p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                                        incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
                                        nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                        Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                                        fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
                                        culpa qui officia deserunt mollit anim id est laborum."</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid Ends-->
            </div>
            <!-- footer start-->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 footer-copyright text-center">
                            <p class="mb-0">Copyright 2021 © Cuba theme by pixelstrap </p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
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

    <script src="<?= user_asset('js/icons/feather-icon/feather.min.js') ?>"></script>
    <script src="<?= user_asset('js/icons/feather-icon/feather-icon.js') ?>"></script>


    <script src="<?= base_url('twebsol/plugins/jquery-validator/jquery.validate.extend.js') ?>"></script>

    <script src="<?= user_asset('js/config.js') ?>"></script>

    <script src="<?= base_url('twebsol/scripts/show-passwords.js') ?>"></script>

    <script src="<?= base_url('twebsol/scripts/user-script.js') ?>"></script>

    <script src="<?= base_url('twebsol/scripts/main.js') ?>"></script>

    <script src="<?= user_asset('js/scrollbar/simplebar.js') ?>"></script>
    <script src="<?= user_asset('js/scrollbar/custom.js') ?>"></script>
    <script src="<?= user_asset('js/sidebar-menu.js') ?>"></script>
    <script src="<?= user_asset('js/tooltip-init.js') ?>"></script>
    <script src="<?= user_asset('js/script.js') ?>"></script>

    <?php $this->renderSection('script') ?>
</body>

</html>