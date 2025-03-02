<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ContactUs</title>
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
        <h1> <b>CONTACT US</b> </h1>
    </header>


    <section class="hero">
        <div class="container">
            <div class="hero-con">
                <div class="hero-info">
                    <h3> <b>Kawaii Wedding Studio</b></h3>
                    <p>205/1 ซอยจินตคาม ถนนทหาร ตำบลหมากแข้ง อำเภอเมือง จังหวัดอุดรธานี</p>
                    <p><b>tel</b>: 092-502-4939</p>
                    <p><b>Email</b>: Komcharn.sriring.joey@gmail.com</p>
                    <p><b>Website</b>: Kawaii Wedding Studio</p>
                    <p><b>Facebook</b>: Kawaii Wedding Studio</p>
                </div>

                <div class="hero-img">
                    <img src=".png/shop.jpg" alt="">
                </div>
            </div>
        </div>
    </section>
    <div class="">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d951.8410023715767!2d102.79506802839843!3d17.394310198964096!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31239d4248aeca49%3A0x374a473b5934aeed!2sKawaii%20wedding%20studio!5e0!3m2!1sth!2sth!4v1724642519071!5m2!1sth!2sth"
            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy">
        </iframe>
    </div>
    <?php include "./components/footer.php" ?>
</body>

</html>