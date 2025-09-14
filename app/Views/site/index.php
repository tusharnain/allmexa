<!DOCTYPE html>
<html lang="zxx">



<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Title -->
    <title><?= data('company_name') ?> - Welcome</title>

    <!-- Favicon -->
    <link rel="icon" href="<?= base_url('site/img/core-img/favicon.ico') ?>">

    <!-- Core Stylesheet -->
    <link rel="stylesheet" href="<?= base_url('site/css/style.css') ?>">

    <!-- Responsive Stylesheet -->
    <link rel="stylesheet" href="<?= base_url('site/css/responsive.css') ?>">

</head>

<body class="darker-blue">
    <!-- Preloader -->
    <div id="preloader">
        <div class="preload-content">
            <div id="dream-load"></div>
        </div>
    </div>

    <!-- ##### Header Area Start ##### -->
    <header class="header-area wow fadeInDown" data-wow-delay="0.2s">
        <div class="classy-nav-container breakpoint-off">
            <div class="container">
                <!-- Classy Menu -->
                <nav class="classy-navbar justify-content-between" id="dreamNav">

                    <!-- Logo -->
                    <a class="nav-brand" href="#"><img draggable="false"
                            src="<?= base_url('site/img/core-img/logo.png') ?>" alt="logo">
                        <?= data('company_name') ?>
                    </a>

                    <!-- Navbar Toggler -->
                    <div class="classy-navbar-toggler">
                        <span class="navbarToggler"><span></span><span></span><span></span></span>
                    </div>

                    <!-- Menu -->
                    <div class="classy-menu">

                        <!-- close btn -->
                        <div class="classycloseIcon">
                            <div class="cross-wrap"><span class="top"></span><span class="bottom"></span></div>
                        </div>

                        <!-- Nav Start -->
                        <div class="classynav">
                            <ul id="nav">
                                <li><a href="#home">Home</a></li>
                                <li><a href="#about">About</a></li>
                                <li><a href="#services">Services</a></li>
                                <!--<li><a href="#roadmap">Roadmap</a></li>-->
                                <li><a href="#team">Team</a></li>
                                <li><a href="#contact">Contact</a></li>
                            </ul>

                            <!-- Button -->
                            <a href="<?= route('login') ?>" class="btn login-btn ml-50">Log in</a>
                            <a href="<?= route('register') ?>" class="btn login-btn ml-50 ml-lg-2">Sign up</a>
                        </div>
                        <!-- Nav End -->
                    </div>
                </nav>
            </div>
        </div>
    </header>
    <!-- ##### Header Area End ##### -->

    <!-- ##### Welcome Area Start ##### -->
    <section class="welcome_area clearfix dzsparallaxer" data-options='{direction: "normal"}' id="home">
        <div class="divimage dzsparallaxer--target"
            style="width: 101%; height: 130%; background-image: url(<?= base_url('site/img/bg-img/bg-3.png') ?>)"></div>

        <!-- Hero Content -->
        <div class="hero-content dark-blue">
            <!-- blip -->
            <div class="dream-blip blip1"></div>
            <div class="dream-blip blip2"></div>
            <div class="dream-blip blip3"></div>
            <div class="dream-blip blip4"></div>

            <div class="container h-100">
                <div class="row h-100 align-items-center">
                    <!-- Welcome Content -->
                    <div class="col-12 col-lg-6 col-md-12">
                        <div class="welcome-content ill">
                            <div class="promo-section">
                                <div class="integration-link">
                                    <span class="integration-icon">
                                        <img draggable="false" src="<?= base_url('site/img/svg/img-dollar.svg') ?>"
                                            width="24" height="24" alt="">
                                    </span>
                                    <span class="integration-text">Discover a new ways to enjoy your World!</span>
                                </div>
                            </div>
                            <h1 class="wow fadeInUp" data-wow-delay="0.2s">Invest Your money with Highest Return <img
                                    src="<?= base_url('site/img/core-img/money.png') ?>"> .</h1>
                            <p class="wow fadeInUp" data-wow-delay="0.3s">We have over 15 year exprience in business
                                consultting arena. We have over 15 year exprience in business consultting arena and
                                artficial intelligence.</p>
                            <div class="dream-btn-group wow fadeInUp" data-wow-delay="0.4s">
                                <a href="<?= base_url('presentation.pdf') ?>" target="_blank"
                                    class="btn dream-btn mr-3">Persentation</a>
                                <a href="#" class="btn dream-btn">Contact Us</a>
                            </div>
                        </div>
                    </div>
                    <!-- Welcome Video Area -->
                    <div class="col-12 col-lg-6 col-md-12 d-none d-xl-block">
                        <img draggable="false" src="<?= base_url('site/img/wallet.png') ?>" class="center-block" alt="">

                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
    <!-- ##### Welcome Area End ##### -->

    <div class="vertical-social">
        <ul>
            <li><a href="#"><i class="fa fa-telegram" aria-hidden="true"></i></a></li>
            <li><a href="#"><i class="fa fa-medium" aria-hidden="true"></i></a></li>
            <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
            <li><a href="#"> <i class="fa fa-twitter" aria-hidden="true"></i></a></li>
            <li><a href="#"><i class="fa fa-github" aria-hidden="true"></i></a></li>
            <li><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
            <li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>

        </ul>
    </div>

    <section class="features section-padding-100-0">

        <div class="section-heading text-center">

            <div class="dream-dots justify-content-center wow fadeInUp" data-wow-delay="0.2s">
                <span></span><span></span><span></span><span></span><span></span><span></span><span></span>
            </div>
            <h2 class="wow fadeInUp" data-wow-delay="0.3s">How it works</h2>
            <p class="wow fadeInUp" data-wow-delay="0.4s"
                style="visibility: visible; animation-delay: 0.4s; animation-name: fadeInUp;">Lorem ipsum dolor sit
                amet, consectetur adipiscing elit. Sed quis accumsan nisi Ut ut felis congue nisl hendrerit commodo.</p>
        </div>

        <div class="container">
            <div class="row">
                <div class="service-img-wrapper how col-lg-6 col-md-12 col-sm-12">
                    <div class="image-box">
                        <img draggable="false" src="<?= base_url('site/img/phone.png') ?>"
                            class="center-block img-responsive phone-img" alt="">
                    </div>
                </div>
                <div class="services-column col-lg-6 offset-lg-0 col-md-10 offset-md-1 col-xs-10 offset-xs-1">
                    <!--Services Block Four-->
                    <div class="services-block-four how">
                        <div class="inner-box">
                            <div class="step">1</div>
                            <h3><a href="#">Powerfull Mobile and Online App</a></h3>
                            <div class="text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laudantium modi
                                assumenda beatae provident non hic eum dolores natus, vitae, quae, facere perferendis.
                            </div>

                        </div>
                    </div>

                    <!--Services Block Four-->
                    <div class="services-block-four how">
                        <div class="inner-box">
                            <div class="step">2</div>
                            <h3><a href="#">Brings more Transparency and Speed</a></h3>
                            <div class="text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laudantium modi
                                assumenda beatae provident non hic eum dolores natus, vitae, quae, facere perferendis.
                            </div>
                        </div>
                    </div>

                    <!--Services Block Four-->
                    <div class="services-block-four how" style="margin-bottom:0">
                        <div class="inner-box">
                            <div class="step">3</div>
                            <h3><a href="#">Special for Multiple Use Capabilities</a></h3>
                            <div class="text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laudantium modi
                                assumenda beatae provident non hic eum dolores natus, vitae, quae, facere perferendis.
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- ##### trust Area Start ##### -->
    <div class="trust-section section-padding-100">
        <div class="section-heading text-center">

            <div class="dream-dots justify-content-center wow fadeInUp" data-wow-delay="0.2s"
                style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
                <span></span><span></span><span></span><span></span><span></span><span></span><span></span>
            </div>
            <h2 class="wow fadeInUp" data-wow-delay="0.3s"
                style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInUp;">We are trusted</h2>
            <p class="wow fadeInUp" data-wow-delay="0.4s"
                style="visibility: visible; animation-delay: 0.4s; animation-name: fadeInUp;">Lorem ipsum dolor sit
                amet, consectetur adipiscing elit. Sed quis accumsan nisi Ut ut felis congue nisl hendrerit commodo.</p>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-6 col-md-3 col-lg-2">
                    <!-- Single Cool Fact -->
                    <div class="trust-item text-center wow fadeInUp" data-wow-delay="0.2s">
                        <div class="ico-platform-logo">
                            <img draggable="false" src="<?= base_url('site/img/ico-platforms/1.png') ?>" alt="">
                        </div>
                        <!-- Single Cool Detail -->
                        <div class="check">
                            <!-- <div class="value">8.9</div> -->
                            <div class="check-icon"></div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 col-lg-2">
                    <!-- Single Cool Fact -->
                    <div class="trust-item text-center wow fadeInUp" data-wow-delay="0.3s">
                        <div class="ico-platform-logo">
                            <img draggable="false" src="<?= base_url('site/img/ico-platforms/2.png') ?>" alt="">
                        </div>
                        <!-- Single Cool Detail -->
                        <div class="check">
                            <div class="value">8.9</div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 col-lg-2">
                    <!-- Single Cool Fact -->
                    <div class="trust-item text-center wow fadeInUp" data-wow-delay="0.4s">
                        <div class="ico-platform-logo">
                            <img draggable="false" src="<?= base_url('site/img/ico-platforms/3.png') ?>" alt="">
                        </div>
                        <!-- Single Cool Detail -->
                        <div class="check">
                            <!-- <div class="value">8.9</div> -->
                            <div class="check-icon"></div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 col-lg-2">
                    <!-- Single Cool Fact -->
                    <div class="trust-item text-center wow fadeInUp" data-wow-delay="0.5s">
                        <div class="ico-platform-logo">
                            <img draggable="false" src="<?= base_url('site/img/ico-platforms/4.png') ?>" alt="">
                        </div>
                        <!-- Single Cool Detail -->
                        <div class="check">
                            <!-- <div class="value">8.9</div> -->
                            <div class="check-icon"></div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 col-lg-2">
                    <!-- Single Cool Fact -->
                    <div class="trust-item text-center wow fadeInUp" data-wow-delay="0.6s">
                        <div class="ico-platform-logo">
                            <img draggable="false" src="<?= base_url('site/img/ico-platforms/5.png') ?>" alt="">
                        </div>
                        <!-- Single Cool Detail -->
                        <div class="check">
                            <div class="value">7.4</div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 col-lg-2">
                    <!-- Single Cool Fact -->
                    <div class="trust-item text-center wow fadeInUp" data-wow-delay="0.7s">
                        <div class="ico-platform-logo">
                            <img draggable="false" src="<?= base_url('site/img/ico-platforms/6.png') ?>" alt="">
                        </div>
                        <!-- Single Cool Detail -->
                        <div class="check">
                            <!-- <div class="value">8.9</div> -->
                            <div class="check-icon"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- ##### trust Area End ##### -->

    <!-- ##### About Us Area Start ##### -->
    <section class="about-us-area section-padding-0-100 clearfix" id="about">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-12 col-lg-6">
                    <div class="welcome-meter wow fadeInUp" data-wow-delay="0.7s">
                        <img draggable="false" src="<?= base_url('site/img/core-img/about1.png') ?>"
                            class="img-responsive center-block" alt="">
                        <!-- client meta -->
                        <div class="growing-company text-center mt-30 wow fadeInUp" data-wow-delay="0.8s">
                            <p>* Already growing up <span class="counter">5236</span> company</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="who-we-contant">

                        <div class="dream-dots wow fadeInUp" data-wow-delay="0.2s">
                            <span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                        </div>
                        <h4 class="wow fadeInUp" data-wow-delay="0.3s">We complete every project with extra care as
                            customer need.</h4>
                        <p class="wow fadeInUp" data-wow-delay="0.4s">Trade Club is a unique portal containing advanced
                            tools & services for traders. We provide low transaction fees & a free account opening. All
                            kinds of market alerts are available through us. </p>
                        <p class="wow fadeInUp" data-wow-delay="0.5s">Our mission is to be a single-stop, reliable, and
                            advanced trading platform for all i.e. Crypto, Forex, Shares for Traders & Investors.</p>
                        <a class="btn dream-btn mt-30 wow fadeInUp" data-wow-delay="0.6s" href="#">Read More</a>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- ##### About Us Area End ##### -->

    <!-- ##### About Us Area Start ##### -->
    <section class="about-us-area section-padding-0-100 clearfix">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-12 col-lg-6">
                    <div class="who-we-contant">

                        <div class="dream-dots wow fadeInUp" data-wow-delay="0.2s">
                            <span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                        </div>
                        <h4 class="wow fadeInUp" data-wow-delay="0.3s">A Crypto trading bot is an automated trading
                            system.</h4>
                        <p class="wow fadeInUp" data-wow-delay="0.4s">Trade Club is built with the latest technologies
                            and the best security practices that are used to create modern financial systems..</p>
                        <p class="wow fadeInUp" data-wow-delay="0.5s">Crypto-trading platform is a digital currency
                            exchange platform that allows users to trade one digital currency for another.</p>
                        <a class="btn dream-btn mt-30 wow fadeInUp" data-wow-delay="0.6s" href="#">Read More</a>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="welcome-meter wow fadeInUp" data-wow-delay="0.7s">
                        <img draggable="false" src="<?= base_url('site/img/solution.png') ?>" class="center-block"
                            alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ##### About Us Area End ##### -->


    <section class="demo-video section-before section-padding-100">
        <div class="container">
            <div class="section-heading text-center">

                <div class="dream-dots justify-content-center wow fadeInUp" data-wow-delay="0.2s">
                    <span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                </div>
                <h2 class="wow fadeInUp" data-wow-delay="0.3s">Watch our demo </h2>
                <p class="wow fadeInUp" data-wow-delay="0.4s">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Sed quis accumsan nisi Ut ut felis congue nisl hendrerit commodo.</p>
            </div>
            <!-- Welcome Video Area -->
            <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12">
                <div class="welcome-video-area wow fadeInUp" data-wow-delay="0.5s">
                    <!-- Welcome Thumbnail -->
                    <div class="welcome-thumb">
                        <img draggable="false" src="<?= base_url('site/img/bg-img/bg-4.jpg') ?>" alt="">
                    </div>
                    <!-- Video Icon -->
                    <div class="video-icon">
                        <!--<a href="https://www.youtube.com/watch?v=gbXEPHsTkgU" class="btn dream-btn video-btn" id="videobtn"><i class="fa fa-play"></i></a>-->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="clearfix"></div>

    <!-- ##### Our Services Area Start ##### -->
    <section class="our_services_area section-padding-100-70 clearfix" id="services">
        <div class="container">

            <div class="section-heading text-center">

                <div class="dream-dots justify-content-center wow fadeInUp" data-wow-delay="0.2s">
                    <span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                </div>
                <h2 class="wow fadeInUp" data-wow-delay="0.3s">Our Core Services</h2>
                <p class="wow fadeInUp" data-wow-delay="0.4s">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Sed quis accumsan nisi Ut ut felis congue nisl hendrerit commodo.</p>
            </div>


            <div class="row">
                <div class="col-12 col-sm-6 col-lg-4">
                    <!-- Content -->
                    <div class="service_single_content text-left mb-100 wow fadeInUp" data-wow-delay="0.2s">
                        <!-- Icon -->
                        <div class="service_icon">
                            <img draggable="false" src="<?= base_url('site/img/services/1.png') ?>" alt="">
                        </div>
                        <h6>Smart Trading Modules</h6>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla neque quam, maximus ut
                            accumsan ut, posuere sit Lorem ipsum.</p>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4">
                    <!-- Content -->
                    <div class="service_single_content text-left mb-100 wow wow fadeInUp" data-wow-delay="0.3s">
                        <!-- Icon -->
                        <div class="service_icon">
                            <img draggable="false" src="<?= base_url('site/img/services/2.png') ?>" alt="">
                        </div>
                        <h6>Adaptive Social Assistant</h6>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla neque quam, maximus ut
                            accumsan ut, posuere sit Lorem ipsum.</p>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4">
                    <!-- Content -->
                    <div class="service_single_content text-left mb-100 wow fadeInUp" data-wow-delay="0.4s">
                        <!-- Icon -->
                        <div class="service_icon">
                            <img draggable="false" src="<?= base_url('site/img/services/3.png') ?>" alt="">
                        </div>
                        <h6>Analyzer of the News with powerful AII </h6>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla neque quam, maximus ut
                            accumsan ut, posuere sit Lorem ipsum.</p>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4">
                    <!-- Content -->
                    <div class="service_single_content text-left mb-100 wow fadeInUp" data-wow-delay="0.5s">
                        <!-- Icon -->
                        <div class="service_icon">
                            <img draggable="false" src="<?= base_url('site/img/services/4.png') ?>" alt="">
                        </div>
                        <h6>Exchange Order Management</h6>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla neque quam, maximus ut
                            accumsan ut, posuere sit Lorem ipsum.</p>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4">
                    <!-- Content -->
                    <div class="service_single_content text-left mb-100 wow fadeInUp" data-wow-delay="0.6s">
                        <!-- Icon -->
                        <div class="service_icon">
                            <img draggable="false" src="<?= base_url('site/img/services/5.png') ?>" alt="">
                        </div>
                        <h6>Module of Price Notification</h6>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla neque quam, maximus ut
                            accumsan ut, posuere sit Lorem ipsum.</p>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4">
                    <!-- Content -->
                    <div class="service_single_content text-left mb-100 wow fadeInUp" data-wow-delay="0.7s">
                        <!-- Icon -->
                        <div class="service_icon">
                            <img draggable="false" src="<?= base_url('site/img/services/6.png') ?>" alt="">
                        </div>
                        <h6>Crypto Trading Platform</h6>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla neque quam, maximus ut
                            accumsan ut, posuere sit Lorem ipsum.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ##### Our Services Area End ##### -->

    <!-- ##### Our roadmap Area start ##### -->
    <!--<section class="roadmap section-padding-100" style="background: url('img/bg-img/bg-roadmap.png') no-repeat center center;background-size: cover;" id="roadmap">-->

    <!--    <div class="container-fluid">-->
    <!--        <div class="section-heading text-center">-->

    <!--            <div class="dream-dots justify-content-center wow fadeInUp" data-wow-delay="0.2s">-->
    <!--                <span></span><span></span><span></span><span></span><span></span><span></span><span></span>-->
    <!--            </div>-->
    <!--            <h2 class="wow fadeInUp" data-wow-delay="0.3s">Our ICO Roadmap</h2>-->
    <!--            <p class="wow fadeInUp" data-wow-delay="0.4s">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed quis accumsan nisi Ut ut felis congue nisl hendrerit commodo.</p>-->
    <!--        </div>-->
    <!--        <div class="row">-->
    <!--            <div class="section_5-content">-->
    <!--                <div class="section_5-slider-trumb"></div>-->
    <!--                <div id="section_5-slider-circle">-->
    <!--                    <div style="left:10%" class="data_cir cir_0" data-date="Q2 2024">-->
    <!--                        <div class="data_cir-content">-->
    <!--                            <span><span class="roadmap_content_info">Emergence and design of the idea</span><span class="roadmap_img"><img draggable="false" src="<?= base_url('site/img/roadmap-icons/1.svg') ?>" alt=""></span></span>-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                    <div style="left:20%" class="data_cir cir_1" data-date="Q3 2017">-->
    <!--                        <div class="data_cir-content">-->
    <!--                            <span><span class="roadmap_content_info"><ul><li><span>built our team</span></li>-->
    <!--                            <li><span>company establishment</span></li>-->
    <!--                            <li><span>legal issues setting</span></li>-->
    <!--                            <li><span> concept development</span></li>-->
    <!--                            </ul>-->
    <!--                            </span><span class="roadmap_img"><img draggable="false" src="<?= base_url('site/img/roadmap-icons/2.svg') ?>" alt=""></span></span>-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                    <div style="left:30%" class="data_cir cir_2" data-date="Q4 2024">-->
    <!--                        <div class="data_cir-content">-->
    <!--                            <span><span class="roadmap_content_info"><ul><li><span> start development of the ealier version and crypto wallet version</span></li>-->
    <!--                            <li><span>negotiations on partnerships</span></li>-->
    <!--                            </ul>-->
    <!--                            </span><span class="roadmap_img"><img draggable="false" src="<?= base_url('site/img/roadmap-icons/3.svg') ?>" alt=""></span></span>-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                    <div style="left:40%" class="data_cir cir_3" data-date="Q1 2024">-->
    <!--                        <div class="data_cir-content">-->
    <!--                            <span><span class="roadmap_content_info"><ul><li><span>website launch</span></li>-->
    <!--                            <li><span>White Paper published</span></li>-->
    <!--                            <li><span>Private and Pre-sale start</span></li>-->
    <!--                            </ul>-->
    <!--                            </span><span class="roadmap_img"><img draggable="false" src="<?= base_url('site/img/roadmap-icons/4.svg') ?>" alt=""></span></span>-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                    <div style="left:50%" class="data_cir cir_4 red_crc" data-date="Q2 2024">-->
    <!--                        <div class="data_cir-content">-->
    <!--                            <span><span class="roadmap_content_info"><ul><li><span>Crowdsale</span></li>-->
    <!--                            <li><span>marketing campaign</span></li>-->
    <!--                            <li><span>listing to the major exchanges</span></li>-->
    <!--                            </ul>-->
    <!--                            </span><span class="roadmap_img"><img draggable="false" src="<?= base_url('site/img/roadmap-icons/5.svg') ?>" alt=""></span></span>-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                    <div style="left:60%" class="data_cir cir_5 next" data-date="Q3 2024">-->
    <!--                        <div class="data_cir-content">-->
    <!--                            <span><span class="roadmap_content_info"><ul><li><span>Crypto Wallet version release</span></li>-->
    <!--                            <li><span>Testnet launch</span></li>-->
    <!--                            <li><span> Platform ealier version development</span></li>-->
    <!--                            </ul>-->
    <!--                            </span><span class="roadmap_img"><img draggable="false" src="<?= base_url('site/img/roadmap-icons/6.svg') ?>" alt=""></span></span>-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                    <div style="left:70%" class="data_cir cir_6 next" data-date="Q4 2024">-->
    <!--                        <div class="data_cir-content">-->
    <!--                            <span><span class="roadmap_content_info"><ul><li><span>Mobile App</span></li>-->
    <!--                            <li><span>Explorer</span></li>-->
    <!--                            <li><span>new partnerships and new marketplace support</span></li>-->
    <!--                            </ul>-->
    <!--                            </span><span class="roadmap_img"><img draggable="false" src="<?= base_url('site/img/roadmap-icons/7.svg') ?>" alt=""></span></span>-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                    <div style="left:80%" class="data_cir cir_7 next" data-date="Q1 2024">-->
    <!--                        <div class="data_cir-content">-->
    <!--                            <span><span class="roadmap_content_info"><ul><li><span>Mainnet launch</span></li>-->
    <!--                            <li><span>Wallet beta-version release</span></li>-->
    <!--                            <li><span>Masternodes implementation</span></li>-->
    <!--                            </ul>-->
    <!--                            </span><span class="roadmap_img"><img draggable="false" src="<?= base_url('site/img/roadmap-icons/8.svg') ?>" alt=""></span></span>-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                    <div style="left:90%" class="data_cir cir_8 next" data-date="Q2 2024">-->
    <!--                        <div class="data_cir-content">-->
    <!--                            <span><span class="roadmap_content_info"><ul><li><span> Platform integration to electronic marketplaces</span></li>-->
    <!--                            <li><span>Zero Fee</span></li>-->
    <!--                            </ul>-->
    <!--                            </span><span class="roadmap_img"><img draggable="false" src="<?= base_url('site/img/roadmap-icons/9.svg') ?>" alt=""></span></span>-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</section>-->
    <!-- ##### Our roadmap Area End ##### -->


    <!-- ##### Subscribe Area start ##### -->
    <!--<section class="container section-padding-100-0" style="padding-bottom: 0px" id="start">-->
    <!--    <div class="subscribe">-->
    <!--        <div class="row">-->
    <!--            <div class="col-sm-12">-->
    <!--                <div class="subscribe-wrapper">-->

    <!--                    <div class="service-text">-->
    <!--                        <div class="col-lg-4 col-md-4 col-xs-12 text-center">-->
    <!--                            <img src="<?= base_url('site/img/core-img/cash.png') ?>" alt="">-->
    <!--                        </div>-->
    <!--                        <div class="col-lg-8 col-md-8 col-xs-12 text-left">-->
    <!--                            <div class="section-heading text-left">-->
    <!--                                <h2 class="wow fadeInUp" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInUp;">Don’t Miss Our News And Updates!</h2>-->
    <!--                                <p class="wow fadeInUp" data-wow-delay="0.4s" style="visibility: visible; animation-delay: 0.4s; animation-name: fadeInUp;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed quis accumsan nisi Ut ut felis congue nisl hendrerit commodo.</p>-->
    <!--                            </div>-->
    <!--                            <div class="group">-->
    <!--                                <input type="text" name="subject" required="">-->
    <!--                                <span class="highlight"></span>-->
    <!--                                <span class="bar"></span>-->
    <!--                                <label>enter your email</label>-->
    <!--                                <button class="dream-btn"><i class="fa fa-paper-plane-o"></i></button>-->
    <!--                            </div>-->
    <!--                        </div>-->
    <!--                        <div class="col-lg-5 text-left mt-30">-->
    <!--                            <ul class="list-unstyled list-inline social-list">-->
    <!--                                <li><a href="#"><i class="fa fa-facebook-square"></i></a></li>-->
    <!--                                <li><a href="#"><i class="fa fa-twitter-square"></i></a></li>-->
    <!--                                <li><a href="#"><i class="fa fa-github-square"></i></a></li>-->
    <!--                                <li><a href="#"><i class="fa fa-instagram"></i></a></li>-->
    <!--                                <li><a href="#"><i class="fa fa-medium"></i></a></li>-->
    <!--                            </ul>-->
    <!--                        </div>-->

    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->

    <!--        </div>-->
    <!--    </div>  -->
    <!--</section>-->
    <!-- ##### Subscribe Area End ##### -->

    <!-- ##### FAQ & Timeline Area Start ##### -->
    <!--<div class="faq-timeline-area section-padding-100">-->
    <!--    <div class="container">-->
    <!--        <div class="row">-->
    <!--            <div class="col-12 col-lg-7 col-md-12">-->
    <!--                <div class="section-heading">-->

    <!--                    <div class="dream-dots wow fadeInUp" data-wow-delay="0.2s">-->
    <!--                        <span></span><span></span><span></span><span></span><span></span><span></span><span></span>-->
    <!--                    </div>-->
    <!--                    <h2 class="wow fadeInUp" data-wow-delay="0.3s">Frequently Asked Questions</h2>-->
    <!--                    <p class="wow fadeInUp" data-wow-delay="0.4s" style="margin-left:0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed quis accumsan nisi Ut ut felis congue nisl hendrerit commodo.</p>-->
    <!--                </div>-->

    <!--                <div class="dream-faq-area">-->
    <!--                    <dl>-->
    <!-- Single FAQ Area -->
    <!--                        <dt class="wave wow fadeInUp" data-wow-delay="0.2s">What are the objectives of this token?</dt>-->
    <!--                        <dd class="wow fadeInUp" data-wow-delay="0.3s">-->
    <!--                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolore omnis quaerat nostrum, pariatur ipsam sunt accusamus enim necessitatibus est fugiat, assumenda dolorem, deleniti corrupti cupiditate ipsum, dolorum voluptatum esse error?</p>-->
    <!--                        </dd>-->
    <!-- Single FAQ Area -->
    <!--                        <dt class="wave wow fadeInUp" data-wow-delay="0.3s">What is Token Sale and pre-sale?</dt>-->
    <!--                        <dd>-->
    <!--                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolore omnis quaerat nostrum, pariatur ipsam sunt accusamus enim necessitatibus est fugiat, assumenda dolorem, deleniti corrupti cupiditate ipsum, dolorum voluptatum esse error?</p>-->
    <!--                        </dd>-->
    <!-- Single FAQ Area -->
    <!--                        <dt class="wave wow fadeInUp" data-wow-delay="0.4s">What is the pre-sale start date?</dt>-->
    <!--                        <dd>-->
    <!--                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolore omnis quaerat nostrum, pariatur ipsam sunt accusamus enim necessitatibus est fugiat, assumenda dolorem, deleniti corrupti cupiditate ipsum, dolorum voluptatum esse error?</p>-->
    <!--                        </dd>-->
    <!-- Single FAQ Area -->
    <!--                        <dt class="wave wow fadeInUp" data-wow-delay="0.5s">how may I take part in pre-sale?</dt>-->
    <!--                        <dd>-->
    <!--                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolore omnis quaerat nostrum, pariatur ipsam sunt accusamus enim necessitatibus est fugiat, assumenda dolorem, deleniti corrupti cupiditate ipsum, dolorum voluptatum esse error?</p>-->
    <!--                        </dd>-->
    <!--                    </dl>-->
    <!--                </div>-->
    <!--            </div>-->

    <!--            <div class="col-12 col-lg-5 offset-lg-0 col-md-8 offset-md-2 col-sm-10 offset-sm-1">-->

    <!--                <div class="ico-counter">-->
    <!--                    <div class="counter-down">-->

    <!--                        <div class="content">-->
    <!--                            <div class="conuter-header">-->
    <!--                                <h3 class="text-center">TOKEN SALE ENDS IN</h3>-->
    <!--                            </div>-->
    <!--                            <div class="counterdown-content">-->

    <!-- <div class="ico-sales-status mb-15 o-hidden">
    <!--                                    <div class="pull-left">-->
    <!--                                        <h3 class="dollar-earning">$7,575,357</h3>-->
    <!--                                        <p style="padding-left:10px">Worth of DREAM tokens</p>-->
    <!--                                    </div>-->
    <!--                                    <div class="pull-right">-->
    <!--                                        <h3 class="btc-earning">9.764 <span>BTC</span></h3>-->
    <!--                                        <p>BTC Raised</p>-->
    <!--                                    </div>-->
    <!--                                </div> -->-->
    <!-- Countdown  -->
    <!--                                <div class="count-down titled circled text-center">-->
    <!--                                    <div class="simple_timer"></div>-->
    <!--                                </div>-->
    <!--                                <div class="ico-progress">-->
    <!--                                   <ul class="list-unstyled list-inline clearfix mb-10">-->
    <!--                                      <li class="title">33m</li>-->
    <!--                                      <li class="strength">75m</li>-->
    <!--                                   </ul>-->
    <!-- skill strength -->
    <!--                                   <div class="current-progress">-->
    <!--                                      <div class="progress-bar has-gradient" style="width: 75%"></div>-->
    <!--                                   </div>-->
    <!--                                   <span class="pull-left">Softcap in 103 days</span>-->
    <!--                                   <span class="pull-right">Token Hardcap</span>-->
    <!--                                </div>-->
    <!--                                <div class="text-center">-->
    <!--                                    <a href="#" class="button mt-30">Buy More Tokens</a>-->
    <!--                                </div>-->
    <!--                            </div>-->

    <!--                        </div>-->

    <!--                    </div>-->
    <!--                </div>-->

    <!--                <div class="ico-docs">-->
    <!--                    <div class="row">-->
    <!--                        <div class="col-md-6 col-sm-6 col-xs-12">-->
    <!--                            <div class="doc-element">-->
    <!--                                <a class="document-entry" href="#" target="_blank"><span class="title">WhitePaper</span></a>-->
    <!--                            </div>-->
    <!--                        </div>-->
    <!--                        <div class="col-md-6 col-sm-6 col-xs-12">-->
    <!--                            <div class="doc-element">-->
    <!--                                <a class="document-entry" href="#" target="_blank"><span class="title">OnePager</span></a>-->
    <!--                            </div>-->
    <!--                        </div>-->
    <!--                        <div class="col-md-6 col-sm-6 col-xs-12">-->
    <!--                            <div class="doc-element">-->
    <!--                                <a class="document-entry" href="#" target="_blank"><span class="title">Rating Review</span></a>-->
    <!--                            </div>-->
    <!--                        </div>-->
    <!--                        <div class="col-md-6 col-sm-6 col-xs-12">-->
    <!--                            <div class="doc-element">-->
    <!--                                <a class="document-entry" href="#" target="_blank"><span class="title">Marketing Plan</span></a>-->
    <!--                            </div>-->
    <!--                        </div>-->

    <!--                    </div>-->
    <!--                </div>-->

    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->
    <!-- ##### FAQ & Timeline Area End ##### -->

    <!-- ##### token-distribution Area start ##### -->
    <!--<section class="token-distribution">-->
    <!--    <div class="container">-->

    <!--        <div class="section-heading text-center">-->

    <!--            <div class="dream-dots justify-content-center wow fadeInUp" data-wow-delay="0.2s">-->
    <!--                <span></span><span></span><span></span><span></span><span></span><span></span><span></span>-->
    <!--            </div>-->
    <!--            <h2 class="wow fadeInUp" data-wow-delay="0.3s">Our ICO Distribution</h2>-->
    <!--            <p class="wow fadeInUp" data-wow-delay="0.4s">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed quis accumsan nisi Ut ut felis congue nisl hendrerit commodo.</p>-->
    <!--        </div>-->

    <!--        <div class="col-lg-6 col-md-6 col-sm-12">-->
    <!--            <h2 class="text-center mb-30">Token Allocation</h2>-->
    <!--            <div class="token-allocation">-->
    <!--                <img draggable="false" src="<?= base_url('site/img/core-img/allocation.png') ?>" class="center-block" alt="">-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <div class="col-lg-6 col-md-6 col-sm-12">-->
    <!--            <div class="token-info-wapper"></div>-->
    <!--            <h2 class="text-center mb-30">More Token Info</h2>-->
    <!--            <div class="token-info">-->
    <!--                <div class="info-wrapper">-->
    <!--                    <div class="token-icon" style="background-image: url('img/svg/token-icon-1.svg');"></div>-->
    <!--                    <div class="token-descr">Lorem ipsum dolor sit amet, conse ctetur elit</div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--            <div class="token-info">-->
    <!--                <div class="info-wrapper">-->
    <!--                    <div class="token-icon" style="background-image: url('img/svg/token-icon-2.svg');"></div>-->
    <!--                    <div class="token-descr">Sed quis accumsan nisi Ut ut felis</div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--            <div class="token-info">-->
    <!--                <div class="info-wrapper">-->
    <!--                    <div class="token-icon" style="background-image: url('img/svg/token-icon-3.svg');"></div>-->
    <!--                    <div class="token-descr">felis congue nisl hendrerit commodo</div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--            <div class="token-info" style="margin-bottom:0">-->
    <!--                <div class="info-wrapper">-->
    <!--                    <div class="token-icon" style="background-image: url('img/svg/token-icon-4.svg');"></div>-->
    <!--                    <div class="token-descr">arch nemo sequi rem saepe ad quasi ullam.</div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->

    <!--</section>-->
    <!-- ##### token-distribution Area End ##### -->


    <!-- ##### Team Area Start ##### -->
    <section class="our_team_area section-padding-0-0 clearfix" id="team">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-heading text-center">

                        <div class="dream-dots justify-content-center wow fadeInUp" data-wow-delay="0.2s">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <h2 class="wow fadeInUp" data-wow-delay="0.3s">Awesome Team</h2>
                        <p class="wow fadeInUp" data-wow-delay="0.4s">Lorem ipsum dolor sit amet, consectetur adipiscing
                            elit. Sed quis accumsan nisi Ut ut felis congue nisl hendrerit commodo. </p>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Single Team Member -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="single-team-member wow fadeInUp" data-wow-delay="0.2s">
                        <!-- Image -->
                        <div class="team-member-thumb">
                            <img draggable="false" src="<?= base_url('site/img/team-img/1.png') ?>" class="center-block"
                                alt="">
                        </div>
                        <!-- Team Info -->
                        <div class="team-info">
                            <h5>Sunny khan</h5>
                            <p>Executive Officer</p>
                        </div>
                        <!-- Social Icon -->
                        <div class="team-social-icon">
                            <a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="#"><i class="fa fa-instagram"></i></a>
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-skype"></i></a>
                        </div>
                    </div>
                </div>


                <!-- Single Team Member -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="single-team-member wow fadeInUp" data-wow-delay="0.3s">
                        <!-- Image -->
                        <div class="team-member-thumb">
                            <img draggable="false" src="<?= base_url('site/img/team-img/2.png') ?>" class="center-block"
                                alt="">
                        </div>
                        <!-- Team Info -->
                        <div class="team-info">
                            <h5>Ajoy Das</h5>
                            <p>Business Development</p>
                        </div>
                        <!-- Social Icon -->
                        <div class="team-social-icon">
                            <a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="#"><i class="fa fa-instagram"></i></a>
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-skype"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Single Team Member -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="single-team-member wow fadeInUp" data-wow-delay="0.4s">
                        <!-- Image -->
                        <div class="team-member-thumb">
                            <img draggable="false" src="<?= base_url('site/img/team-img/3.png') ?>" class="center-block"
                                alt="">
                        </div>
                        <!-- Team Info -->
                        <div class="team-info">
                            <h5>Afroza Mou</h5>
                            <p>UX/UI Designer</p>
                        </div>
                        <!-- Social Icon -->
                        <div class="team-social-icon">
                            <a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="#"><i class="fa fa-instagram"></i></a>
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-skype"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Single Team Member -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="single-team-member wow fadeInUp" data-wow-delay="0.5s">
                        <!-- Image -->
                        <div class="team-member-thumb">
                            <img draggable="false" src="<?= base_url('site/img/team-img/4.png') ?>" class="center-block"
                                alt="">
                        </div>
                        <!-- Team Info -->
                        <div class="team-info">
                            <h5>Lim Sarah</h5>
                            <p>Head of Marketing</p>
                        </div>
                        <!-- Icon -->
                        <div class="team-social-icon">
                            <a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="#"><i class="fa fa-instagram"></i></a>
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-skype"></i></a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- ##### Team Area End ##### -->


    <!-- ##### Footer Area Start ##### -->
    <footer class="footer-area bg-img" style="background-image: url(<?= base_url('site/img/core-img/pattern.png') ?>);">

        <!-- ##### Contact Area Start ##### -->
        <div class="contact_us_area" id="contact">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="section-heading text-center">

                            <div class="dream-dots justify-content-center wow fadeInUp" data-wow-delay="0.2s">
                                <span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                            </div>
                            <h2 class="wow fadeInUp" data-wow-delay="0.3s">Contact With Us</h2>
                            <p class="wow fadeInUp" data-wow-delay="0.4s">Lorem ipsum dolor sit amet, consectetur
                                adipiscing elit. Sed quis accumsan nisi Ut ut felis congue nisl hendrerit commodo.</p>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="row justify-content-center">
                    <div class="col-12 col-md-10 col-lg-8">
                        <div class="contact_form">
                            <form action="#" method="post" id="main_contact_form" novalidate>
                                <div class="row">
                                    <div class="col-12">
                                        <div id="success_fail_info"></div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="group wow fadeInUp" data-wow-delay="0.2s">
                                            <input type="text" name="name" id="name" required>
                                            <span class="highlight"></span>
                                            <span class="bar"></span>
                                            <label>Name</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="group wow fadeInUp" data-wow-delay="0.3s">
                                            <input type="text" name="email" id="email" required>
                                            <span class="highlight"></span>
                                            <span class="bar"></span>
                                            <label>Email</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="group wow fadeInUp" data-wow-delay="0.4s">
                                            <input type="text" name="subject" id="subject" required>
                                            <span class="highlight"></span>
                                            <span class="bar"></span>
                                            <label>Subject</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="group wow fadeInUp" data-wow-delay="0.5s">
                                            <textarea name="message" id="message" required></textarea>
                                            <span class="highlight"></span>
                                            <span class="bar"></span>
                                            <label>Message</label>
                                        </div>
                                    </div>
                                    <div class="col-12 text-center wow fadeInUp" data-wow-delay="0.6s">
                                        <button type="submit" class="btn dream-btn">Send Message</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ##### Contact Area End ##### -->

        <div class="footer-content-area " style="background: url(<?= base_url('site/img/bg-img/bg-2.png') ?>) center;">
            <div class="container">
                <div class="row align-items-end">
                    <div class="col-12 col-md-5">
                        <div class="footer-copywrite-info">
                            <!-- Copywrite -->
                            <div class="copywrite_text wow fadeInUp" data-wow-delay="0.2s">
                                <div class="footer-logo">
                                    <a href="#"><img draggable="false"
                                            src="<?= base_url('site/img/core-img/logo.png') ?>" alt="logo"> Trade
                                        Club.</a>
                                </div>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Velit ducimus voluptatibus
                                    neque illo id repellat quisquam? Autem expedita earum quae laborum ipsum ad, a eaque
                                    officiis eligendi blanditiis odio necessitatibus.</p>
                            </div>
                            <!-- Social Icon -->
                            <!--<div class="footer-social-info wow fadeInUp" data-wow-delay="0.4s">-->
                            <!--    <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>-->
                            <!--    <a href="#"> <i class="fa fa-twitter" aria-hidden="true"></i></a>-->
                            <!--    <a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a>-->
                            <!--    <a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a>-->
                            <!--    <a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a>-->
                            <!--</div>-->
                        </div>
                    </div>
                    <div class="col-12 col-md-7">
                        <!-- Content Info -->
                        <div class="contact_info_area d-sm-flex justify-content-between">
                            <div class="contact_info text-center wow fadeInUp" data-wow-delay="0.2s">
                                <h5>NAVIGATE</h5>
                                <a href="#">
                                    <p>Advertisers</p>
                                </a>
                                <a href="#">
                                    <p>Developers</p>
                                </a>
                                <a href="#">
                                    <p>Resources</p>
                                </a>
                                <a href="#">
                                    <p>Company</p>
                                </a>
                                <a href="#">
                                    <p>Connect</p>
                                </a>
                            </div>
                            <!-- Content Info -->
                            <div class="contact_info text-center wow fadeInUp" data-wow-delay="0.3s">
                                <h5>PRIVACY & TOS</h5>
                                <a href="#">
                                    <p>Advertiser Agreement</p>
                                </a>
                                <a href="#">
                                    <p>Acceptable Use Policy</p>
                                </a>
                                <a href="#">
                                    <p>Privacy Policy</p>
                                </a>
                                <a href="#">
                                    <p>Technology Privacy</p>
                                </a>
                                <a href="#">
                                    <p>Developer Agreement</p>
                                </a>
                            </div>
                            <!-- Content Info -->
                            <div class="contact_info text-center wow fadeInUp" data-wow-delay="0.4s">
                                <h5>Phone</h5>
                                <p>Mailing Address:xx00 E. UK</p>
                                <p>1145 Street, UK</p>
                                <p>info@traderclub.biz</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- ##### Footer Area End ##### -->

    <!-- ########## All JS ########## -->
    <!-- jQuery js -->
    <script src="<?= base_url('site/js/jquery.min.js') ?> "></script>
    <!-- Popper js -->
    <script src="<?= base_url('site/js/popper.min.js') ?> "></script>
    <!-- Bootstrap js -->
    <script src="<?= base_url('site/js/bootstrap.min.js') ?> "></script>
    <!-- All Plugins js -->
    <script src="<?= base_url('site/js/plugins.js') ?> "></script>
    <!-- Parallax js -->
    <script src="<?= base_url('site/js/dzsparallaxer.js') ?> "></script>

    <script src="<?= base_url('site/js/jquery.syotimer.min.js') ?> "></script>

    <!-- Active js -->
    <script src="<?= base_url('site/js/script.js') ?> "></script>

</body>


</html>