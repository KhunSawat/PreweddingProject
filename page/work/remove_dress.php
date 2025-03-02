<?php
include("../../config/dbcon.php");

if (isset($_POST['returnNo'])) {
    $returnNo = mysqli_real_escape_string($conn, $_POST['returnNo']);
    
    // ลบข้อมูลจากฐานข้อมูล
    $sql_delete = "DELETE FROM `return` WHERE Return_No = '$returnNo'";
    if (mysqli_query($conn, $sql_delete)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการลบ']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ไม่มีข้อมูล Return_No']);
}
?>
