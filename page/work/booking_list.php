<?php
session_start();
include("../../config/dbcon.php");

$current_page = basename($_SERVER['PHP_SELF']);

$sql_select = "SELECT *
    FROM booking_h
    INNER JOIN work_status ON booking_h.work_status_No = work_status.Work_Status_No
    INNER JOIN payment_status ON payment_status.Payment_status_No = booking_h.Payment_status_No
    INNER JOIN customers ON customers.customer_No = booking_h.Customer_No
    INNER JOIN packages ON packages.Package_No = booking_h.Package_No
    ORDER BY BookingH_No DESC
";
$result = mysqli_query($conn, $sql_select);


$sql_detail_query = "SELECT 
        booking_d.QTY, 
        booking_d.Price, 
        packages.Package_Name
    FROM booking_d
    INNER JOIN packages ON booking_d.Package_No = packages.Package_No
";

$result_detail = mysqli_query($conn, $sql_detail_query);
$package_details = [];
$total_service_price = 0;
while ($detail_row = mysqli_fetch_assoc($result_detail)) {
    $package_details[] = $detail_row;
    $total_service_price += $detail_row['Price'];
}

function fetchPackageDetails($conn, $BookingH_No)
{
    $sql_detail_query = "SELECT booking_d.QTY, booking_d.Price, packages.Package_Name
                         FROM booking_d
                         INNER JOIN packages ON booking_d.Package_No = packages.Package_No
                         WHERE booking_d.BookingH_No = '$BookingH_No'";
    $result_detail = mysqli_query($conn, $sql_detail_query);
    $package_details = [];
    $total_service_price = 0;

    while ($detail_row = mysqli_fetch_assoc($result_detail)) {
        $package_details[] = $detail_row;
        $total_service_price += $detail_row['Price'];
    }
    return [$package_details, $total_service_price];
}

function getStatusBadgeClass($status_no, $status_type = 'work')
{
    $badge_classes = [
        'work' => [
            '00' => ['class' => 'badge badge-danger', 'progress' => 'bg-danger w-100', 'value' => 100],
            '01' => ['class' => 'badge badge-info', 'progress' => 'bg-info w-50', 'value' => 50],
            '02' => ['class' => 'badge badge-success', 'progress' => 'bg-success w-100', 'value' => 100]
        ],
        'payment' => [
            '01' => ['class' => 'badge badge-danger', 'progress' => 'bg-danger w-100', 'value' => 100],
            '02' => ['class' => 'badge badge-count', 'progress' => 'bg-info w-25', 'value' => 25],
            '03' => ['class' => 'badge badge-info', 'progress' => 'bg-info w-50', 'value' => 50],
            '04' => ['class' => 'badge badge-success', 'progress' => 'bg-success w-100', 'value' => 100]
        ]
    ];
    return $badge_classes[$status_type][$status_no] ?? ['class' => 'badge badge-secondary', 'progress' => 'bg-secondary', 'value' => 0];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>รายการการจอง</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../assets/css/plugins.min.css" />
    <link rel="stylesheet" href="../../assets/css/kaiadmin.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Fonts and icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <?php include('../../includes/sidebar.php'); ?>
        <div class="main-panel "> <!-- เนื้อหาอยู่ในส่วนนี้ -->
            <div class="container mt-0">
                <div class="page-inner">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title text-center mt-5">รายการการจอง</h4>
                                </div>
                                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                                    <div class="d-flex w-100 justify-content-between py-2 py-md-0">
                                        <a href="../../index.php" class="btn btn-label-info btn-round ms-4 mt-3">
                                            <i class="fas fa-home"></i> กลับหน้าแรก
                                        </a>
                                        <a href="booking_fm.php" class="btn btn-label-info btn-round me-4 mt-3">
                                            <i class="fas fa-plus"></i> เพิ่มรายการ
                                        </a>
                                    </div>
                                </div>
                                <?php include('../../components/calendar.php'); ?>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="basic-datatables" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>รหัสการจอง</th>
                                                    <th style="width: 160px;">วันที่จอง(จอง,งาน)</th>
                                                    <th style="width: 160px;">ชื่อลูกค้า</th>
                                                    <th style="width: 100px;">สถานะการชำระเงิน</th>
                                                    <th style="width: 100px;">สถานะการดำเนินงาน</th>
                                                    <th>รายละเอียด</th>
                                                    <th>แก้ไข</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (mysqli_num_rows($result) > 0) {
                                                    $num = 1;
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        [$package_details, $total_service_price] = fetchPackageDetails($conn, $row['BookingH_No']);

                                                        $work_status = getStatusBadgeClass($row['work_status_No'], 'work');
                                                        $payment_status = getStatusBadgeClass($row['Payment_status_No'], 'payment');

                                                        $start_date = ($row['B_DateTime_S'] === '0000-00-00') ? '-' : date('d-m-Y', strtotime($row['B_DateTime_S']));
                                                        $end_date = ($row['B_DateTime_F'] === '0000-00-00') ? '-' : date('d-m-Y', strtotime($row['B_DateTime_F']));
                                                        $date_range = $start_date . ' / ' . $end_date;
                                                ?>
                                                        <tr>
                                                            <th scope='row'><?= $num ?></th>
                                                            <td><?= $row['BookingH_No']; ?></td>
                                                            <td><?= $date_range; ?></td>
                                                            <td><?= $row['cus_Name']; ?></td>
                                                            <td>
                                                                <div class="<?= $payment_status['class']; ?>"><?= $row['Payment_status_Name']; ?></div>
                                                                <div class='progress progress-sm mt-1'>
                                                                    <div class='progress-bar <?= $payment_status['progress']; ?>' role='progressbar' aria-valuenow='<?= $payment_status['value']; ?>' aria-valuemin='0' aria-valuemax='100'></div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="<?= $work_status['class']; ?>"><?= $row['Work_Name']; ?></div>
                                                                <div class='progress progress-sm mt-1'>
                                                                    <div class='progress-bar <?= $work_status['progress']; ?>' role='progressbar' aria-valuenow='<?= $work_status['value']; ?>' aria-valuemin='0' aria-valuemax='100'></div>
                                                                </div>
                                                            </td>
                                                            <td class="d-flex justify-content-center align-items-center" style="height: 150px;">
                                                                <div class="package-container text-center">
                                                                    <div class=" text-secondary">
                                                                        <?= $row['Package_Name']; ?>
                                                                    </div>
                                                                    <div class="mt-2">
                                                                        <button class="btn btn-outline-secondary btn-details rounded-pill"
                                                                            data-id="<?= $num ?>"
                                                                            data-detail="<?= $row['B_detail'] ?>"
                                                                            data-packages='<?= json_encode($package_details) ?>'
                                                                            data-package="<?= $row['Package_Name'] ?>"
                                                                            data-price="<?= number_format($row['P_Price'], 2); ?>">
                                                                            <i class="fas fa-eye"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <a href='search_booking.php?BookingH_No=<?= $row['BookingH_No']; ?>' class='btn btn-secondary mb-1 d-block w-100'>
                                                                    <i class="fas fa-clipboard-list"></i>
                                                                </a>
                                                                <a href='booking_edit.php?BookingH_No=<?= $row['BookingH_No']; ?>' class='btn btn-info mb-1 d-block w-100'>
                                                                    <i class="fas fa-wrench"></i>
                                                                </a>
                                                                <a href='booking_list.php?BookingH_No=<?= $row['BookingH_No']; ?>&action=delete' onClick="return confirm('คุณต้องการที่จะลบข้อมูลนี้หรือไม่ ?')" class='btn btn-danger mb-1 d-block w-100'>
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                <?php
                                                        $num++;
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                            <?php
                                            if (isset($_GET['BookingH_No']) && isset($_GET['action']) && $_GET['action'] == 'delete') {
                                                $BookingH_No = mysqli_real_escape_string($conn, $_GET['BookingH_No']);
                                                $sql_delete = "DELETE FROM Booking_H WHERE BookingH_No='$BookingH_No'";

                                                if (mysqli_query($conn, $sql_delete)) {
                                                    echo "<script>
                                                Swal.fire({
                                                    title: 'สำเร็จ!',
                                                    text: 'ลบข้อมูลหมายเลข $BookingH_No สำเร็จแล้ว',
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
                                        <a href="#" id="exportPDF">
                                            <button class="btn btn-primary">Export PDF</button>
                                        </a>

                                        <script>
                                            document.getElementById("exportPDF").addEventListener("click", function(event) {
                                                event.preventDefault(); // ป้องกันไม่ให้ลิงก์ทำงานทันที

                                                var searchQuery = document.querySelector('#basic-datatables_filter input').value.trim(); // ดึงค่าค้นหา
                                                var exportUrl = "../report/Reportbook.php";

                                                if (searchQuery !== "") { // ถ้ามีค่าค้นหา ให้เพิ่มไปที่ URL
                                                    exportUrl += "?search=" + encodeURIComponent(searchQuery);
                                                }

                                                window.open(exportUrl, '_blank'); // เปิด Reportbook.php ในแท็บใหม่
                                            });
                                        </script>



                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script src="../../assets/js/core/jquery-3.7.1.min.js"></script>
                <script src="../../assets/js/core/popper.min.js"></script>
                <script src="../../assets/js/core/bootstrap.min.js"></script>
                <script src="../../assets/js/plugin/datatables/datatables.min.js"></script>
                <script src="../../assets/js/plugin/sweetalert2/sweetalert2.min.js"></script>
                <script src="../../assets/js/kaiadmin.min.js"></script>

                <script>
                    $(document).ready(function() {
                        $("#basic-datatables").DataTable({});

                        // Handle details button click
                        $('.btn-details').on('click', function() {
                            var button = $(this);
                            var detail = button.data('detail');
                            var package = button.data('package');
                            var price = button.data('price');
                            price = price.replace(/,/g, '');
                            price = parseFloat(price);

                            // ประกาศตัวแปร totalPriceD เพื่อเก็บค่ารวม
                            var totalPriceD = 0;

                            var packages = $(this).data('packages');
                            var detail = $(this).data('detail');

                            var packageDetailsHtml = ''; // HTML สำหรับแสดงผลแพ็กเกจ
                            packages.forEach(function(pkg) {
                                packageDetailsHtml += `
                            <div class="d-flex justify-content-between mb-1">
                                <span class="ms-4">${pkg.Package_Name}</span>
                                <span class="me-4">${parseFloat(pkg.Price).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })} บาท</span>
                            </div>
                        `;

                                // บวกค่า Price เข้ากับ totalPriceD
                                totalPriceD += parseFloat(pkg.Price);
                            });

                            Swal.fire({
                                title: 'รายละเอียดแพ็กเกจ',
                                html: `
                                <div>
                                    <div>แพ็กเกจ: ${package}</div>
                                    <div>ราคา: ${price.toLocaleString()} บาท</div>
                                    <div><strong>หมายเหตุ :</strong> ${detail}</div><hr>
                                    <div><strong>บริการเสริมอื่นๆ:</strong></div>
                                    ${packageDetailsHtml}<hr>
                                    <div><strong>ยอดเงิน(แพ็กเกจ)</strong></div>
                                    <div class="d-flex justify-content-between ">
                                        <span class="ms-4">มัดจำ (25%) :</span>
                                        <span class="me-4">${(price * 0.25).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })} บาท</span>
                                    </div>
                                    <div class="d-flex justify-content-between ">
                                        <span class="ms-4">หักมัดจำ :</span>
                                        <span class="me-4">${(price * 0.75).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })} บาท</span>
                                    </div>
                                    <div class="d-flex justify-content-between ">
                                        <span class="ms-4">ทั้งหมด :</span>
                                        <span class="me-4">${price.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })} บาท</span>
                                    </div><br>
                                    <div><strong>ยอดเงิน(บริการเสริม)</strong></div>
                                    <div class="d-flex justify-content-between ">
                                        <span class="ms-4">มัดจำ (50%):</span>
                                        <span class="me-4">${(totalPriceD*0.5).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })} บาท</span>
                                    </div>
                                    <div class="d-flex justify-content-between ">
                                        <span class="ms-4">หักมัดจำ :</span>
                                        <span class="me-4">${(totalPriceD*0.5).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })} บาท</span>
                                    </div>
                                    <div class="d-flex justify-content-between ">
                                        <span class="ms-4">ทั้งหมด :</span>
                                        <span class="me-4">${totalPriceD.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })} บาท</span>
                                    </div>
                                </div>
                            `,
                                icon: 'info',
                                confirmButtonText: 'ปิด',
                                customClass: {
                                    confirmButton: 'btn btn-success',
                                }
                            });

                        });

                    });
                </script>
            </div>
        </div>
    </div>
</body>

</html>