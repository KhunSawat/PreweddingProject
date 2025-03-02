<?php
session_start(); // เริ่มการทำงาน session
include "../config/dbcon.php"; // รวมการเชื่อมต่อฐานข้อมูล
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>Login</title>
    <!-- Fonts and icons -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet" />
    <link id="pagestyle" href="../assets/css/material-dashboard.min.css" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-200">
    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                <nav class="navbar navbar-expand-lg blur border-radius-xl top-0 z-index-3 shadow position-absolute my-3 py-2 start-0 end-0 mx-4">
                    <div class="container-fluid ps-2 pe-0">
                        <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3">
                            Kawaii Wedding Studio
                        </a>
                        <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3" href="./Prewedding">
                            กลับหน้าหลัก
                        </a>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <main class="main-content mt-0">
        <div class="page-header align-items-start min-vh-100" style="background-image: url('../assets/pic/bg-login.jpg');">
            <span class="mask bg-gradient-dark opacity-6"></span>
            <div class="container my-auto">
                <div class="row">
                    <div class="col-lg-4 col-md-8 col-12 mx-auto">
                        <div class="card z-index-0 fadeIn3 fadeInBottom">
                            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                                    <h4 class="text-white font-weight-bolder text-center mt-3 mb-3">LOGIN</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="container-fluid ps-2 pe-0">
                                    <p class="navbar-brand font-weight-bolder ms-lg-0 ms-3 text-primary">
                                        # เข้าสู่ระบบสำหรับผู้ดูแล
                                    </p>
                                </div>
                                <form role="form" method="post" class="text-start">
                                    <div class="input-group input-group-outline my-3">
                                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                                    </div>
                                    <div class="input-group input-group-outline">
                                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">เข้าสู่ระบบ</button>
                                    </div>
                                </form>
                                <?php
                                // ส่วนของการตรวจสอบ username และ password
                                if (isset($_POST['username'])) {
                                    $username = $_POST['username'];
                                    $password = $_POST['password'];

                                    $sql = "SELECT * FROM employee WHERE em_Username = '$username' AND em_Password = '$password'";
                                    $stmt = $conn->prepare("SELECT * FROM employee WHERE em_Username = ? AND em_Password = ?");
                                    $stmt->bind_param("ss", $username, $password);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    $result = mysqli_query($conn, $sql);
                                    if (mysqli_num_rows($result) == 1) {
                                        $row = mysqli_fetch_array($result);

                                        // ตั้งค่าตัวแปร session
                                        $_SESSION['Employee_No'] = $row[0];
                                        $_SESSION['Em_Name'] = $row[1];
                                        $_SESSION['Em_Username'] = $row[2];
                                        $_SESSION['Em_pic'] = $row[7];
                                        $_SESSION['EmployeeType_No'] = $row[9];


                                        // กำหนดสิทธิ์ผู้ใช้
                                        if ($_SESSION['EmployeeType_No'] == '01') {
                                            $_SESSION["logtype"] = "admin";
                                        } else {
                                            $_SESSION["logtype"] = "employee";
                                        }

                                        // แสดงผลลัพธ์การเข้าสู่ระบบ
                                        echo "<script>
                                                Swal.fire({
                                                    title: 'เข้าสู่ระบบสำเร็จแล้ว',
                                                    text: 'ยินดีต้อนรับคุณ " . $_SESSION['Em_Name'] ." ',
                                                    icon: 'success',
                                                    confirmButtonText: 'OK',
                                                    customClass: {
                                                        confirmButton: 'btn btn-success',
                                                    }
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        window.location.href = '../index.php';
                                                    }
                                                });
                                            </script>";
                                    } else {
                                        echo "<script>
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'เข้าสู่ระบบไม่สำเร็จ',
                                                text: 'กรุณาตรวจสอบชื่อผู้ใช้หรือรหัสผ่าน',
                                                confirmButtonText: 'ตกลง'
                                            });
                                        </script>";
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- Core JS Files -->
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="../assets/js/material-dashboard.min.js?v=3.1.0"></script>
</body>

</html>