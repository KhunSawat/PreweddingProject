<?php
include("../../config/dbcon.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับข้อมูลที่ส่งมาจาก AJAX
    $dressed_No = $_POST['dressedNo'];
    $bookingH_No = $_POST['bookingHNo'];

    // สร้าง Return_No แบบสุ่ม (หรือปรับตามความต้องการ)
    $return_No = uniqid('RTN');
    $return_Status_No = '00'; // สถานะ "00" ที่กำหนดไว้

    // บันทึกข้อมูลลงในตาราง Return
    $sql = "INSERT INTO `Return` (Return_No, Return_Status_No, Dressed_No, BookingH_No) 
            VALUES ('$return_No', '$return_Status_No', '$dressed_No', '$bookingH_No')";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . mysqli_error($conn)]);
    }
}
?>
