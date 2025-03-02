<?php include('../../config/dbcon.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>ออกรายงาน</title>
    <meta
        content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
        name="viewport" />
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php
    //กำหนดชื่อตาราง
    $table = "Expense";
    $sql_select = "SELECT * FROM $table ORDER BY Expense_No";
    $result = mysqli_query($conn, $sql_select);
    ?>
    <div class="wrapper">
        <?php include('../../includes/sidebar.php'); ?>
        <div class="main-panel"> <!-- เนื้อหาอยู่ในส่วนนี้ -->
            <div class="container  mt-0">
                <div class="page-inner">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title text-center mt-5">ออกรายงาน</h4>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-center">
                                        <div class="table-responsive ">
                                            <table class="table table-striped table-bordered table-sm" style="font-size: 12px; width: 50%;margin:auto;">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th class='text-center'>#</th>
                                                        <th class='text-center'>ชื่อรายงาน</th>
                                                        <th class='text-center'>เปิดรายงาน</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    // สร้าง Object รายงาน
                                                    $reports = [
                                                        "รายงานลูกค้า" => "reportCus.php",
                                                        "รายงานพนักงาน" => "reportEm.php",
                                                        "รายงานชุด" => "reportDress.php",
                                                        "รายงานบริการแพ็กเกจ" => "reportPackage.php",
                                                        "รายงานการให้บริการ" => "reportBook.php",
                                                        "รายงานรายจ่าย" => "reportExp.php",
                                                        "รายงานสรุปรายรับรายจ่าย(ปี)" => "reportIncExp.php"
                                                    ];

                                                    $count = 1;
                                                    foreach ($reports as $reportName => $reportFile) {
                                                        echo "<tr>";
                                                        echo "<td class='text-center'>{$count}</td>";
                                                        echo "<td>{$reportName}</td>";
                                                        echo "<td class='text-center'><a href='{$reportFile}' target='_blank' class='btn btn-label-info btn-round btn-sm'><span class='btn-label'><i class='fa fa-print'></i></span>Print</a></td>";
                                                        echo "</tr>";
                                                        $count++;
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!--   Core JS Files   -->
    <script src="../../assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="../../assets/js/core/popper.min.js"></script>
    <script src="../../assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="../../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <!-- Datatables -->
    <script src="../../assets/js/plugin/datatables/datatables.min.js"></script>
    <!-- Kaiadmin JS -->
    <script src="../../assets/js/kaiadmin.min.js"></script>
    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="../../assets/js/setting-demo2.js"></script>
</body>

</html>