<?php
include('../../config/dbcon.php');
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

// SQL คำนวณรายรับ รายจ่าย และกำไรสุทธิ
$sql = "
SELECT 
    Month,
    COALESCE(Total_Revenue, 0) AS Total_Revenue,
    COALESCE(Total_Expenses, 0) AS Total_Expenses,
    (COALESCE(Total_Revenue, 0) - COALESCE(Total_Expenses, 0)) AS Net_Profit
FROM (
    -- รายรับ
    SELECT 
        DATE_FORMAT(BH.B_DateTime_S, '%Y-%m') AS Month,
        SUM(BD.QTY * BD.price) + COALESCE(SUM(P.P_Price), 0) AS Total_Revenue,
        NULL AS Total_Expenses
    FROM booking_h BH
    JOIN booking_d BD ON BH.BookingH_No = BD.BookingH_No
    LEFT JOIN packages P ON BH.Package_No = P.Package_No  -- JOIN กับ packages เพื่อดึง P_Price
    GROUP BY Month

    UNION ALL

    -- รายจ่าย
    SELECT 
        DATE_FORMAT(E.E_DateTime, '%Y-%m') AS Month,
        NULL AS Total_Revenue,
        SUM(E.Expenses) AS Total_Expenses
    FROM Expense E
    GROUP BY Month
) AS Combined
GROUP BY Month
ORDER BY Month;

";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <title>รายงานสรุปรายรับรายจ่าย</title>
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
            text-align: center;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>

    <h1 style="text-align: center;">รายงานสรุปรายรับ รายจ่าย และกำไรสุทธิ</h1>
    <table>
        <thead>
            <tr>
                <th>เดือน</th>
                <th>รายรับ (บาท)</th>
                <th>รายจ่าย (บาท)</th>
                <th>กำไรสุทธิ (บาท)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>" . $row['Month'] . "</td>
                        <td>" . number_format($row['Total_Revenue'], 2) . "</td>
                        <td>" . number_format($row['Total_Expenses'], 2) . "</td>
                        <td>" . number_format($row['Net_Profit'], 2) . "</td>
                      </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>ไม่มีข้อมูล</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <?php
    // แสดงวันที่ปัจจุบันในรูปแบบไทย
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
    $year = date('Y') + 543; // แปลงเป็น พ.ศ.
    ?>
    <p style="text-align: right;">วันที่พิมพ์รายงาน: <?php echo "$day $month $year"; ?></p>

</body>

</html>

<?php
$html = ob_get_contents();
ob_end_clean();

// สร้าง PDF และแสดงผล
$mpdf->WriteHTML($html);
$mpdf->Output('Report_Income_Expenses.pdf', 'I');
?>