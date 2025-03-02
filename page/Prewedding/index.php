<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Kawaii Wedding Studio</title>
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

<body>

    </head>

    <body>
        <?php include('./components/navbar.php') ?>
        <br>

        <!-- Slideshow container -->
        <div class="slideshow-container">

            <!-- Full-width images with number and caption text -->
            <div class="mySlides fade">
                <div class="numbertext"></div>
                <img src=".png/img1.jpg" style="width:100%">
                <div class="text"></div>
            </div>

            <div class="mySlides fade">
                <div class="numbertext"></div>
                <img src=".png/img2.jpg" style="width:100%">
                <div class="text"></div>
            </div>

            <div class="mySlides fade">
                <div class="numbertext"></div>
                <img src=".png/img3.jpg" style="width:100%">
                <div class="text"></div>
            </div>

            <!-- Next and previous buttons -->
            <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
            <a class="next" onclick="plusSlides(1)">&#10095;</a>

        </div>
        <br>

        <!-- The dots/circles -->
        <div style="text-align:center">
            <span class="dot" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
            <span class="dot" onclick="currentSlide(3)"></span>
        </div>
        <script src="slidshow.js"></script>
        <br>
        <section class="hero">
            <div class="container">
                <div class="hero-con">
                    <div class="hero-info">
                        <h3>เกี่ยวกับเรา</h3>
                        <p>Kawaii Wedding Studio : ร้านเช่าชุดแต่งงาน ชุดไทย ชุดเจ้าสาว ถ่ายพรีเว้ดดิ้ง ตั้งอยู่เลขที่
                            205/1 ซอยจินตคาม ถนนทหาร ตำบลหมากแข้ง อำเภอเมือง จังหวัดอุดรธานี</p>
                        <a href="#" class="hero-btn">SEE MORE</a>
                    </div>

                    <div class="hero-img">
                        <img src=".png/shop.jpg" alt="">
                    </div>
                </div>
            </div>
        </section>
        <section class="hero">
            <div class="container">
                <div class="hero-con">
                    <div class="hero-img">
                        <img src=".png/Package.png" alt="">
                    </div>
                    <div class="hero-info">
                        <h3>Package</h3>
                        <p>Kawaii Wedding Studio มีการบริการในการถ่ายพรีเว้ดดิ้งภายใน Studio หรือภายนอกสถานที่
                            ลูกค้าสามารถเลือก Concept && Location ได้ มีให้เลือกเป็น Package ต่างๆ และในกรณีนอกสถานที่
                            มีค่าสถานที่ ค่าเดินทางและค่าอาหาร ลูกค้าเป็นคนชำระทั้งหมด</p>
                        <a href="Package.html" class="hero-btn">SEE MORE</a>
                    </div>
                </div>
            </div>
        </section>
        <section class="hero">
            <div class="container">
                <div class="hero-con-dress">
                    <div class="hero-info">
                        <h3>Dress</h3>
                        <p>สนใจ เช่าชุดแต่งงาน เช่าชุดเจ้าสาว ชุดราตรีเจ้าสาว ชุดแต่งงานสั้น ชุดหมั้นสั้น มีทุกแนวเช่น เช่าชุดแต่งงาน มินิมอล เรียบหรู แนวเกาหลี วินเทจ แขนสั้น แขนยาว ชุดเจ้าสาวอวบไซส์ ใหญ่ พลัสไซส์ ชุดถ่ายพรีเวดดิ้ง สวยๆมากมาย แนะนำโปรโมชั่น 4 ชุดวันจริง เจ้าบ่าว เจ้าสาว ( เช่าชุดเจ้าสาว + เช่าชุดไทย ) แค่ 13,900บ. คุ้ม ถูก ประหยัด คุมงบได้.</p>
                        <a href="Dress.html" class="hero-btn">SEE MORE</a>
                    </div>

                    <div class="hero-img">
                        <img src=".png/Dress.png" alt="">
                    </div>
                </div>
            </div>
        </section>
        <section class="hero">
            <div class="container">
                <div class="hero-con-dress">
                    <div class="hero-info">
                        <h3 class="text-center mb-5">
                            <span class="badge badge-primary px-5 py-3" style="background: #ea586b; color: white; font-size: 1.2rem;">คิวงาน</span>
                        </h3>
                        <?php include('../../components/calendar.php'); ?>
                    </div>
                </div>
            </div>
        </section>
<?php include "./components/footer.php" ?>
    </body>
    <script src="slidshow.js"></script>


</html>