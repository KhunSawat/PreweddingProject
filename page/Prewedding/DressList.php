<?php
include('../../config/dbcon.php');

// ตรวจสอบว่ามีค่าถูกส่งมาหรือไม่
$type = isset($_GET['type']) ? $_GET['type'] : null;
$row = null;

if ($type) {
    // ใช้ prepared statement เพื่อป้องกัน SQL Injection
    $sql = "SELECT * FROM Dressed_type WHERE Dtype_No = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $type);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result); // ดึงข้อมูลประเภทชุด
    }

    // ค้นหาชุดทั้งหมดในประเภทนี้
    $sql = "SELECT * FROM Dressed 
            INNER JOIN Dressed_type 
            ON Dressed.Dtype_No = Dressed_type.Dtype_No 
            WHERE Dressed_type.Dtype_No = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $type);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประเภทชุด</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="slidshow.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" href="../../assets/img/iconPage.png" type="image/x-icon" />

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
        <h1><b><?= isset($row['Dtype_Name']) ? $row['Dtype_Name'] : "ไม่พบประเภทชุด" ?></b></h1>
    </header>

    <div class="row justify-content-center align-items-center mb-5 mt-5">
        <div class="container">
            <div class="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card px-4">
                            <div class="row">
                                <?php
                                if ($result && mysqli_num_rows($result) > 0) {
                                    while ($dress = mysqli_fetch_assoc($result)) {
                                        echo '
                                            <div class="col-md-4">
                                                <div class="card my-4 d-flex justify-content-center">
                                                    <div class="d-flex justify-content-center">
                                                        <img src="../../assets/pic/dress/' . $dress['D_Pic1'] . '" class="card-img-top" alt="' . $dress['Dressed_Name'] . '" style="width: 100%; max-width: 150px;">
                                                    </div>
                                                    <div class="card-body">
                                                        <h5 class="card-title">' . $dress['Dressed_Name'] . '</h5><br>
                                                        <p class="card-text mb-0">ขนาด: ' . $dress['size'] . '</p>
                                                        <p class="card-text mb-0">รอบอก /เอว /สะโพก /ยาว:  </p>
                                                        <p class="card-text  mb-0">' . $dress['Bust'] . ' /' . $dress['Waist'] . ' /' . $dress['Hip'] . ' /' . $dress['long'] . '</p>
                                                        <p class="card-text">วัสดุ: ' . $dress['material'] . '</p>
                                                        <div class="d-flex justify-content-end py-2 py-md-0">
                                                        <p class="card-tex mt-1 me-3">ราคาเช่า</p>
                                                        <h4 class="card-title  text-secondary">' . number_format($dress['D_price'], 0, '.', ',') . '</h4><br>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        ';
                                    }
                                } else {
                                    echo '<p class="text-center">ไม่พบชุดในประเภทที่เลือก</p>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

    <!-- Sweet Alert -->
    <script src="../../assets/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Kaiadmin JS -->
    <script src="../../assets/js/kaiadmin.min.js"></script>

    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="../../assets/js/setting-demo.js"></script>
    <script src="../../assets/js/demo.js"></script>
</body>

</html>