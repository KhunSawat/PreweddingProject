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


ob_start(); // เริ่มต้นการจับ HTML
?>


<!DOCTYPE html>
<html>

<head>
    <title>รายงานการจองคิว</title>
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

        .image-gallery {
            display: flex;
            gap: 16px;
        }

        .gallery-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .gallery-image:hover {
            transform: scale(2);
        }

        .accordion-button:focus {
            box-shadow: none;
        }
    </style>
</head>

<body>
    <script>
        function getSearchParam() {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get('search') || ''; // ถ้าไม่มีค่าจะคืนค่าว่าง
        }

        document.addEventListener("DOMContentLoaded", function() {
            var searchValue = getSearchParam(); // ดึงค่าค้นหา

            if (searchValue !== '') {
                document.querySelector('#basic-datatables_filter input').value = searchValue; // ตั้งค่าช่องค้นหา
            }
        });
    </script>
    <?php
    //กำหนดชื่อตาราง
    $table = "Expense";
    $sql_select = "SELECT * FROM $table ORDER BY Expense_No";
    $result = mysqli_query($conn, $sql_select);
    ?>
    <h1 style="text-align: center;">รายงานค่าใช้จ่าย</h1>
    <table
        id="basic-datatables"
        class="display table table-striped table-hover">
        <thead>
            <tr>
                <th>ลำดับ</th>
                <th>รหัสรายจ่าย</th>
                <th>ชื่อรายจ่าย</th>
                <th>ยอดจ่ายรวม</th>
                <th>วันที่เวลา</th>
                <th>รายละเอียด</th>
            </tr>
        </thead>
        <tbody>
            <?php

            if (mysqli_num_rows($result) > 0) {
                $num = 1;
                //ให้เก็บข้อมูลที่ได้ ไว้ในตัวแปรอาร์เรย์ $row  
                while ($row = mysqli_fetch_array($result)) {
            ?>
                    <tr>
                        <th scope='row'><?= $num ?></th>
                        <td><?= $row['Expense_No']; ?></td>
                        <td><?= $row['Expense_Name']; ?></td>
                        <td><?= number_format($row['Expenses'], 2); ?></td>
                        <td><?= $row['E_DateTime']; ?></td>
                        <td class="d-flex justify-content-center align-items-center" style="height: 150px;">
                            <?= $row['E_details']; ?>
                        </td>
                       
                    </tr>
            <?php
                    //ปิด loop while 
                    $num++;
                }
            }
            ?>
        </tbody>


        <?php

        if (isset($_GET['Expense_No']) && isset($_GET['action']) && $_GET['action'] == 'delete') {
            $Expense_No = mysqli_real_escape_string($conn, $_GET['Expense_No']);
            $sql_delete = "DELETE FROM Expense WHERE Expense_No='$Expense_No'";

            if (mysqli_query($conn, $sql_delete)) {
                echo "<script>
                              Swal.fire({
                                  title: 'สำเร็จ!',
                                  text: 'ลบข้อมูลรหัส $Expense_No สำเร็จแล้ว',
                                  icon: 'success',
                                  confirmButtonText: 'OK',
                                  customClass: {
                                      confirmButton: 'btn btn-success',
                                  }
                              }).then((result) => {
                                  if (result.isConfirmed) {
                                      window.location.href = '$_SERVER[PHP_SELF]';
                                  }
                              });
                          </script>";
            } else {
                echo "Error deleting record: " . mysqli_error($conn);
            }
        }
        ?>

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
$mpdf->Output('ReportEm.pdf');
$mpdf->Output('ReportEm.pdf', 'I');
ob_end_flush();
?>