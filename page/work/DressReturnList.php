<?php
include("../../config/dbcon.php");

$filter_type = isset($_GET['filter_type']) ? $_GET['filter_type'] : ''; // กำหนดค่า filter_type จาก GET

// เขียน SQL ที่จะใช้สำหรับกรอง
$sql = "SELECT DISTINCT Dressed.*
        FROM Dressed
        LEFT JOIN `Return` ON Dressed.Dressed_No = `Return`.Dressed_No
        WHERE (`Return`.Return_Status_No NOT IN ('00', '01') OR `Return`.Return_Status_No IS NULL)";

// ถ้ามีการเลือกประเภท (filter_type) เพิ่มเงื่อนไขในการกรอง
if (!empty($filter_type)) {
    $sql .= " AND Dressed.Dtype_No = '$filter_type'";
}


$result = mysqli_query($conn, $sql);

// ตรวจสอบการส่งค่าผ่าน GET หรือ POST
$bookingH_No = isset($_GET['BookingH_No']) ? $_GET['BookingH_No'] : '';

// ถ้าต้องการให้ค่ามาจาก POST ด้วย สามารถเพิ่มการตรวจสอบได้
// $bookingH_No = isset($_POST['BookingH_No']) ? $_POST['BookingH_No'] : $bookingH_No;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Dress</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- รวม jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../assets/css/plugins.min.css" />
    <link rel="stylesheet" href="../../assets/css/kaiadmin.min.css" />
</head>

<body>
    <div class="container">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="card px-4">
                        <div class="card-header">
                            <h4 class="card-title text-center mt-5">เลือกชุด</h4>
                        </div>
                        <div class="d-flex align-items-center justify-content-between pt-2 pb-4">
                            <a href="search_booking.php?BookingH_No=<?= $bookingH_No ?>" class="btn btn-label-info btn-round">
                                <i class="fas fa-home"></i> ย้อนกลับ
                            </a>
                            <div class="d-flex align-items-center ms-auto">
                                <label for="filterForm" class="mx-2">กรองชุด</label>
                                <form method="GET" class="d-flex align-items-center" id="filterForm" name="filterForm">
                                    <select name="filter_type" class="form-select me-3" style="width: 150px;" onchange="this.form.submit()">
                                        <option value="" <?= $filter_type == '' ? 'selected' : '' ?>>ทั้งหมด</option>
                                        <option value="01" <?= $filter_type == '01' ? 'selected' : '' ?>>ชุดราตรี</option>
                                        <option value="02" <?= $filter_type == '02' ? 'selected' : '' ?>>ชุดสูท</option>
                                        <option value="03" <?= $filter_type == '03' ? 'selected' : '' ?>>ชุดไทย</option>
                                    </select>
                                    <input type="hidden" name="BookingH_No" value="<?php echo $bookingH_No; ?>">
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '
                                            <div class="col-md-4">
                                                <div class="card mb-4 d-flex justify-content-center">
                                                    <div class="d-flex justify-content-center">
                                                        <img src="../../assets/pic/dress/' . $row['D_Pic1'] . '" class="card-img-top" alt="' . $row['Dressed_Name'] . '" style="width: 100%; max-width: 150px;">
                                                    </div>
                                                    <div class="card-body">
                                                        <h5 class="card-title">' . $row['Dressed_Name'] . '</h5>
                                                        <p class="card-text">ขนาด: ' . $row['size'] . '</p>
                                                        <div class="h4 text-end me-5"><span>ราคาเช่า: </span><strong>' . $row['D_price'] . '</strong> บาท</div>
                                                        <div class="d-flex justify-content-end py-2 py-md-0">
                                                            <a href="DressDetail.php?Dressed_No=' . $row['Dressed_No'] . '&BookingH_No=' . $bookingH_No . '" class="btn btn-label-info btn-round ms-4 mt-3">
                                                                <i class="fas fa-info-circle"></i> รายละเอียดชุด
                                                            </a>
                                                            <a class="btn btn-label-success btn-round me-4 mt-3"
                                                                onclick="selectDress(\'' . $row['Dressed_No'] . '\', \'' . $row['Dressed_Name'] . '\', \'' . $bookingH_No . '\')">
                                                                <i class="fas fa-check-circle"></i> เลือกชุด
                                                            </a>
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

    <script>
        function selectDress(dressNo, dressName, bookingHNo) {
            Swal.fire({
                title: 'คุณต้องการเลือกชุดนี้ใช่ไหม?',
                text: dressName,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน'
            }).then((result) => {
                if (result.isConfirmed) {
                    // ส่งข้อมูลไปที่ไฟล์ save_return.php ด้วย AJAX
                    $.ajax({
                        url: 'save_return.php',
                        type: 'POST',
                        data: {
                            dressedNo: dressNo,
                            bookingHNo: bookingHNo
                        },
                        success: function(response) {
                            let data = JSON.parse(response); // แปลงข้อมูลที่ได้รับกลับเป็น JSON
                            if (data.status === 'success') {
                                Swal.fire(
                                    'สำเร็จ!',
                                    'ชุดถูกบันทึกลงในตารางเบิกคืนแล้ว',
                                    'success'
                                ).then(() => {
                                    window.location.href = 'DressReturnList.php?BookingH_No=' + bookingHNo;
                                });
                            } else {
                                Swal.fire(
                                    'เกิดข้อผิดพลาด!',
                                    data.message,
                                    'error'
                                );
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire(
                                'เกิดข้อผิดพลาด!',
                                'ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่',
                                'error'
                            );
                        }
                    });
                }
            });
        }
    </script>
</body>

</html>