<?php include('../../config/dbcon.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>รายจ่าย</title>
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
                                    <h4 class="card-title text-center mt-5">รายการรายจ่าย</h4>
                                </div>
                                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                                    <div class="d-flex w-100 justify-content-between py-2 py-md-0">
                                        <a href="../../index.php" class="btn btn-label-info btn-round ms-4 mt-3">
                                            <i class="fas fa-home"></i> กลับหน้าแรก
                                        </a>
                                        <a href="expense_fm.php" class="btn btn-label-info btn-round me-4 mt-3">
                                            <i class="fas fa-plus"></i> เพิ่มรายจ่าย
                                        </a>
                                    </div>

                                </div>
                                <div class="container">
                                    <div class="row d-flex justify-content-end">
                                        <div class="col-sm-6 col-md-3">
                                            <div class="card card-stats card-round">
                                                <div class="card-body">
                                                    <div class="row align-items-center">
                                                        <div class="col-icon">
                                                            <div class="icon-big text-center icon-info bubble-shadow-small">
                                                                <i class="fas fa-wallet"></i> <!-- ไอคอนรายจ่าย -->
                                                            </div>
                                                        </div>
                                                        <?php
                                                        setlocale(LC_TIME, 'th_TH.UTF-8', 'thai');
                                                        // แทนที่ strftime ด้วย IntlDateFormatter เพื่อดึงชื่อเดือนปัจจุบันเป็นภาษาไทย
                                                        $formatter = new IntlDateFormatter('th_TH', IntlDateFormatter::LONG, IntlDateFormatter::NONE, 'Asia/Bangkok', IntlDateFormatter::GREGORIAN, 'MMMM');
                                                        $Month = $formatter->format(new DateTime());
                                                        $Month = ucfirst($Month);
                                                        $currentMonth = date('m');
                                                        $currentYear = date('Y');
                                                        $sql_total_expense = "SELECT SUM(Expenses) AS total_expense FROM Expense 
                                              WHERE MONTH(E_DateTime) = '$currentMonth' AND YEAR(E_DateTime) = '$currentYear'";
                                                        $result_Filter = mysqli_query($conn, $sql_total_expense);
                                                        $row = mysqli_fetch_assoc($result_Filter);
                                                        $total_expense = $row['total_expense'] ? $row['total_expense'] : 0; // ตรวจสอบว่ายอดรายจ่ายไม่เป็น null
                                                        ?>
                                                        <div class="col col-stats ms-3 ms-sm-0">
                                                            <div class="numbers">
                                                                <p class="card-category">รายจ่ายเดือน<?php echo $Month; ?></p>
                                                                <h4 class="card-title"><?php echo number_format($total_expense, 2); ?> บาท</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
                                                    <th>รหัสรายจ่าย</th>
                                                    <th>ชื่อรายจ่าย</th>
                                                    <th>ยอดจ่ายรวม</th>
                                                    <th>วันที่เวลา</th>
                                                    <th>รายละเอียด</th>
                                                    <th>แก้ไข</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                                                if (mysqli_num_rows($result) > 0) {
                                                    $num = 1;
                                                    //ให้เก็บข้อมูลที่ได้ ไว้ในตัวแปรอาร์เรย์ $row  
                                                    while ($row = mysqli_fetch_array($result)) {
                                                ?>
                                                        <tr>
                                                            <th scope='row'><?= $num ?></th>
                                                            <td><?= $row['Expense_No']; ?></td>
                                                            <td><?= $row['Expense_Name']; ?></td>
                                                            <td><?= number_format($row['Expenses'], 2); ?></td>
                                                            <td><?= $row['E_DateTime']; ?></td>
                                                            <td class="d-flex justify-content-center align-items-center" style="height: 150px;">
                                                                <?= $row['E_details']; ?>
                                                            </td>
                                                            <td>
                                                                <a href='expense_edit.php?Expense_No=<?= $row['Expense_No']; ?>' class='btn btn-info mb-1 d-block w-100'>
                                                                    <i class="fas fa-wrench"></i> <!-- ไอคอนสำหรับแก้ไข -->
                                                                </a>
                                                                <a href='expense_list.php?Expense_No=<?= $row['Expense_No']; ?>&action=delete' onClick="return confirm('คุณต้องการที่จะลบข้อมูลนี้หรือไม่ ?')" class='btn btn-danger mb-1 d-block w-100'>
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

                                            if (isset($_GET['Expense_No']) && isset($_GET['action']) && $_GET['action'] == 'delete') {
                                                $Expense_No = mysqli_real_escape_string($conn, $_GET['Expense_No']);
                                                $sql_delete = "DELETE FROM Expense WHERE Expense_No='$Expense_No'";

                                                if (mysqli_query($conn, $sql_delete)) {
                                                    echo "<script>
                              Swal.fire({
                                  title: 'สำเร็จ!',
                                  text: 'ลบข้อมูลรหัส $Expense_No สำเร็จแล้ว',
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
                                        <a href="../report/ReportExp.php" target="_blank">
                                            <button class="btn btn-primary">Export PDF</button>
                                        </a>
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
    <script>
        $(document).ready(function() {
            $("#basic-datatables").DataTable({});

            // Handle details button click
            $('.btn-details').on('click', function() {
                var button = $(this);
                var detail = button.data('detail');
                var package = button.data('package');
                var price = button.data('price');

                Swal.fire({
                    title: 'รายละเอียด',
                    html: `<div>
                                รายละเอียด: ${E_detail} </div>`,
                    icon: 'info',
                    confirmButtonText: 'ปิด',
                    customClass: {
                        confirmButton: 'btn btn-success',
                    }
                });
            });

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