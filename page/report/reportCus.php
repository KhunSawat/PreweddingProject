<?php
include('../../config/dbcon.php');
// Require composer autoload
require_once __DIR__ . '/vendor/autoload.php';

// เพิ่ม Font ให้กับ mPDF
$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];
$mpdf = new \Mpdf\Mpdf([
  'tempDir' => __DIR__ . '/tmp',
  'fontdata' => $fontData + [
    'sarabun' => [
      'R' => 'THSarabunNew.ttf',
      'I' => 'THSarabunNew Italic.ttf',
      'B' => 'THSarabunNew Bold.ttf',
      'BI' => 'THSarabunNew BoldItalic.ttf'
    ]
  ],
]);

ob_start(); // Start get HTML code
?>

<!DOCTYPE html>
<html>

<head>
  <title>รายงานลูกค้า</title>
  <link href="https://fonts.googleapis.com/css?family=Sarabun&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: sarabun;
    }

    table {
      border-collapse: collapse;
      width: 100%;
    }

    td,
    th {
      border: 1px solid #dddddd;
      text-align: left;
      padding: 8px;
    }

    tr:nth-child(even) {
      background-color: #dddddd;
    }
  </style>
</head>

<body>

  <h1 style="text-align: center;">รายงานลูกค้า</h1>
  <table
    id="basic-datatables"
    class="display table table-striped table-hover">
    <thead>
      <tr>
        <th>ลำดับ</th>
        <th>รหัสลูกค้า</th>
        <th>ชื่อ</th>
        <th>ที่อยู่</th>
        <th>เบอร์โทรศัพท์</th>
        <th class="text-end">เพศ</th>
      </tr>
    </thead>
    <tbody>
      <?php

      //กำหนดชื่อตาราง
      $table = "customers";
      //คำสั่ง select ข้อมูลลำดับตามรหัสสมาชิกจากมากไปหาน้อย
      $sql_select = "SELECT * FROM $table ORDER BY customer_no";
      //สั่งให้ qury ทำงาน
      $result = mysqli_query($conn, $sql_select);
      //เมื่อได้ผลการ query จากตัวแปร $result 
      //ถ้า record ที่ query ได้มีจำนวนมากกว่า 0
      if (mysqli_num_rows($result) > 0) {
        $num = 1;
        //ให้เก็บข้อมูลที่ได้ ไว้ในตัวแปรอาร์เรย์ $row  
        while ($row = mysqli_fetch_array($result)) {
      ?>
          <tr>
            <th scope='row'><?= $num ?></th>
            <td><?= $row[0]; ?></td>
            <td><?= $row[1]; ?></td>
            <td><?= $row[2]; ?></td>
            <td><?= $row[4]; ?></td>
            <td class="text-end"><?php echo $row[5] == "M" ? "ชาย" : "หญิง"; ?></td>
          </tr>
      <?php
          //ปิด loop while 
          $num++;
        }
      }
      ?>
    </tbody>
  </table>
  <?php
  date_default_timezone_set('Asia/Bangkok');
  $thai_months = [
    '',
    'มกราคม',
    'กุมภาพันธ์',
    'มีนาคม',
    'เมษายน',
    'พฤษภาคม',
    'มิถุนายน',
    'กรกฎาคม',
    'สิงหาคม',
    'กันยายน',
    'ตุลาคม',
    'พฤศจิกายน',
    'ธันวาคม'
  ];

  $day = date('j');
  $month = $thai_months[date('n')];
  $year = date('Y') + 543; // แปลงเป็นปี พ.ศ.
  ?>
  <p style="text-align: right;">
    วันที่พิมพ์รายงาน <?php echo date('d/m/Y'); ?>
  </p>

</body>

</html>

<?php
$html = ob_get_contents();

// สร้างไฟล์ PDF และแสดงทันทีในเบราว์เซอร์
$mpdf->WriteHTML($html);
$mpdf->Output('ReportCus.pdf', 'I'); // 'I' จะทำให้เปิดไฟล์ในเบราว์เซอร์
ob_end_flush();
?>