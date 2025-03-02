<?php include('../../config/dbcon.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>รายชื่อพนักงาน</title>
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
    // กำหนดชื่อตาราง
    $table = "Employee";
    $tablejoin = "EmployeeType";
    $sql_select = "SELECT * FROM $table 
    INNER JOIN $tablejoin ON $table.EmployeeType_No = $tablejoin.EmployeeType_No
    ORDER BY $table.Employee_No";

    // สั่งให้ query ทำงาน
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
                                    <h4 class="card-title text-center mt-5">รายชื่อพนักงาน</h4>
                                </div>
                                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                                    <div class="d-flex w-100 justify-content-between py-2 py-md-0">
                                        <a href="../../index.php" class="btn btn-label-info btn-round ms-4 mt-3">
                                            <i class="fas fa-home"></i> กลับหน้าแรก
                                        </a>
                                        <a href="em_fm.php" class="btn btn-label-info btn-round me-4 mt-3">
                                            <i class="fas fa-plus"></i> เพิ่มรายชื่อ
                                        </a>
                                    </div>

                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table
                                            id="basic-datatables"
                                            class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ลำดับ</th>
                                                    <th>รหัสพนักงาน</th>
                                                    <th>ชื่อ</th>

                                                    <th>ที่อยู่</th>
                                                    <th>เบอร์โทรศัพท์</th>
                                                    <th>อายุ</th>
                                                    <th>รูป</th>
                                                    <th>เพศ</th>
                                                    <th>ประเภท</th>
                                                    <th>แก้ไข</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                //เมื่อได้ผลการ query จากตัวแปร $result 
                                                //ถ้า record ที่ query ได้มีจำนวนมากกว่า 0
                                                if (mysqli_num_rows($result) > 0) {
                                                    $num = 1;
                                                    //ให้เก็บข้อมูลที่ได้ ไว้ในตัวแปรอาร์เรย์ $row  
                                                    while ($row = mysqli_fetch_array($result)) {

                                                        // วันที่เกิด
                                                        $birthDate = $row[6];

                                                        // สร้างวัตถุ DateTime จากวันที่เกิด
                                                        $birthDateObj = new DateTime($birthDate);

                                                        // สร้างวัตถุ DateTime สำหรับวันที่ปัจจุบัน
                                                        $currentDateObj = new DateTime();

                                                        // คำนวณความแตกต่างระหว่างวันที่เกิดและวันที่ปัจจุบัน
                                                        $ageDiff = $birthDateObj->diff($currentDateObj);

                                                        // ดึงค่าอายุในปี
                                                        $years = $ageDiff->y;
                                                        $months = $ageDiff->m;
                                                ?>
                                                        <tr>
                                                            <th scope='row'><?= $num ?></th>
                                                            <td><?= $row[0]; ?></td>
                                                            <td><?= $row[1]; ?></td>

                                                            <td><?= $row[4]; ?></td>
                                                            <td><?= $row[5]; ?></td>
                                                            <td><?= $years . " ปี " . $months . " เดือน"; ?></td>
                                                            <td>
                                                                <img src="../../assets/pic/<?= $row[7]; ?>" width='100px' height="100px" alt="none" style="object-fit: cover;" onerror="this.onerror=null;">
                                                            </td>
                                                            <td><?= $row[8] == "M" ? "ชาย" : "หญิง"; ?></td>
                                                            <td><?= $row[11]; ?></td>
                                                            <td>
                                                                <a href='em_edit.php?Employee_No=<?= $row[0]; ?>' class='btn btn-info mb-1 d-block w-100'>
                                                                    <i class="fas fa-wrench"></i> <!-- ไอคอนสำหรับแก้ไข -->
                                                                </a>
                                                                <a href='em_list.php?Employee_No=<?= $row[0]; ?>&action=delete' onClick="return confirm('คุณต้องการที่จะลบข้อมูลนี้หรือไม่ ?')" class='btn btn-danger mb-1 d-block w-100'>
                                                                    <i class="fas fa-trash-alt"></i> <!-- ไอคอนสำหรับลบ -->
                                                                </a>
                                                            </td>
                                                        </tr>
                                                <?php
                                                        //ปิด loop while 
                                                        $num++;
                                                    }
                                                }
                                                ?>
                                            </tbody>


                                            <?php
                                            if (isset($_GET['Employee_No']) && isset($_GET['action']) && $_GET['action'] == 'delete') {
                                                $Employee_No = mysqli_real_escape_string($conn, $_GET['Employee_No']);
                                                $sql_delete = "DELETE FROM Employee WHERE Employee_No='$Employee_No'";

                                                if (mysqli_query($conn, $sql_delete)) {
                                                    echo "<script>
                                                    Swal.fire({
                                                        title: 'สำเร็จ!',
                                                        text: 'ลบข้อมูลรหัส $Employee_No สำเร็จแล้ว',
                                                        icon: 'success',
                                                        confirmButtonText: 'OK',
                                                        customClass: {
                                                            confirmButton: 'btn btn-success',
                                                        }
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            window.location.href = '$_SERVER[PHP_SELF]';
                                                        }
                                                    });
                                                </script>";
                                                } else {
                                                    echo "Error deleting record: " . mysqli_error($conn);
                                                }
                                            }
                                            ?>

                                        </table>
                                        <a href="../report/ReportEm.php" target="_blank">
                                            <button class="btn btn-primary">Export PDF</button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Custom template -->
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
    <script>
        $(document).ready(function() {
            $("#basic-datatables").DataTable({});

            $("#multi-filter-select").DataTable({
                pageLength: 5,
                initComplete: function() {
                    this.api()
                        .columns()
                        .every(function() {
                            var column = this;
                            var select = $(
                                    '<select class="form-select"><option value=""></option></select>'
                                )
                                .appendTo($(column.footer()).empty())
                                .on("change", function() {
                                    var val = $.fn.dataTable.util.escapeRegex($(this).val());

                                    column
                                        .search(val ? "^" + val + "$" : "", true, false)
                                        .draw();
                                });

                            column
                                .data()
                                .unique()
                                .sort()
                                .each(function(d, j) {
                                    select.append(
                                        '<option value="' + d + '">' + d + "</option>"
                                    );
                                });
                        });
                },
            });

            // Add Row
            $("#add-row").DataTable({
                pageLength: 5,
            });

            var action =
                '<td> <div class="form-button-action"> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

            $("#addRowButton").click(function() {
                $("#add-row")
                    .dataTable()
                    .fnAddData([
                        $("#addName").val(),
                        $("#addPosition").val(),
                        $("#addOffice").val(),
                        action,
                    ]);
                $("#addRowModal").modal("hide");
            });
        });
    </script>
</body>

</html>