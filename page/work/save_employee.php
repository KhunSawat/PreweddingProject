<?php
include("../../config/dbcon.php");

// ตรวจสอบว่าข้อมูลถูกส่งมาผ่าน POST
if (isset($_POST['employeeNo']) && isset($_POST['bookingHNo'])) {
    $employeeNo = mysqli_real_escape_string($conn, $_POST['employeeNo']);
    $bookingHNo = mysqli_real_escape_string($conn, $_POST['bookingHNo']);

    // ตรวจสอบว่าพนักงานยังไม่ถูกเพิ่มในรายการการจองนี้
    $check_sql = "SELECT * FROM Booking_Em WHERE Employee_No = '$employeeNo' AND BookingH_No = '$bookingHNo'";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) == 0) {
        // เพิ่มข้อมูลพนักงานในการจองคิวพนักงาน
        $insert_sql = "INSERT INTO Booking_Em (BookingH_No, Employee_No) VALUES ('$bookingHNo', '$employeeNo')";
        
        if (mysqli_query($conn, $insert_sql)) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถบันทึกข้อมูลได้']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'พนักงานนี้ถูกเพิ่มแล้วในรายการการจองนี้']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ข้อมูลไม่ครบถ้วน']);
}
?>
