<?php
session_start();
include("../../config/dbcon.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Return_No = $_POST['Return_No']; // รับรหัสการคืนที่ส่งมาจากฟอร์ม
    $BookingH_No = $_POST['BookingH_No'];
    $Dressed_No = $_POST['Dressed_No'];
    $Date_out = $_POST['Date_out'];
    $Date_wait = $_POST['Date_wait'];
    $Return_Status_No = '01';

    // echo '<pre>';
    // var_dump($_POST); // ทดสอบ POST ที่รับมา
    // echo '</pre>';
    // echo '<pre>';
    // var_dump($_GET); // ทดสอบ GET ที่รับมา
    // echo '</pre>';

    // SQL สำหรับอัปเดตข้อมูลการเบิกชุด
    $sql_update = "UPDATE `return` 
                   SET 
                       BookingH_No = '$BookingH_No', 
                       Dressed_No = '$Dressed_No', 
                       Date_out = '$Date_out', 
                       Date_wait = '$Date_wait', 
                       Return_Status_No = '$Return_Status_No' 
                   WHERE Return_No = '$Return_No'";

    // เรียกใช้คำสั่ง SQL
    $message = '';
    if (mysqli_query($conn, $sql_update)) {
        $message = "เบิกชุดรหัส $Dressed_No เสร็จสิ้น"; // แจ้งเตือนข้อความสำเร็จ
    } else {
        $message = "เกิดข้อผิดพลาด: " . mysqli_error($conn); // แจ้งเตือนข้อผิดพลาด
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedBookingH_No = $_POST['BookingH_No'] ?? ''; // ดึงค่าจาก POST เมื่อมีการเปลี่ยน
} else {
    $selectedBookingH_No = ''; // ค่าดีฟอลต์
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // echo '<pre>';
    // print_r($_POST); // ตรวจสอบค่าที่ส่งมาหลังการเลือก dropdown
    // echo '</pre>';
}


// รับค่า GET และ POST
$selectedBookingH_No = isset($_GET['BookingH_No']) ? $_GET['BookingH_No'] : '';
$selectedDressNo = isset($_GET['Dressed_No']) ? $_GET['Dressed_No'] : '';


// ดึงข้อมูลการจองทั้งหมด
$sql_booking = "SELECT BookingH_No FROM booking_h ORDER BY BookingH_No DESC";
$result_booking = mysqli_query($conn, $sql_booking);



// ดึงข้อมูลการเบิกชุดที่เกี่ยวข้องกับ BookingH_No ที่เลือก
$sql_return = "SELECT
    r.Return_No,
    r.BookingH_No,
    dressed.Dressed_No,
    dressed.Dressed_Name,
    dressed.D_Color,
    r.Date_out,
    r.Date_wait,
    r.Date_in,
    return_status.Return_status_Name
FROM
    `return` AS r
INNER JOIN dressed ON r.Dressed_No = dressed.Dressed_No
INNER JOIN return_status ON r.Return_Status_No = return_status.Return_Status_No
WHERE
    r.Return_Status_No = '00'
ORDER BY
    r.Return_No DESC";
$result_return = mysqli_query($conn, $sql_return);

// Query ดึงรายการชุดทั้งหมด
$sql_dresses = "SELECT Dressed_No, Dressed_Name, D_Color FROM dressed ORDER BY Dressed_Name ASC";
$result_dresses = mysqli_query($conn, $sql_dresses);

//ทดสอบการรับข้อมูล
// echo '<pre>';
// var_dump($_GET);
// echo '</pre>';
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$bookingH_No = isset($_GET['BookingH_No']) ? $_GET['BookingH_No'] : (isset($selectedBookingH_No) ? $selectedBookingH_No : '');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สถานะชุด</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../assets/js/plugin/webfont/webfont.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../assets/css/plugins.min.css" />
    <link rel="stylesheet" href="../../assets/css/kaiadmin.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <?php include('../../includes/sidebar.php'); ?>
        <div class="main-panel"> <!-- เนื้อหาอยู่ในส่วนนี้ -->
            <div class="container mt-5">

                <div class="card mb-5">
                    <div class="card-header text-center">
                        <h4>สถานะเบิก-คืนชุด</h4>
                    </div>

                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                        <div class="d-flex w-100 justify-content-between py-2 py-md-0">
                            <a href='search_booking.php?BookingH_No=<?= $bookingH_No ?>' class="btn btn-label-info btn-round ms-4 mt-3">
                                <i class="fas fa-home"></i> หน้าจัดการงาน
                            </a>

                        </div>
                    </div>

                </div>

                <!-- Return List Table -->
                <?php
                // รอเบิก
                $sql_00 = "SELECT count(*) as count FROM `return` WHERE Return_Status_No = '00'";
                $result_00 = mysqli_query($conn, $sql_00);
                $count00 = 0; // ตั้งค่าเริ่มต้นเพื่อป้องกันข้อผิดพลาด

                if ($result_00) {
                    $num00 = mysqli_fetch_assoc($result_00);
                    $count00 = $num00['count']; // ใช้ชื่อคอลัมน์ที่ถูกต้อง
                }
                // เบิกแล้ว
                $sql_01 = "SELECT count(*) as count FROM `return` WHERE Return_Status_No = '01'";
                $result_01 = mysqli_query($conn, $sql_01);
                $count01 = 0; // ตั้งค่าเริ่มต้นเพื่อป้องกันข้อผิดพลาด

                if ($result_01) {
                    $num01 = mysqli_fetch_assoc($result_01);
                    $count01 = $num01['count']; // ใช้ชื่อคอลัมน์ที่ถูกต้อง
                }


                ?>


                <div class="mb-4 ms-4 text-left ">
                    <button type="button" class="btn btn-primary filter-button position-relative" data-status="รอเบิก">
                        รอเบิก
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">
                            <?php echo $count00; ?>
                            <span class="visually-hidden">unread messages</span>
                        </span>
                    </button>
                    <button type="button" class="btn btn-warning filter-button text-white position-relative ms-2" data-status="เบิกชุดแล้ว">
                        เบิกชุดแล้ว
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning">
                            <?php echo $count01; ?>
                            <span class="visually-hidden">unread messages</span>
                        </span>
                    </button>
                    <button class="btn btn-success filter-button ms-2" data-status="คืนชุดแล้ว">คืนชุดแล้ว</button>
                    <button class="btn btn-secondary filter-button ms-2" data-status="">ทั้งหมด</button>
                </div>

                <div class="card">

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="returnTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>รหัสการเบิก</th>
                                        <th>รหัสงาน</th>
                                        <th>รหัสชุด</th>
                                        <th>ชื่อชุด(สี)</th>
                                        <th>วันที่เบิก</th>
                                        <th>กำหนดคืน</th>
                                        <th>วันที่คืน</th>
                                        <th>สถานะ</th>
                                        <th>จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // SQL สำหรับดึงข้อมูลการเบิกที่ตรงกับ BookingH_No ที่เลือก
                                    $sql_return = "SELECT
                                *
                                FROM
                                    `return` AS r
                                INNER JOIN dressed ON r.Dressed_No = dressed.Dressed_No
                                INNER JOIN return_status ON r.Return_Status_No = return_status.Return_Status_No
                                ORDER BY
                                    r.Return_No DESC";
                                    $Result_dress_table = mysqli_query($conn, $sql_return);

                                    //ดึงข้อมูลมาแสดง dropdown ด้วย BookingH_No ที่ตรงกัน
                                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['BookingH_No'])) {
                                        $BookingH_No = $_POST['BookingH_No'];

                                        // ดึงข้อมูลชุดที่ตรงกับ BookingH_No
                                        $sql_dressed = "SELECT * FROM dressed WHERE BookingH_No = '$BookingH_No'";
                                        $result_dressed = mysqli_query($conn, $sql_dressed);
                                    }

                                    //ไฮท์ไลฟ์ส่วนเบิก คืน รอคืน
                                    ?>
                                    <?php if (mysqli_num_rows($Result_dress_table) > 0) {
                                        while ($dressT = mysqli_fetch_assoc($Result_dress_table)) {
                                            $badgeClass = '';
                                            if ($dressT['Return_Status_No'] == '00') {
                                                $badgeClass = 'badge badge-primary';
                                            } elseif ($dressT['Return_Status_No'] == '01') {
                                                $badgeClass = 'badge badge-warning';
                                            } elseif ($dressT['Return_Status_No'] == '02') {
                                                $badgeClass = 'badge badge-success';
                                            }
                                            // echo '<pre>';
                                            // var_dump($badgeClass);
                                            // var_dump($dressT['Return_status_Name']);
                                            // echo '</pre>';
                                    ?>
                                            <tr>
                                                <td><?= $dressT['Return_No'] ?></td>
                                                <td><?= $dressT['BookingH_No'] ?></td>
                                                <td><?= $dressT['Dressed_No'] ?></td>
                                                <td><?= $dressT['Dressed_Name'] . " (" . $dressT['D_Color'] . ")" ?></td>
                                                <td><?= $dressT['Date_out'] ?></td>
                                                <td><?= $dressT['Date_wait'] ?></td>
                                                <td><?= $dressT['Date_in'] ?></td>
                                                <td>
                                                    <span class="<?= $badgeClass ?>">
                                                        <?= $dressT['Return_status_Name'] ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="search_booking.php?BookingH_No=<?= $dressT['BookingH_No']; ?>" class="d-block btn btn-secondary btn-sm">
                                                        <i class="fas fa-clipboard-list"></i> งาน
                                                    </a>
                                                    <a href="return_list.php?BookingH_No=<?= $dressT['BookingH_No']; ?>" class="mt-1 d-block btn btn-info btn-sm" target="_blank">
                                                        <i class="fas fa-box-open"></i> เบิก
                                                    </a>
                                                    <a href="receive.php?BookingH_No=<?= $dressT['BookingH_No']; ?>" class="mt-1 d-block btn btn-success btn-sm" target="_blank">
                                                        <i class="fas fa-box-open"></i> คืน
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php }
                                    } else { ?>
                                        <tr>
                                            <td colspan="7" class="text-center">ไม่พบข้อมูลการเบิกสำหรับการจองนี้</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        function selectReturn(returnNo) {
            // กำหนดค่า Return_No ในฟอร์ม
            document.getElementById('Return_No').value = returnNo;

        }
        $(document).ready(function() {
            var table = $('#returnTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/Thai.json"
                }
            });

            // ฟังก์ชันกรองข้อมูลในตารางตาม BookingH_No
            $('#BookingH_No').on('change', function() {
                var selectedBooking = $(this).val();
                if (selectedBooking) {
                    table.column(1).search(selectedBooking).draw(); // กรองตารางด้วย BookingH_No ที่เลือก
                } else {
                    table.column(1).search('').draw(); // แสดงทั้งหมดหากไม่ได้เลือก BookingH_No
                }
            });

            // ฟังก์ชันกรองข้อมูลในตารางตามสถานะ
            $('.filter-button').on('click', function() {
                var status = $(this).data('status');
                if (status === 'all') {
                    table.search('').draw(); // แสดงทั้งหมด
                } else {
                    table.search(status).draw(); // กรองตามสถานะ
                }
            });
            //แสดงข้อความอัพเดท
            <?php if (!empty($message)) { ?>
                Swal.fire({
                    title: 'แจ้งเตือน',
                    text: "<?= $message ?>",
                    icon: "<?= strpos($message, 'เสร็จสิ้น') !== false ? 'success' : 'error' ?>",
                    confirmButtonText: 'ตกลง'
                });
            <?php } ?>

        });

        function updateBookingUrl(bookingNo) {
            console.log('Function called with:', bookingNo);
            if (bookingNo) {
                window.location.href = `?BookingH_No=${bookingNo}`;
            }
        }
        document.getElementById('BookingH_No').addEventListener('change', function() {
            console.log('Selected value:', this.value); // ตรวจสอบค่าที่เปลี่ยน
        });
    </script>
</body>

</html>