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
    <title>รายงานชุด</title>
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

    <h1 style="text-align: center;">รายงานชุด</h1>
    <table
        id="basic-datatables"
        class="display table table-striped table-hover">
        <thead>
            <tr>
                <th>ลำดับ</th>
                <th>รหัสชุด</th>
                <th>ชื่อ</th>
                <th>สี</th>
                <th>รูป</th>
                <th>ขนาด</th>
                <th class="d-none">ประเภท</th>
                <th>ราคา</th>
                <th>รายละเอียด</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $table = "dressed";
            $jointable = "dressed_type";
            $sql_select = "SELECT * FROM $table 
                INNER JOIN $jointable ON $table.Dtype_No = $jointable.Dtype_No 
                ORDER BY $table.Dressed_No";
            $result = mysqli_query($conn, $sql_select);
            if (mysqli_num_rows($result) > 0) {
                $num = 1;
                while ($row = mysqli_fetch_array($result)) {
            ?>
                    <tr>
                        <th scope='row'><?= $num ?></th>
                        <td><?= $row['Dressed_No']; ?></td>
                        <td><?= $row['Dressed_Name']; ?></td>
                        <td><?= $row['D_Color']; ?></td>
                        <td>
                            <?php
                            $pics = ['D_Pic1', 'D_Pic2', 'D_Pic3', 'D_Pic4'];
                            echo '<div class="image-gallery">';
                            foreach ($pics as $pic) {
                                if (!empty($row[$pic])) {
                                    echo "<img src='../../assets/pic/dress/{$row[$pic]}' alt='none' class='gallery-image' onerror='this.onerror=null;'>";
                                }
                            }
                            echo '</div>';
                            ?>
                        </td>
                        <td><?= $row['size']; ?></td>
                        <td class="d-none"><?= $row['Dtype_Name']; ?></td>
                        <td><?= $row['D_price']; ?></td>
                        <td>
                            <!-- <div><strong>ประเภท :</strong> <?= $row['Dtype_Name'] ?></div> -->
                            <div>รอบอก : <?= $row['Bust'] ?></div>
                            <div>เอว : <?= $row['Waist'] ?></div>
                            <div>สะโพก : <?= $row['Hip'] ?></div>
                            <div>ยาว : <?= $row['long'] ?></div>
                            <div><strong>วัสดุ :</strong> <?= $row['material'] ?></div>
                            <div><strong>รายละเอียดเพิ่มเติม :</strong><?= $row['D_detail'] ?> </div>
                        </td>
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