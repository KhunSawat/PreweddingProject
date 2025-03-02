<?php
session_start();
include("../../config/dbcon.php");

// ตรวจสอบว่ามีข้อมูลถูกส่งมาจากฟอร์มหรือไม่
if (isset($_POST['Package_No']) && isset($_POST['QTY']) && isset($_POST['Price']) && isset($_POST['BookingH_No'])) {

    $packageNos = $_POST['Package_No']; // รับค่ารหัสแพ็กเกจ
    $qtys = $_POST['QTY']; // รับค่าจำนวน
    $prices = $_POST['Price']; // รับค่าราคา
    $bookingH_No = mysqli_real_escape_string($conn, $_POST['BookingH_No']);

    // ตรวจสอบข้อมูลว่าไม่เป็นค่าว่าง
    if (!empty($packageNos) && !empty($qtys) && !empty($prices)) {
        // เริ่มการทำงานแบบ transaction เพื่อให้การทำงานมีความสมบูรณ์
        mysqli_begin_transaction($conn);
        try {
            for ($i = 0; $i < count($packageNos); $i++) {
                $packageNo = mysqli_real_escape_string($conn, $packageNos[$i]);
                $qty = mysqli_real_escape_string($conn, $qtys[$i]);
                $price = mysqli_real_escape_string($conn, $prices[$i]);

                // ตรวจสอบว่าไม่มีค่าว่าง
                if (!empty($packageNo) && !empty($qty) && !empty($price)) {
                    // เพิ่มข้อมูลลงในฐานข้อมูล
                    $sql = "INSERT INTO booking_d (BookingH_No, Package_No, QTY, Price) VALUES ('$bookingH_No', '$packageNo', '$qty', '$price')";

                    if (!mysqli_query($conn, $sql)) {
                        throw new Exception("เกิดข้อผิดพลาด: " . mysqli_error($conn));
                    }
                } else {
                    throw new Exception("ข้อมูลบางส่วนไม่สมบูรณ์ในรายการที่ $i");
                }
            }

            // commit การทำงานเมื่อทุกอย่างสมบูรณ์
            mysqli_commit($conn);
            echo json_encode(['status' => 'success', 'message' => 'บันทึกข้อมูลเรียบร้อยแล้ว']);
        } catch (Exception $e) {
            // rollback หากเกิดข้อผิดพลาด
            mysqli_rollback($conn);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ข้อมูลไม่ครบถ้วน กรุณากรอกข้อมูลให้ครบทุกช่อง']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ไม่มีข้อมูลที่ส่งมาบันทึก']);
}
?>
