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
    <title>รายงานแพ็กเกจบริการ</title>
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

    <h1 style="text-align: center;">รายงานแพ็กเกจบริการ</h1>
    <table
        id="basic-datatables"
        class="display table table-striped table-hover">
        <thead>
            <tr>
                <th>ลำดับ</th>
                <th>รหัสบริการ</th>
                <th>ชื่อบริการ</th>
                <th>รายละเอียดบริการ</th>
                <th>ราคา</th>
                <th>ประเภทบริการ</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $table = "Packages";
            $tablejoin = "PackageType";
            $sql_select = "SELECT $table.Package_No, $table.Package_Name, $table.P_Detail, $table.P_Price, $tablejoin.Ptype_Name
                           FROM $table 
                           INNER JOIN $tablejoin ON $table.Ptype_No = $tablejoin.Ptype_No
                           ORDER BY $table.Package_No ";

            // สั่งให้ query ทำงาน
            $result = mysqli_query($conn, $sql_select);
            // ตรวจสอบว่ามีข้อมูลที่ได้จาก query หรือไม่
            if (mysqli_num_rows($result) > 0) {
                $num = 1;
                // ลูปแสดงข้อมูลที่ได้จาก query
                while ($row = mysqli_fetch_array($result)) {
            ?>
                    <tr>
                        <th scope='row'><?= $num ?></th>
                        <td><?= $row['Package_No']; ?></td>
                        <td><?= $row['Package_Name']; ?></td>
                        <td><?= $row['P_Detail']; ?></td>
                        <td><?= number_format($row['P_Price'], 2); ?> </td>
                        <td><?= $row['Ptype_Name']; ?></td>

                    </tr>
            <?php
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
// $mpdf->Output('ReportEm.pdf');
$mpdf->Output('ReportEm.pdf', 'I');
ob_end_flush();
?>