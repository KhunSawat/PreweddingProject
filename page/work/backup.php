<?php
session_start();
include("../../config/dbcon.php");
// ตรวจสอบการส่งข้อมูลชุดจากหน้า DressReturnList.php
if (isset($_GET['selectedDress']) && isset($_GET['BookingH_No'])) {
    $selectedDress = $_GET['selectedDress'];
    $bookingH_No = $_GET['BookingH_No'];

    // ดึงข้อมูลชุดจากฐานข้อมูล
    $sql = "SELECT * FROM Dressed WHERE Dressed_No = '$selectedDress'";
    $result = mysqli_query($conn, $sql);
    $dress = mysqli_fetch_assoc($result);

    // เก็บชุดที่เลือกไว้ใน session (สามารถเลือกหลายชุดได้)
    if (!isset($_SESSION['selectedDresses'][$bookingH_No])) {
        $_SESSION['selectedDresses'][$bookingH_No] = [];
    }
    $_SESSION['selectedDresses'][$bookingH_No][] = $dress;
}

// ตรวจสอบว่าได้เลือกชุดแล้วหรือไม่สำหรับ BookingH_No ปัจจุบัน
$bookingH_No = isset($_GET['BookingH_No']) ? $_GET['BookingH_No'] : '';
$selectedDresses = isset($_SESSION['selectedDresses'][$bookingH_No]) ? $_SESSION['selectedDresses'][$bookingH_No] : [];
// ตรวจสอบการค้นหาผ่าน POST หรือ GET
$BookingH_No = '';
$selectedBooking = '';

// ตรวจสอบการส่งผ่าน URL (GET method)
if (isset($_GET['BookingH_No'])) {
    $BookingH_No = mysqli_real_escape_string($conn, $_GET['BookingH_No']);
    $selectedBooking = $BookingH_No;
}
// ตรวจสอบการส่งผ่าน POST method
elseif (isset($_POST['search'])) {
    $BookingH_No = mysqli_real_escape_string($conn, $_POST['BookingH_No']);
    $selectedBooking = $BookingH_No;
}

// ดึงข้อมูลการจอง
$sql_select = "SELECT 
    *
    FROM booking_h
    INNER JOIN customers ON booking_h.Customer_No = customers.customer_No
    INNER JOIN packages ON packages.Package_No = booking_h.Package_No
    WHERE booking_h.BookingH_No = '$BookingH_No'
    ORDER BY booking_h.BookingH_No DESC;
";
$result = mysqli_query($conn, $sql_select);

// ตรวจสอบผลลัพธ์การค้นหา
if (!$result) {
    die("Error: " . mysqli_error($conn));
}

// ดึงข้อมูลรายละเอียดแพ็คเกจ
$sql_details = "SELECT 
        booking_d.BookingD_No, 
        booking_d.QTY, 
        booking_d.Price AS P_Price,  -- ตรวจสอบว่าในตาราง Booking_D มีคอลัมน์ Price
        packages.Package_Name, 
        packages.P_Detail
    FROM booking_d
    INNER JOIN packages ON booking_d.Package_No = packages.Package_No
    WHERE booking_d.BookingH_No = '$BookingH_No'
";

$result_details = mysqli_query($conn, $sql_details);

// ตรวจสอบผลลัพธ์การค้นหา
if (!$result_details) {
    die("Error: " . mysqli_error($conn));
}

// ดึงรายการการจองทั้งหมดสำหรับ dropdown
$sql_dropdown = "SELECT BookingH_No FROM booking_h ORDER BY BookingH_No DESC";
$result_dropdown = mysqli_query($conn, $sql_dropdown);

// ดึงรายการแพ็คเกจที่ไม่ใช่ประเภท 01
$sql_packages = "SELECT Package_No, Package_Name, P_Price FROM Packages WHERE Ptype_No <> '01'";
$result_packages = mysqli_query($conn, $sql_packages);

if (!$result_packages) {
    die("Error: " . mysqli_error($conn));
}


// ตรวจสอบการส่งข้อมูลลบ
if (isset($_POST['delete_service'])) {
    $bookingD_No = mysqli_real_escape_string($conn, $_POST['BookingD_No']);  // รับ ID ของบริการที่ต้องการลบ

    // SQL สำหรับลบข้อมูลจากตาราง booking_d
    $sql_delete = "DELETE FROM booking_d WHERE BookingD_No = '$bookingD_No'";

    if (mysqli_query($conn, $sql_delete)) {
        echo "success";  // ส่งกลับ success เพื่อให้ AJAX รู้ว่าลบสำเร็จ
    } else {
        echo "Error: " . mysqli_error($conn);  // ส่งข้อความแสดงข้อผิดพลาดกลับ
    }
    exit;
}


if (isset($_GET['BookingH_No'])) {
    $BookingH_No = mysqli_real_escape_string($conn, $_GET['BookingH_No']);
    $selectedBooking = $BookingH_No;
} elseif (isset($_POST['search'])) {
    $BookingH_No = mysqli_real_escape_string($conn, $_POST['BookingH_No']);
    $selectedBooking = $BookingH_No;
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ค้นหาข้อมูลการจอง</title>
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
                urls: ["../assets/css/fonts.min.css"],
            },
            active: function() {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <style>
        .form-control[readonly],
        .form-control[disabled] {
            background-color: #e9ecef;
            cursor: not-allowed;
        }

        .remove-item {
            cursor: pointer;
            color: red;
        }
    </style>
    <script>
        $(document).ready(function() {
            let itemIndex = 0; // ใช้เพื่อนับจำนวนฟิลด์ที่ถูกเพิ่ม

            // ฟังก์ชันเพื่อเพิ่มฟิลด์ใหม่เมื่อกดปุ่ม +
            $('#addItem').click(function() {
                itemIndex++;
                const newItemHtml = `
                <div class="row item-row mb-2">
                    <div class="form-group col-md-3">
                        <select name="Package_No[]" id="Package_No_${itemIndex}" class="form-control packageDropdown" required>
                            <option value="" disabled selected>เลือกแพ็คเกจ</option>
                            <?php
                            mysqli_data_seek($result_packages, 0); // รีเซ็ตผลลัพธ์
                            while ($row_package = mysqli_fetch_assoc($result_packages)):
                            ?>
                                <option value="<?= htmlspecialchars($row_package['Package_No']) ?>" data-price="<?= $row_package['P_Price'] ?>">
                                    <?= htmlspecialchars($row_package['Package_Name']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <input type="number" class="form-control qtyInput" name="QTY[]" id="QTY_${itemIndex}" placeholder="จำนวน" required>
                    </div>
                    <div class="form-group col-md-3">
                        <input type="text" class="form-control priceInput" name="Price[]" id="Price_${itemIndex}" placeholder="ราคา">
                    </div>
                    <div class="form-group col-md-3">
                        <span class="remove-item btn ">
                            <i class="fas fa-trash-alt mt-2"></i>
                        </span>
                    </div>
                </div>`;
                $('#itemsContainer').append(newItemHtml); // เพิ่มฟิลด์ใหม่ใน container
            });

            // ฟังก์ชันเพื่อจัดการลบฟิลด์
            $(document).on('click', '.remove-item', function() {
                $(this).closest('.item-row').remove(); // ลบฟิลด์นั้นออก
                calculateGrandTotal(); // คำนวณค่าใช้จ่ายรวมอีกครั้ง
            });

            $(document).ready(function() {
                // เมื่อคลิกที่ปุ่มลบในแต่ละบริการ
                $('.remove-service').on('click', function() {
                    var serviceId = $(this).data('id'); // ดึง BookingD_No ของบริการที่ต้องการลบ
                    var serviceRow = $(this).closest('tr'); // ดึงแถวของบริการที่ต้องการลบ

                    // ยืนยันการลบ
                    Swal.fire({
                        title: 'คุณแน่ใจหรือไม่?',
                        text: 'คุณต้องการลบบริการนี้หรือไม่',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'ยืนยัน',
                        cancelButtonText: 'ยกเลิก'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: 'remove_service.php',
                                type: 'POST',
                                data: {
                                    BookingD_No: serviceId
                                },
                                success: function(response) {
                                    console.log("Response from server:", response); // เพิ่มบรรทัดนี้เพื่อตรวจสอบการตอบกลับ
                                    if (response === "success") {
                                        Swal.fire('ลบแล้ว!', 'บริการนี้ถูกลบออกจากรายการของคุณแล้ว', 'success');
                                        serviceRow.remove(); // ลบแถวออกจากหน้าจอ
                                        setTimeout(function() {
                                            location.reload(); // รีเฟรชหน้าเว็บ
                                        }, 1500);
                                    } else {
                                        Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถลบบริการนี้ได้', 'error');
                                    }
                                },
                            });

                        }
                    });
                });
                // เมื่อผู้ใช้คลิกปุ่มลบ
                $(document).on('click', '.remove-dress', function() {
                    var dressIndex = $(this).data('index'); // ดึง index ของชุดที่ต้องการลบ

                    // ส่ง AJAX เพื่ออัปเดตข้อมูลในเซสชัน
                    $.ajax({
                        url: 'remove_dress.php', // สร้างไฟล์ PHP ที่จะจัดการการลบชุด
                        type: 'POST',
                        data: {
                            index: dressIndex,
                            bookingH_No: "<?php echo $bookingH_No; ?>" // ส่งข้อมูล index และ bookingH_No ไปยัง PHP
                        },
                        success: function(response) {
                            if (response === 'success') {
                                // ลบแถวออกจากตารางโดยใช้ index
                                $('tr').eq(dressIndex + 1).remove(); // +1 เนื่องจาก tbody tr เริ่มจาก 1
                                updateTotalPrice(); // เรียกฟังก์ชันอัปเดตราคารวม
                            } else {
                                alert('เกิดข้อผิดพลาดในการลบชุด');
                            }
                        },
                        error: function() {
                            alert('เกิดข้อผิดพลาดในการส่งคำขอไปยังเซิร์ฟเวอร์');
                        }
                    });
                });

                function selectDress(dressNo, dressName, bookingHNo) {
                    // แสดง alert ถามว่าต้องการเลือกชุดนี้หรือไม่
                    Swal.fire({
                        title: 'คุณต้องการเลือกชุดนี้ใช่ไหม?',
                        text: dressName,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'ใช่, เลือกชุดนี้'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // ส่งข้อมูลรหัสชุดและ BookingH_No กลับไปที่ search_booking.php
                            window.location.href = 'search_booking.php?selectedDress=' + dressNo + '&BookingH_No=' + bookingHNo;
                        }
                    });
                }


            });


            // เมื่อผู้ใช้เปลี่ยนแพ็คเกจ ให้ปรับราคาอัตโนมัติ
            $(document).on('change', '.packageDropdown', function() {
                var price = $(this).find('option:selected').data('price'); // ดึงราคาจาก data-price
                $(this).closest('.item-row').find('.priceInput').val(price.toFixed(2)); // ตั้งค่าราคาลงในฟิลด์
                calculateGrandTotal(); // คำนวณราคารวมทั้งหมด
            });

            // ฟังก์ชันคำนวณราคาจากจำนวน
            $(document).on('input', '.qtyInput', function() {
                calculateTotal($(this).closest('.item-row')); // คำนวณราคาสำหรับแถวปัจจุบัน
            });

            // ฟังก์ชันคำนวณราคาจากแพ็คเกจและจำนวน
            function calculateTotal(row) {
                var price = parseFloat(row.find('.packageDropdown option:selected').data('price')) || 0;
                var qty = parseInt(row.find('.qtyInput').val()) || 0;
                var total = price * qty;
                row.find('.priceInput').val(total.toFixed(2)); // แสดงราคารวมต่อแพ็คเกจ
                calculateGrandTotal(); // คำนวณราคารวมทั้งหมด
            }

            // ฟังก์ชันคำนวณค่าใช้จ่ายรวมทั้งหมด
            function calculateGrandTotal() {
                var grandTotal = 0;
                $('.priceInput').each(function() {
                    grandTotal += parseFloat($(this).val()) || 0;
                });
                $('#grandTotal').text(grandTotal.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
            }

            // ฟังก์ชันอัพเดทรายละเอียดงาน
            $('#submitForm').click(function(event) {
                event.preventDefault(); // ป้องกันการรีเฟรชหน้า
                var formData = $('#multiForm').serialize(); // เก็บข้อมูลจากฟอร์มทั้งหมดในรูปแบบ string

                console.log(formData); // แสดงข้อมูลที่กำลังจะถูกส่งเพื่อการ debug

                // เรียก AJAX เพื่อส่งข้อมูลไปยัง PHP
                $.ajax({
                    type: 'POST',
                    url: 'save_booking.php',
                    data: formData,
                    dataType: 'json', // คาดหวังผลลัพธ์เป็น JSON
                    success: function(response) {
                        if (response.status === 'success') {
                            // ถ้าสำเร็จให้แสดง SweetAlert และรีโหลดหน้า
                            Swal.fire({
                                title: 'สำเร็จ!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'ตกลง'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload(); // รีโหลดหน้าเมื่อกด OK
                                }
                            });
                        } else {
                            // ถ้ามีข้อผิดพลาด ให้แสดง SweetAlert ข้อผิดพลาด
                            Swal.fire({
                                title: 'เกิดข้อผิดพลาด!',
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: 'ตกลง'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'เกิดข้อผิดพลาด!',
                            text: 'ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่',
                            icon: 'error',
                            confirmButtonText: 'ตกลง'
                        });
                    }
                });
            });

        });

        // เช็คว่าส่งข้อมูลค้นหายัง
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // รับค่าที่ส่งมาจากฟอร์ม
            $BookingH_No = $_POST['BookingH_No'];

            header("Location: search_booking.php?BookingH_No=$BookingH_No");
            exit;
        }
        console.log("Service ID:", serviceId); // เพิ่มบรรทัดนี้เพื่อตรวจสอบค่า ID ก่อนส่ง
    </script>
</head>

<body>
    <div class="container mt-3">
        <!-- ฟอร์มค้นหา -->
        <div class="card">
            <div class="card-header">
                <h4>ค้นหาข้อมูลการจอง</h4>
            </div>
            <div class="card-body">
                <!-- ฟอร์มค้นหา -->
                <form id="searchForm" action="" method="post" class="form-inline">
                    <div class="form-group mx-sm-3 mb-2">
                        <label for="BookingH_No" class="sr-only">หมายเลขการจอง</label>
                        <select name="BookingH_No" id="bookingDropdown" class="form-control">
                            <option value="" disabled selected>เลือกหมายเลขการจอง</option>
                            <?php while ($row_dropdown = mysqli_fetch_assoc($result_dropdown)): ?>
                                <option value="<?= htmlspecialchars($row_dropdown['BookingH_No']) ?>">
                                    <?= htmlspecialchars($row_dropdown['BookingH_No']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <button type="submit" name="search" class="btn btn-primary mb-2 ml-2">ค้นหา</button>
                </form>
            </div>
        </div>
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <!-- แสดงข้อมูลการจอง -->
            <?php $row = mysqli_fetch_assoc($result); ?>
            <div class="card text-center mt-3">
                <div class="card-header">
                    <div class="label"><a href="booking_list.php">รายการจอง</a> >> ดูข้อมูลการจอง</div>
                </div>
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right">
                        <div class="card-title">รายละเอียดการจอง</div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>หมายเลขการจอง</label>
                                <input type="text" class="form-control" readonly value="<?php echo $row['BookingH_No']; ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>ชื่อของลูกค้า</label>
                                <input type="text" class="form-control" readonly value="<?php echo $row['cus_Name']; ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>วันที่เริ่มต้น - สิ้นสุด</label>
                                <input type="text" class="form-control" readonly value="<?php echo ($row['B_DateTime_S'] == '0000-00-00' && $row['B_DateTime_F'] == '0000-00-00') ? ' - ' : htmlspecialchars(date('d/m/Y', strtotime($row['B_DateTime_S']))) . ' - ' . htmlspecialchars(date('d/m/Y', strtotime($row['B_DateTime_F']))) ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>แพ็กเกจ</label>
                                <input type="text" class="form-control mb-2" readonly value="<?php echo $row['Package_Name']; ?>">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>รายละเอียด
                                    <a href="./booking_edit.php?BookingH_No=<?= $row['BookingH_No']; ?>" class="btn btn-sm change-row" style="background: none; border: none;">
                                        <i class="fas fa-edit" style="color: orange;"></i>
                                    </a>
                                </label>
                                <textarea class="form-control" readonly><?php echo $row['B_detail']; ?></textarea>
                            </div>
                        </div>

                        
        
                        <div class="col-md-12">
                            <div class="form-group">
                                <!-- <hr> -->
                                <div class="card-header">
                                    <div class="card-head-row card-tools-still-right">
                                        <div class="card-title">บริการเสริม</div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="booking-details-table" class="display table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th style="text-align: left;">ชื่อบริการ</th>
                                                <th style="text-align: left;">รายละเอียดบริการ</th>
                                                <th>จำนวนแพ็กเกจ</th>
                                                <th>ราคาขายแพ็กเกจ</th>
                                                <th>ลบ</th> <!-- เพิ่มคอลัมน์สำหรับปุ่มลบ -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $totalPrice = 0;
                                            if (mysqli_num_rows($result_details) > 0):
                                                $num = 1;
                                                while ($detail_row = mysqli_fetch_assoc($result_details)):
                                                    $totalPrice += $detail_row['P_Price'];  // เพิ่มราคาในแต่ละแถว
                                            ?>
                                                    <tr id="service-row-<?php echo $num; ?>">
                                                        <td><?= $num ?></td>
                                                        <td style="text-align: left;"><?= $detail_row['Package_Name']; ?></td>
                                                        <td style="text-align: left;"><?= $detail_row['P_Detail']; ?></td>
                                                        <td><?= $detail_row['QTY']; ?></td>
                                                        <td><?= number_format($detail_row['P_Price'], 2); ?> บาท</td>
                                                        <td>
                                                            <button class="btn btn-danger btn-sm remove-service" data-id="<?= $detail_row['BookingD_No']; ?>">
                                                                <i class="fas fa-trash-alt"></i> ลบ
                                                            </button>
                                                        </td>
                                                    </tr>
                                            <?php
                                                    $num++;
                                                endwhile;
                                            endif;
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" class="text-right"><strong>รวมทั้งหมด:</strong></td>
                                                <td><strong><?= number_format($totalPrice, 2); ?> บาท</strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>

                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">บริการเสริมเพิ่มเติม</div>
                            </div>
                            <div class="card-body">
                                <!-- body -->
                                <div class="row">

                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <!-- <hr> -->
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="card-header">
                                        <div class="card-head-row card-tools-still-right">
                                            <div class="card-title">รายการชุด</div>
                                        </div>
                                    </div>
                                    <a href="DressReturnList.php?BookingH_No=<?= $bookingH_No ?>" class="btn btn-secondary btn-sm select-dress " data-booking-no="<?= $bookingH_No ?>">
                                        เพิ่มรายการชุด
                                    </a>
                                </div>


                                <div class="table-responsive">
                                    <table id="booking-details-table" class="display table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>รหัสชุด</th>
                                                <th>ชื่อชุด</th>

                                                <th>ไซส์</th>

                                                <th>ลบ</th> <!-- ปุ่มลบ -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $totalPrice = 0;  // ตัวแปรสำหรับราคารวม
                                            $num = 1;

                                            // ดึงข้อมูลจากการ query SQL
                                            $sql_details = "SELECT
                                                                *
                                                                FROM
                                                                dressed
                                                                INNER JOIN `return` ON `return`.Dressed_No = dressed.Dressed_No
                                                                INNER JOIN booking_h ON `return`.BookingH_No = booking_h.BookingH_No

                                                            WHERE `Return`.BookingH_No = '$BookingH_No'";

                                            $result_details = mysqli_query($conn, $sql_details);

                                            if (mysqli_num_rows($result_details) > 0):
                                                while ($detail_row = mysqli_fetch_assoc($result_details)):
                                                    $totalPrice += $detail_row['D_price'];  // รวมราคารวมแต่ละแถว
                                            ?>
                                                    <tr id="service-row-<?php echo $detail_row['Return_No']; ?>">
                                                        <td><?= $num ?></td>
                                                        <td><?= $detail_row['Dressed_No']; ?></td>
                                                        <td style="text-align: left;"><?= $detail_row['Dressed_Name']; ?></td>
                                                        <td><?= $detail_row['size']; ?></td>
                                                        <td>
                                                            <button class="btn btn-danger btn-sm remove-service" data-id="<?= $detail_row['Return_No']; ?>">
                                                                <i class="fas fa-trash-alt"></i> ลบ
                                                            </button>
                                                        </td>
                                                    </tr>

                                            <?php
                                                    $num++;
                                                endwhile;
                                            endif;
                                            ?>
                                        </tbody>

                                    </table>

                                </div>
                            </div>
                        </div>




                        <!-- ใส่สคริปต์เพื่อสร้าง Event -->
                        <script>
                            $(document).ready(function() {
                                // ผูกเหตุการณ์ 'click' กับปุ่มลบ
                                $(document).on('click', '.remove-service', function() {
                                    var returnNo = $(this).data('id'); // ดึง Return_No ของชุดที่ต้องการลบ
                                    console.log(returnNo); // ตรวจสอบว่า returnNo ถูกต้องหรือไม่

                                    // แสดงการยืนยันว่าต้องการลบจริงหรือไม่
                                    Swal.fire({
                                        title: 'คุณแน่ใจหรือไม่?',
                                        text: 'คุณต้องการลบชุดนี้ออกจากรายการหรือไม่?',
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'ใช่, ลบเลย!',
                                        cancelButtonText: 'ยกเลิก'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            // ถ้าผู้ใช้กดยืนยันการลบ ส่งคำขอ AJAX ไปยังไฟล์ PHP
                                            $.ajax({
                                                url: 'remove_dress.php', // ไฟล์ PHP สำหรับจัดการการลบ
                                                type: 'POST',
                                                data: {
                                                    returnNo: returnNo
                                                },
                                                success: function(response) {
                                                    let data = JSON.parse(response);
                                                    if (data.status === 'success') {
                                                        Swal.fire('ลบสำเร็จ!', 'ชุดถูกลบออกจากรายการแล้ว.', 'success');
                                                        // ลบแถวออกจากตารางโดยไม่ต้องรีเฟรชหน้า
                                                        $('#service-row-' + returnNo).remove();
                                                    } else {
                                                        Swal.fire('เกิดข้อผิดพลาด!', data.message, 'error');
                                                    }
                                                },
                                                error: function(xhr, status, error) {
                                                    Swal.fire('เกิดข้อผิดพลาด!', 'ไม่สามารถลบชุดได้ กรุณาลองใหม่', 'error');
                                                }
                                            });
                                        }
                                    });
                                });
                            });
                        </script>



                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="card-title">บริการเสริมเพิ่มเติม</div>
                </div>
                <div class="card-body">
                    <!-- body -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <!-- <hr> -->
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="card-header">
                                        <div class="card-head-row card-tools-still-right">
                                            <div class="card-title">พนักงาน</div>
                                        </div>
                                    </div>
                                    <a href="EmployeeList.php?BookingH_No=<?= $bookingH_No ?>" class="btn btn-secondary btn-sm select-employee" data-booking-no="<?= $bookingH_No ?>">
                                        เพิ่มพนักงาน
                                    </a>
                                </div>

                                <div class="table-responsive">
                                    <table id="employee-details-table" class="display table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>รหัสพนักงาน</th>
                                                <th>ชื่อพนักงาน</th>
                                                <th>เบอร์โทร</th>
                                                <th>ลบ</th> <!-- ปุ่มลบ -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $num = 1;

                                            // ดึงข้อมูลพนักงานที่ถูกเลือกสำหรับการจองจากตาราง Booking_Em
                                            $sql_employee_details = "SELECT
                                                *
                                            FROM
                                                employeetype
                                            INNER JOIN Employee ON employeetype.EmployeeType_No = Employee.EmployeeType_No";

                                            $result_employee_details = mysqli_query($conn, $sql_employee_details);

                                            if (mysqli_num_rows($result_employee_details) > 0):
                                                while ($employee_row = mysqli_fetch_assoc($result_employee_details)):
                                            ?>
                                                    <tr id="employee-row-<?php echo $employee_row['BookingE_No']; ?>">
                                                        <td><?= $num ?></td>
                                                        <td><?= $employee_row['employee_No']; ?></td>
                                                        <td style="text-align: left;"><?= $employee_row['em_Name']; ?></td>
                                                        <td><?= $employee_row['EmployeeType_Name']; ?></td>
                                                        <td>
                                                            <button class="btn btn-danger btn-sm remove-employee" data-id="<?= $employee_row['BookingE_No']; ?>">
                                                                <i class="fas fa-trash-alt"></i> ลบ
                                                            </button>
                                                        </td>
                                                    </tr>

                                            <?php
                                                    $num++;
                                                endwhile;
                                            endif;
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <script>
                    $(document).ready(function() {
                        // เมื่อคลิกปุ่มลบพนักงาน
                        $(document).on('click', '.remove-employee', function() {
                            var bookingENo = $(this).data('id'); // ดึง BookingE_No ของพนักงานที่ต้องการลบ
                            console.log(bookingENo);

                            // แสดงการยืนยันว่าต้องการลบจริงหรือไม่
                            Swal.fire({
                                title: 'คุณแน่ใจหรือไม่?',
                                text: 'คุณต้องการลบพนักงานนี้ออกจากรายการหรือไม่?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'ใช่, ลบเลย!',
                                cancelButtonText: 'ยกเลิก'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // ถ้าผู้ใช้กดยืนยันการลบ ส่งคำขอ AJAX ไปยังไฟล์ PHP
                                    $.ajax({
                                        url: 'remove_employee.php', // ไฟล์ PHP สำหรับจัดการการลบ
                                        type: 'POST',
                                        data: {
                                            bookingENo: bookingENo
                                        },
                                        success: function(response) {
                                            let data = JSON.parse(response);
                                            if (data.status === 'success') {
                                                Swal.fire('ลบสำเร็จ!', 'พนักงานถูกลบออกจากรายการแล้ว.', 'success');
                                                // ลบแถวออกจากตารางโดยไม่ต้องรีเฟรชหน้า
                                                $('#employee-row-' + bookingENo).remove();
                                            } else {
                                                Swal.fire('เกิดข้อผิดพลาด!', data.message, 'error');
                                            }
                                        },
                                        error: function(xhr, status, error) {
                                            Swal.fire('เกิดข้อผิดพลาด!', 'ไม่สามารถลบพนักงานได้ กรุณาลองใหม่', 'error');
                                        }
                                    });
                                }
                            });
                        });
                    });
                </script>
            </div>
        <?php elseif (isset($_POST['search'])): ?>
            <!-- กรณีไม่พบข้อมูลการจอง -->
            <div class="alert alert-danger mt-3">ไม่พบข้อมูลการจอง</div>
        <?php endif; ?>
        <div class="card">
            <div class="card-header">
                <div class="card-title">บริการเสริมเพิ่มเติม</div>
            </div>
            <div class="card-body">
                <!-- ข้อมูลแพ็คเกจ -->
                <div class="form-group">
                    <form id="multiForm">
                        <input type="hidden" name="BookingH_No" value="<?= $selectedBooking; ?>">
                        <div class="row item-row mb-2">
                            <div class="form-group col-md-3">
                                <label for="Package_No_${itemIndex}">รหัสแพ็คเกจ</label>
                                <select name="Package_No[]" id="Package_No_${itemIndex}" class="form-control packageDropdown">
                                    <option value="" disabled selected>เลือกแพ็คเกจ</option>
                                    <?php
                                    mysqli_data_seek($result_packages, 0); // รีเซ็ตผลลัพธ์ก่อนแสดงแพ็กเกจ
                                    while ($row_package = mysqli_fetch_assoc($result_packages)):
                                    ?>
                                        <option value="<?= htmlspecialchars($row_package['Package_No']) ?>" data-price="<?= $row_package['P_Price'] ?>">
                                            <?= htmlspecialchars($row_package['Package_Name']) ?>
                                        </option>
                                    <?php endwhile; ?>

                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="QTY_${itemIndex}">จำนวน</label>
                                <input type="number" class="form-control qtyInput" name="QTY[]" id="QTY_${itemIndex}" placeholder="จำนวน">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="Price_${itemIndex}">ราคา</label>
                                <input type="text" class="form-control priceInput" name="Price[]" id="Price_${itemIndex}" placeholder="ราคา">
                            </div>
                        </div>
                        <div id="itemsContainer"></div>
                        <button type="button" class="btn btn-primary mt-2" id="addItem">+</button>
                        <div class="d-flex justify-content-end mt-4">
                            <h5 class="me-3">ค่าใช้จ่ายรวม: <span id="grandTotal" class="h2">0.00</span> บาท</h5>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" id="submitForm" onclick="" class="btn btn-primary" style="margin-right: 0.2rem;">บันทึกรายการ</button>
                            <button class="btn btn-danger">
                                <a href="./booking_list.php" class="text-white">
                                    ยกเลิก
                                </a>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</body>
<script>
    $(document).ready(function() {
        $("#basic-datatables").DataTable({});
    });
</script>

</html>