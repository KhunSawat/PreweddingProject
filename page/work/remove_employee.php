<?php
include("../../config/dbcon.php");

if (isset($_POST['bookingENo'])) {
    $bookingENo = mysqli_real_escape_string($conn, $_POST['bookingENo']);

    // ลบพนักงานจากตาราง Booking_Em ตาม BookingE_No
    $delete_sql = "DELETE FROM Booking_Em WHERE BookingE_No = '$bookingENo'";
    if (mysqli_query($conn, $delete_sql)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ไม่สามารถลบพนักงานได้']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ข้อมูลไม่ครบถ้วน']);
}
?>
