<?php
include('../../config/dbcon.php');
            // คำสั่งที่ใช้ดึงข้อมูลจากฐานข้อมูล (ถ้ามี)
$query = "SELECT * FROM customers"; 
$result = mysqli_query($conn, $query);
$html = '<h1>ข้อมูลจากฐานข้อมูล</h1><table border="1"><tr><th>ID</th><th>Name</th><th>Email</th></tr>';

while($row = mysqli_fetch_array($result)) {
    $html .= '<tr><td>' . $row[0] . '</td><td>' . $row[1] . '</td><td>' . $row[2] . '</td></tr>';

}
$html .= '</table>';

// สร้าง PDF ด้วย mPDF
$mpdf->WriteHTML($html);
$mpdf->Output('ReportCus.pdf', 'I'); // 'I' เพื่อให้แสดงผลในเบราว์เซอร์
?>