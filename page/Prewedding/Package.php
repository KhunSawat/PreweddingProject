<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>บริการแพ็คเกจ</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="slidshow.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link
        rel="icon"
        href="../../assets/img/iconPage.png"
        type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="../../assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["../../assets/css/fonts.min.css"],
            },
            active: function() {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../assets/css/plugins.min.css" />
    <link rel="stylesheet" href="../../assets/css/kaiadmin.min.css" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="../../assets/css/demo.css" />
</head>

<body>
    <?php include('./components/navbar.php') ?>

    <header class="header">
        <h1> <b>PACKAGE</b> </h1>
    </header>
    <div class="row justify-content-center align-items-center mb-5 mt-5">
        <div class="col-md-3 ps-md-0">
            <div class="card-pricing2 card-success">
                <div class="pricing-header">
                    <h3 class="fw-bold mb-3">Standard</h3>
                    <span class="sub-title">บริการเริ่มต้น</span>
                </div>
                <div class="price-value">
                    <div class="value">
                        <span class="amount">7,900</span>
                    </div>
                </div>
                <ul class="pricing-content">
                    <li>ชุดสูทชุดราตรี</li>
                    <li>ถ่ายภาพ 1 ชั่วโมง</li>
                    <li>แต่งหน้าทำผม</li>
                    <li>รีทัชพิเศษ 20 ภาพ</li>
                    <li class="disable">เตรียมชุดมาเอง 1 เซท</li>
                    <li class="disable">กรอบรูป</li>
                    <li class="disable">ชุดไทยพรีเมี่ยม</li>
                </ul>
            </div>
        </div>
        <div class="col-md-3 ps-md-0 pe-md-0">
            <div class="card-pricing2 card-primary">
                <div class="pricing-header">
                    <h3 class="fw-bold mb-3">Premium</h3>
                    <span class="sub-title">บริการคุณภาพ</span>
                </div>
                <div class="price-value">
                    <div class="value">
                        <span class="amount">9,900</span>
                    </div>
                </div>
                <ul class="pricing-content">
                    <li>ชุดสูทชุดราตรี</li>
                    <li>ถ่ายภาพ 2 ชั่วโมง</li>
                    <li>แต่งหน้าทำผม</li>
                    <li>รีทัชพิเศษ 30 ภาพ</li>
                    <li>เตรียมชุดมาเอง 1 เซท</li>
                    <li class="disable">กรอบรูป</li>
                    <li class="disable">ชุดไทยพรีเมี่ยม</li>
                </ul>
            </div>
        </div>
        <div class="col-md-3 pe-md-0">
            <div class="card-pricing2 card-secondary">
                <div class="pricing-header">
                    <h3 class="fw-bold mb-3">Exclusive</h3>
                    <span class="sub-title">บริการพิเศษ</span>
                </div>
                <div class="price-value">
                    <div class="value">
                        <span class="amount">13,900</span>
                    </div>
                </div>
                <ul class="pricing-content">
                    <li>ชุดสูทชุดราตรี</li>
                    <li>ถ่ายภาพ 2 ชั่วโมง</li>
                    <li>แต่งหน้าทำผม</li>
                    <li>รีทัชพิเศษ 35 ภาพ</li>
                    <li>เตรียมชุดมาเอง 1 เซท</li>
                    <li>กรอบรูป</li>
                    <li>ชุดไทยพรีเมี่ยม</li>
                </ul>
            </div>
        </div>
    </div>
    <section class="hero">
        <div class="container">
            <div class="image-container">
                <div class="hero-img">
                    <a href="https://www.facebook.com/profile.php?id=100082993164420">
                        <img class="hover-image" src=".png/StandardPackage.jpg" alt="">
                    </a>
                </div>
            </div>
            <div class="image-container">
                <div class="hero-img">
                    <a href="https://www.facebook.com/profile.php?id=100082993164420">
                        <img class="hover-image" src=".png/PremiumPackage.jpg" alt="">
                    </a>
                </div>
            </div>
            <div class="image-container">
                <div class="hero-img">
                    <a href="https://www.facebook.com/profile.php?id=100082993164420">
                        <img class="hover-image" src=".png/ExclusivePackage.jpg" alt="">
                    </a>
                </div>
            </div>
        </div>
    </section>
    <?php include "./components/footer.php" ?>
    <!--   Core JS Files   -->
    <script src="../../assets/js/core/jquery-3.7.1.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="../../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script src="../../assets/js/core/popper.min.js"></script>
    <script src="../../assets/js/core/bootstrap.min.js"></script>

    <!-- Chart JS -->
    <script src="../../assets/js/plugin/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="../../assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="../../assets/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="../../assets/js/plugin/datatables/datatables.min.js"></script>

    <!-- Bootstrap Notify -->
    <!-- <script src="../../assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script> -->

    <!-- jQuery Vector Maps -->
    <script src="../../assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
    <script src="../../assets/js/plugin/jsvectormap/world.js"></script>

    <!-- Sweet Alert -->
    <script src="../../assets/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Kaiadmin JS -->
    <script src="../../assets/js/kaiadmin.min.js"></script>

    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="../../assets/js/setting-demo.js"></script>
    <script src="../../assets/js/demo.js"></script>
</body>

</html>