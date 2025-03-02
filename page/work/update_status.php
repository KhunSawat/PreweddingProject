<?php
include("../../config/dbcon.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $BookingH_No = mysqli_real_escape_string($conn, $_POST['BookingH_No']);
    $work_status = mysqli_real_escape_string($conn, $_POST['work_status']);
    $payment_status = mysqli_real_escape_string($conn, $_POST['payment_status']);

    // ตรวจสอบว่ามีข้อมูลที่ต้องการ
    if (!empty($BookingH_No) && !empty($work_status) && !empty($payment_status)) {
        // อัปเดตข้อมูลสถานะ
        $sql_update = "UPDATE booking_h 
                       SET work_status_No = '$work_status', Payment_status_No = '$payment_status' 
                       WHERE BookingH_No = '$BookingH_No'";
        if (mysqli_query($conn, $sql_update)) {
            echo json_encode(['status' => 'success', 'message' => 'ปรับปรุงสถานะสำเร็จ']);
        } else {
            echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ข้อมูลไม่ครบถ้วน']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
