<!DOCTYPE html>
<html lang="vi">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{ DataSite('description') }}" />
    <meta name="keywords" content="{{ DataSite('keyword') }}" />
    <meta name="title" content="{{ DataSite('title') }}" />
    <meta property="og:image" content="{{ DataSite('image_seo') }}" />
    <meta name="robots" content="index, follow" />
    <meta name="author" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="2_pWvgGmPUUiGP6_YG9WKCfVpNtDXLpJ609ZTnpDJOs" />
    <!-- Title -->
    <title>{{ getDomain() }} - {{ DataSite('title') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="/landing/img/core-img/favicon.ico">

    <!-- Core Stylesheet -->
    <link rel="stylesheet" href="/landing/css/style.css">

    <!-- Responsive Stylesheet -->
    <link rel="stylesheet" href="/landing/css/responsive.css">

    <style>
        @font-face {
            font-family: 'Itim';
            src: url('/path/to/your/fonts/itim-regular.ttf') format('truetype');
            /* Add other font-face declarations if you have different font weights or styles */
        }
        
        /* Apply the Itim font to all elements on the page */
        body {
            font-family: 'Itim', sans-serif;
        }

        p, h1, h2, h3, h4, h5, h6 {
            font-family: 'Itim', sans-serif;
        }
    </style>

</head>

<body class="light-version">
    <!-- Preloader -->
    <div id="preloader">
        <div class="preload-content">
            <div id="dream-load"></div>
        </div>
    </div>

    <!-- ##### Header Area Start ##### -->
    <header class="header-area fadeInDown" data-wow-delay="0.2s">
        <div class="classy-nav-container light breakpoint-off">
            <div class="container">
                <!-- Classy Menu -->
                <nav class="classy-navbar justify-content-between" id="dreamNav">

                    <!-- Logo -->
                    <a class="nav-brand light" href="#"><img src="{{ DataSite('logo') }}" width="100" alt="logo"></a>

                    <!-- Navbar Toggler -->
                    <div class="classy-navbar-toggler demo">
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
                                <li><a href="{{ route('home') }}">Trang chủ</a></li>
                            </ul>

                            <!-- Button -->
                            <a href="{{ route('login') }}" class="btn login-btn ml-50">Đăng nhập</a>
                        </div>
                        <!-- Nav End -->
                    </div>
                </nav>
            </div>
        </div>
    </header>
    <!-- ##### Header Area End ##### -->

    <!-- ##### Welcome Area Start ##### -->
    <section class="welcome_area clearfix dzsparallaxer auto-init none fullwidth" data-options='{direction: "normal"}'
        id="home">
        <div class="divimage dzsparallaxer--target"
            style="width: 101%; height: 130%; background-image: url(/landing/img/bg-img/bg-5.png)"></div>

        <!-- Hero Content -->
        <div class="hero-content transparent">

            <div class="container h-100">
                <div class="row h-100 align-items-center">
                    <!-- Welcome Content -->
                    <div class="col-12 col-lg-6 col-md-12">
                        <div class="welcome-content">
                            <h1 class="fadeInUp fw-bold" data-wow-delay="0.2s">Website dịch vụ mạng xã hội tốt nhất</h1>
                            <p class="w-text fadeInUp" data-wow-delay="0.3s">Hệ thống chuyên cung cấp các tương tác Mạng xã hội như Facebook, Instagram, Tiktok...</p>
                            <div class="dream-btn-group fadeInUp" data-wow-delay="0.4s">
                                <a href="{{ route('login') }}" class="btn dream-btn mr-3">Bắt đầu ngay</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- ##### Welcome Area End ##### -->



    <section class="demo-video features section-padding-100"
        style="background:url('/landing/img/svg/bg_hero.svg') no-repeat center right">
        <div class="container">
            <div class="section-heading text-center">
                <!-- Dream Dots -->
                <h2 class="b-text fadeInUp" data-wow-delay="0.3s">Tại sao chọn dịch vụ của chúng tôi?</h2>
                <p class="fadeInUp" data-wow-delay="0.4s" style="color:#888">Chúng tôi cung cấp dịch vụ giúp tài khoản Mạng xã hội của bạn phát triển</p>
            </div>
            <!-- Welcome Video Area -->
            <div class="col-lg-6 col-md-12  col-sm-12">
                <div class="welcome-video-area fadeInUp" data-wow-delay="0.5s">
                    <!-- Welcome Thumbnail -->
                    <div class="welcome-thumb">
                        <img src="/assets/images/marketing/Gotosub1.jpg" alt="">
                    </div>
                    <!-- Video Icon -->
                    <div class="video-icon">
                        <a href="" class="btn dream-btn video-btn"
                            id="videobtn"><i class="fa fa-play"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12">
                <div class="services-block-four mt-s">
                    <div class="inner-box">
                        <div class="icon-img-box">
                            <img src="/assets/images/badge.png" width="50" alt="">
                        </div>
                        <h3><a href="#">Chất lượng ấn tượng</a></h3>
                        <div class="text">Bạn sẽ cảm thấy ấn tượng với chất lượng dịch vụ mà chúng tôi cung cấp.</div>

                    </div>
                </div>
                <div class="services-block-four">
                    <div class="inner-box">
                        <div class="icon-img-box">
                            <img src="/assets/images/famous.png" width="50" alt="">
                        </div>
                        <h3><a href="#">Tạo sự nổi tiếng trên mạng xã hội</a></h3>
                        <div class="text">Khiến bạn trở nên nổi bật hơn, thu hút hơn và phổ biến hơn trên các trang mạng xã hội</div>

                    </div>
                </div>
                <div class="services-block-four" style="margin-bottom:0">
                    <div class="inner-box">
                        <div class="icon-img-box">
                            <img src="/assets/images/diversity.png" width="50" alt="">
                        </div>
                        <h3><a href="#">Đa Dạng Dịch Vụ</a></h3>
                        <div class="text">Hệ thống đa dạng dịch vụ từ dịch vụ mạng xã hội , dịch vụ website đến dịch vụ tài khoản ,...
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>


    <div class="clearfix"></div>
    

    <!-- ##### FAQ & Timeline Area Start ##### -->
    <div class="faq-timeline-area section-padding-100">
        <div class="container">
            <div class="section-heading text-center">
                <!-- Dream Dots -->
                <h2 class="fadeInUp" data-wow-delay="0.3s">Top những câu hỏi được hỏi nhiều nhất</h2>
                <p class="fadeInUp" data-wow-delay="0.4s">Chúng tôi đã trả lời những câu hỏi thường gặp nhất từ ​những khách hàng của chúng tôi</p>
            </div>
            <div class="row">
                <div class="col-12 col-lg-6 col-md-12">


                    <div class="dream-faq-area">
                        <dl style="margin-bottom:0">
                            <!-- Single FAQ Area -->
                            <dt class="wave fadeInUp" data-wow-delay="0.2s">Tôi có thể nạp tiền qua những phương thức nào?
                            </dt>
                            <dd class="fadeInUp" data-wow-delay="0.3s">
                                <p>Trên web hỗ trợ phương thức nạp tiền qua ví điện tử Momo, Chuyển khoản ngân hàng, nạp bằng thẻ cào , Thẻ Siêu Rẻ và các phương thức nạp tiền khác, Hãy nạp tiền thông qua phương thức thanh toán mà bạn thích nhất.</p>
                            </dd>
                            <!-- Single FAQ Area -->
                            <dt class="wave fadeInUp" data-wow-delay="0.3s">Tôi có thể tìm thấy những loại dịch vụ nào?</dt>
                            <dd>
                                <p>Chúng tôi cung cấp các dịch vụ Mạng Xã Hội , Dịch vụ mua bán tài khoản , dịch vụ website và các dịch vụ liên quan khác</p>
                            </dd>
                            <!-- Single FAQ Area -->
                            <dt class="wave fadeInUp" data-wow-delay="0.4s">Tôi có bị khóa tài khoản nếu mua dịch vụ MXH không?</dt>
                            <dd>
                                <p>Dịch vụ chúng tôi cung cấp là 100% an toàn! Tài khoản của bạn sẽ không bị khóa.</p>
                            </dd>
                            <!-- Single FAQ Area -->
                            <dt class="wave fadeInUp" data-wow-delay="0.4s">Tôi có thể nhận hỗ trợ ở đâu?
                            </dt>
                            <dd>
                                <p>Bạn có thể nhận hỗ trợ tại zalo , telegram hoặc facebook </p>
                            </dd>
                        </dl>
                    </div>
                </div>

                <div class="col-12 col-lg-6 offset-lg-0 col-md-8 offset-md-2 col-sm-10 offset-sm-1">
                    <img src="/landing/img/core-img/faq1.png" alt="" class="center-block img-responsive">
                </div>
            </div>
        </div>
    </div>
    <!-- ##### FAQ & Timeline Area End ##### -->

    <!-- ##### Footer Area Start ##### -->
    <footer class="footer-area bg-img" style="background-image: url(/landing/img/core-img/pattern.png);">

        <div class="footer-content-area ">
            <div class="container">
                <div class="row ">
                    <div class="col-12 col-lg-4 col-md-6">
                        <div class="footer-copywrite-info">
                            <!-- Copywrite -->
                            <div class="copywrite_text fadeInUp" data-wow-delay="0.2s">
                                <div class="footer-logo">
                                    <a href="#"><img src="/landing/img/core-img/logo.png" alt="logo"> {{ getDomain() }}
                                    </a>
                                </div>
                                <p>Cảm ơn quý khách đã ghé thăm Website của chúng tôi, nếu có thắc mắc vui lòng liên hệ ngay để được giải đáp, chúc Quý Khách một ngày tốt lành. Xin Cảm ơn!</p>
                            </div>
                            <!-- Social Icon -->
                            <div class="footer-social-info fadeInUp" data-wow-delay="0.4s">
                                <a href="{{ DataSite('facebook') }}"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-3 col-md-6">
                        <div class="contact_info_area d-sm-flex justify-content-between">
                            <!-- Content Info -->
                            <div class="contact_info mt-x text-center fadeInUp" data-wow-delay="0.3s">
                                <h5>Our links</h5>
                                <a href="#">
                                    <p>Home</p>
                                </a>
                                <a href="#">
                                    <p>About</p>
                                </a>
                                <a href="#">
                                    <p>Support</p>
                                </a>
                            </div>
                        </div>
                    </div>


                    <div class="col-12 col-lg-3 col-md-6 ">
                        <div class="contact_info_area d-sm-flex justify-content-between">
                            <!-- Content Info -->
                            <div class="contact_info mt-s text-center fadeInUp" data-wow-delay="0.4s">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
<!-- Messenger Plugin chat Code -->
    <div id="fb-root"></div>

    <!-- Your Plugin chat code -->
    <div id="fb-customer-chat" class="fb-customerchat">
    </div>

    <script>
      var chatbox = document.getElementById('fb-customer-chat');
      chatbox.setAttribute("page_id", "115587334831139");
      chatbox.setAttribute("attribution", "biz_inbox");
    </script>

    <!-- Your SDK code -->
    <script>
      window.fbAsyncInit = function() {
        FB.init({
          xfbml            : true,
          version          : 'v17.0'
        });
      };

      (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/vi_VN/sdk/xfbml.customerchat.js';
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));
    </script>
    </footer>
    <!-- ##### Footer Area End ##### -->

    <!-- ########## All JS ########## -->
    <!-- jQuery js -->
    <script src="/landing/js/jquery.min.js"></script>
    <!-- Popper js -->
    <script src="/landing/js/popper.min.js"></script>
    <!-- Bootstrap js -->
    <script src="/landing/js/bootstrap.min.js"></script>
    <!-- All Plugins js -->
    <script src="/landing/js/plugins.js"></script>
    <!-- Parallax js -->
    <script src="/landing/js/dzsparallaxer.js"></script>

    <script src="/landing/js/jquery.syotimer.min.js"></script>

    <!-- script js -->
    <script src="/landing/js/script.js"></script>

</body>


<!-- Mirrored from cryptorica-ico.netlify.app/index-demo-3.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 13 Jun 2023 22:44:32 GMT -->

</html>
