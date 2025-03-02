<?php
include("../../config/dbcon.php");

// ตรวจสอบการส่งค่าผ่าน GET หรือ POST
$bookingH_No = isset($_GET['BookingH_No']) ? $_GET['BookingH_No'] : '';

// ดึงข้อมูลพนักงานที่ถูกเลือกสำหรับการจองจากตาราง Employee และ EmployeeType
$sql_employee = "SELECT Employee.*, EmployeeType.EmployeeType_Name,
        CASE WHEN Booking_Em.Employee_No IS NOT NULL THEN 1 ELSE 0 END AS is_selected
    FROM Employee
    INNER JOIN EmployeeType ON Employee.EmployeeType_No = EmployeeType.EmployeeType_No
    LEFT JOIN Booking_Em ON Employee.Employee_No = Booking_Em.Employee_No
    AND Booking_Em.BookingH_No = '$bookingH_No'
";

$result_employee = mysqli_query($conn, $sql_employee);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Employee</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- รวม jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../assets/css/plugins.min.css" />
    <link rel="stylesheet" href="../../assets/css/kaiadmin.min.css" />
</head>

<body>
    <div class="container mt-5">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="card px-4">
                        <div class="card-header text-center">
                            <h4 class="card-title mt-5">เลือกพนักงาน</h4>
                        </div>
                        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                            <div class="d-flex w-100 justify-content-between py-2 py-md-0">

                                <a href="search_booking.php?BookingH_No=<?= $bookingH_No ?>" class="btn btn-label-info btn-round ms-4 mt-3">
                                    <i class="fas fa-home"></i> ย้อนกลับ
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            if (mysqli_num_rows($result_employee) > 0) {
                                while ($row = mysqli_fetch_assoc($result_employee)) {
                                    echo '
                                        <div class="col-md-4 mb-4">
                                            <div class="card h-100 shadow-sm">
                                                <div class="card-body d-flex flex-column align-items-center">
                                                    <div class="d-flex justify-content-center mb-3">
                                                        <img src="../../assets/pic/' . $row['em_Pic'] . '" class="rounded-circle" style="width: 100%; max-width: 150px;" alt="employee image">
                                                    </div>
                                                    <div class="mt-auto text-center"> <!-- ส่วนนี้จะทำให้ข้อความชิดล่าง -->
                                                        <h5 class="card-title">' . htmlspecialchars($row['em_Name']) . '</h5>
                                                        <p class="card-text mb-1">ประเภทพนักงาน: ' . htmlspecialchars($row['EmployeeType_Name']) . '</p>
                                                        <p class="card-text">เบอร์โทร: ' . htmlspecialchars($row['em_Phone']) . '</p>
                                                    </div>
                                                    <div class="mt-auto">
                                                        <a class="btn btn-success w-100"
                                                           onclick="selectEmployee(\'' . $row['employee_No'] . '\', \'' . htmlspecialchars($row['em_Name']) . '\', \'' . $bookingH_No . '\')">
                                                            <i class="fas fa-check-circle"></i> เลือกพนักงาน
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    ';
                                }
                            } else {
                                echo '<p class="text-center">ไม่พบพนักงานที่เลือกในระบบ</p>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function selectEmployee(employeeNo, employeeName, bookingHNo) {
            Swal.fire({
                title: 'คุณต้องการเลือกพนักงานคนนี้ใช่ไหม?',
                text: employeeName,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน'
            }).then((result) => {
                if (result.isConfirmed) {
                    // ส่งข้อมูลไปที่ไฟล์ save_employee.php ด้วย AJAX
                    $.ajax({
                        url: 'save_employee.php',
                        type: 'POST',
                        data: {
                            employeeNo: employeeNo,
                            bookingHNo: bookingHNo
                        },
                        success: function(response) {
                            let data = JSON.parse(response); // แปลงข้อมูลที่ได้รับกลับเป็น JSON
                            if (data.status === 'success') {
                                Swal.fire(
                                    'สำเร็จ!',
                                    'พนักงานถูกบันทึกลงในตารางจองคิวพนักงานแล้ว',
                                    'success'
                                ).then(() => {
                                    window.location.href = 'search_booking.php?BookingH_No=' + bookingHNo;
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
