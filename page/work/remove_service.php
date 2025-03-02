<?php
include("../../config/dbcon.php");

if (isset($_POST['BookingD_No'])) {
    $bookingD_No = mysqli_real_escape_string($conn, $_POST['BookingD_No']);

    // ลบข้อมูลจากฐานข้อมูล
    $sql_delete = "DELETE FROM booking_d WHERE BookingD_No = $bookingD_No";

    if (mysqli_query($conn, $sql_delete)) {
        echo "success";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "BookingD_No ไม่ถูกส่งมา";
}


?>
