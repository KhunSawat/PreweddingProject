<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการชุด</title>
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
        <h1> <b>DRESS</b> </h1>
    </header>

    <section class="card d-flex flex-row justify-content-center mt-5 mx-3">
        <a href="DressList.php?type=01" target="_blank">
            <div class="info bg1">
                <h3>ชุดเจ้าสาวราตรี</h3>
            </div>
        </a>
        <a href="DressList.php?type=02" target="_blank">
            <div class="info bg2 mx-5">
                <h3>ชุดสูทเจ้าบ่าว</h3>
            </div>
        </a>
        <a href="DressList.php?type=03" target="_blank">
            <div class="info bg3">
                <h3>ชุดไทยพรีเมี่ยม</h3>
            </div>
        </a>
    </section>


    <?php include "./components/footer.php" ?>
</body>

</html>