<?php include('../../includes/header.php') ?>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="slidshow.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<nav>
    <div class="container">
        <div class="nav-con">
            <div class="logo">
                <a href="#">Kawaii Wedding Studio</a>
            </div>
            <ul class="menu">
                <li><a href="index.html">Home</a></li>
                <li><a href="Package.html">Package</a></li>
                <li><a href="Dress.html">Dress</a></li>
                <li><a href="ContactUs.html">Contact Us</a></li>
                <a href="../login.php">
                    <button class="w3-btn w3-white w3-border w3-border-red w3-round-large">
                        Log In
                    </button>
                </a>
            </ul>
        </div>
    </div>
</nav>
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
                    <span class="badge badge-primary px-5 py-3" style="background: #ea586b; color: white; font-size: 1.2rem;">ตารางคิว</span>
                </h3>
                <?php include('../../components/calendar.php'); ?>
            </div>
        </div>
    </div>
</section>


<div class="footer-top">
    <div class="container">
        <div class="footer-top-con">

            <div class="footer-top-item">
                <h4>About Us</h4>
                <p>Kawaii Wedding Studio : ร้านเช่าชุดแต่งงาน ชุดไทย ชุดเจ้าสาว ถ่ายพรีเว้ดดิ้ง ตั้งอยู่เลขที่
                    205/1 ซอยจินตคาม ถนนทหาร ตำบลหมากแข้ง อำเภอเมือง จังหวัดอุดรธานี</p>
            </div>
            <div class="footer-top-item">
                <h4>Stay Connected</h4>
                <p><a href="https://www.facebook.com/profile.php?id=100082993164420" class="bi bi-facebook"> Facebook</a></p>
                <p><a href="" class="bi bi-line"> Line</a></p>
                <p><a href="https://www.tiktok.com/@kawaii.wedding_studio?is_from_webapp=1&sender_device=pc" class="bi bi-tiktok"> Tiktok</a></p>
            </div>
            <div class="footer-top-item">
                <h4>Dress</h4>
                <p><a href="Dress.html"> Dress</a></p>
                <p><a href="Dress.html"> Suit</a></p>
                <p><a href="Dress.html"> Thai Dress</a></p>
            </div>


        </div>
    </div>
</div>

<div class="footer-bottom">
    <p>Copyright 2024. Kawaii Wedding Studio web design.</p>
</div>
<script src="slidshow.js"></script>
<?php include('../../includes/footer.php') ?>