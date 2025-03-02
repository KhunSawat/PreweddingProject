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

    <h1 style="text-align: center;">รายงานการให้บริการ</h1>
    <table
        id="basic-datatables"
        class="display table table-striped table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>รหัสการจอง</th>
                <th style="width: 160px;">วันที่จอง</th>
                <th style="width: 160px;">ชื่อลูกค้า</th>
                <th style="width: 100px;">สถานะการชำระเงิน</th>
                <th style="width: 100px;">สถานะการดำเนินงาน</th>
                <th>แพ็กเกจ</th>
                <th>รายละเอียด</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $searchQuery = "";
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $searchQuery = mysqli_real_escape_string($conn, $_GET['search']);
            }

            // ปรับ SQL ให้รองรับค่าค้นหา
            $sql_select = "SELECT * FROM booking_h
            INNER JOIN work_status ON booking_h.work_status_No = work_status.Work_Status_No
            INNER JOIN payment_status ON payment_status.Payment_status_No = booking_h.Payment_status_No
            INNER JOIN customers ON customers.customer_No = booking_h.Customer_No
            INNER JOIN packages ON packages.Package_No = booking_h.Package_No
            WHERE booking_h.BookingH_No LIKE '%$searchQuery%'
            OR booking_h.B_DateTime_S LIKE '%$searchQuery%'
            OR customers.cus_Name LIKE '%$searchQuery%'
            OR packages.Package_Name LIKE '%$searchQuery%'
            ORDER BY BookingH_No DESC";

            $result = mysqli_query($conn, $sql_select);

            if (mysqli_num_rows($result) > 0) {
                $num = 1;
                while ($row = mysqli_fetch_assoc($result)) {


                $start_date = ($row['B_DateTime_S'] === '0000-00-00') ? '-' : date('d-m-Y', strtotime($row['B_DateTime_S']));
                $end_date = ($row['B_DateTime_F'] === '0000-00-00') ? '-' : date('d-m-Y', strtotime($row['B_DateTime_F']));
                $date_range = $start_date . ' / ' . $end_date;
            ?>
                    <tr>
                        <th scope='row'><?= $num ?></th>
                        <td><?= $row['BookingH_No']; ?></td>
                        <td><?= $date_range; ?></td>
                        <td><?= $row['cus_Name']; ?></td>
                        <td>
                            <div class="<?= $payment_status['class']; ?>"><?= $row['Payment_status_Name']; ?></div>
                            <div class='progress progress-sm mt-1'>
                                <div class='progress-bar <?= $payment_status['progress']; ?>' role='progressbar' aria-valuenow='<?= $payment_status['value']; ?>' aria-valuemin='0' aria-valuemax='100'></div>
                            </div>
                        </td>
                        <td>
                            <div class="<?= $work_status['class']; ?>"><?= $row['Work_Name']; ?></div>
                            <div class='progress progress-sm mt-1'>
                                <div class='progress-bar <?= $work_status['progress']; ?>' role='progressbar' aria-valuenow='<?= $work_status['value']; ?>' aria-valuemin='0' aria-valuemax='100'></div>
                            </div>
                        </td>

                        <td style="height: 150px;">
                            <div>
                                <hr>
                                <span><strong><?= $row['Package_Name']; ?></strong></span>
                                <span>ราคา: <?= $row['P_Price']; ?> บาท</span>
                                <hr>
                                <?php
                                // ดึงข้อมูลแพ็กเกจเสริมจากฐานข้อมูล
                                $BookingH_No = $row['BookingH_No'];
                                $sql = "SELECT booking_d.QTY, booking_d.Price, packages.Package_Name, booking_d.BookingH_No
                                            FROM booking_d
                                            INNER JOIN packages ON booking_d.Package_No = packages.Package_No
                                            WHERE booking_d.BookingH_No = '$BookingH_No'";
                                $pak = mysqli_query($conn, $sql);

                                // ตรวจสอบว่ามีข้อมูลหรือไม่
                                if (mysqli_num_rows($pak) > 0) {
                                    $packages = [];
                                    while ($package = mysqli_fetch_assoc($pak)) {
                                        $packages[] = $package; // เก็บข้อมูลแพ็กเกจเสริม
                                    }
                                } else {
                                    $packages = [];
                                }

                                ?>
                                <div><strong>บริการเสริมอื่นๆ:</strong></div>
                                <?php
                                $pk = 0; // ตัวแปรสำหรับเก็บยอดรวม
                                $pkAll = $row['P_Price']; // ตัวแปรสำหรับเก็บยอดรวม
                                if (count($packages) > 0) {
                                    foreach ($packages as $package): ?>
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="ms-4"><?= $package['Package_Name']; ?></span>
                                            <span class="me-4"><?= number_format($package['Price'], 2); ?> บาท </span>
                                        </div>
                                <?php
                                        // เพิ่มราคาบริการเสริมเข้าไปในยอดรวม
                                        $pk += $package['Price'];
                                    endforeach;
                                    $pkAll += $pk;
                                } else {
                                    echo '<div>ไม่มีบริการเสริม</div>';
                                }
                                ?>
                                <hr>
                                <div><strong>ยอดรวมบริการเสริม:</strong> <?= number_format($pkAll, 2); ?> บาท</div> <!-- แสดงยอดรวม -->
                                <hr>
                            </div>
                        </td>

                        <td style="height: 150px;">
                            <div>
                                <hr>
                                <div><strong>หมายเหตุ :</strong> <?= $row['B_detail']; ?></div>
                                <hr>
                                <?php
                                // สมมติว่า 'price' คือราคาของแพ็กเกจ และ 'totalPriceD' คือยอดรวมของบริการเสริม
                                $prices = $row['P_Price'];
                                $totalPriceD = $pk;

                                // คำนวณมัดจำและยอดรวม
                                $depositPackage = $prices * 0.25;
                                $deductedPackage = $prices * 0.75;
                                $totalPackage = $prices;

                                $depositService = $totalPriceD * 0.5;
                                $deductedService = $totalPriceD * 0.5;
                                $totalService = $totalPriceD;
                                ?>

                                <div><strong>ยอดเงิน(แพ็กเกจ)</strong></div>
                                <div class="d-flex justify-content-between">
                                    <span class="ms-4">มัดจำ (25%) :</span>
                                    <span class="me-4"><?= number_format($depositPackage, 2); ?> บาท</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="ms-4">หักมัดจำ :</span>
                                    <span class="me-4"><?= number_format($deductedPackage, 2); ?> บาท</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="ms-4">ทั้งหมด :</span>
                                    <span class="me-4"><?= number_format($totalPackage, 2); ?> บาท</span>
                                </div>
                                <hr>

                                <div><strong>ยอดเงิน(บริการเสริม)</strong></div>
                                <div class="d-flex justify-content-between">
                                    <span class="ms-4">มัดจำ (50%):</span>
                                    <span class="me-4"><?= number_format($depositService, 2); ?> บาท</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="ms-4">หักมัดจำ :</span>
                                    <span class="me-4"><?= number_format($deductedService, 2); ?> บาท</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="ms-4">ทั้งหมด :</span>
                                    <span class="me-4"><?= number_format($totalService, 2); ?> บาท</span>
                                </div>
                            </div>

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
$mpdf->Output('ReportEm.pdf');
$mpdf->Output('ReportEm.pdf', 'I');
ob_end_flush();
?>