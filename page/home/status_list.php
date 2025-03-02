<?php
// session_start();
include("../../config/dbcon.php");
include('../../config/loginchk.php');

$sql_select = "SELECT 
    booking_h.BookingH_No AS booking_no, 
    booking_h.B_DateTime_S AS start_time, 
    booking_h.B_DateTime_F AS end_time, 
    customers.cus_Name AS customer_name, 
    payment_status.Payment_status_Name AS payment_status, 
    payment_status.Payment_status_No AS payment_status_no,
    work_status.Work_Name AS work_status,
    work_status.Work_Status_No AS work_status_no,
    Packages.Package_Name AS PkgName
FROM
    booking_h
INNER JOIN payment_status ON payment_status.Payment_status_No = booking_h.Payment_status_No
INNER JOIN work_status ON work_status.Work_Status_No = booking_h.work_status_No
INNER JOIN customers ON customers.Customer_No = booking_h.Customer_No
INNER JOIN packages ON packages.Package_No = booking_h.Package_No
ORDER BY BookingH_No DESC
LIMIT 5
";

$result = mysqli_query($conn, $sql_select);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Booking Information</title>
</head>

<body>

    <table class="table align-items-center mb-0">
        <thead class="thead-light">
            <tr>
                <th scope="col">รหัสการจอง</th>
                <th scope="col" class="text-end">วันที่และเวลา</th>
                <th scope="col" class="text-end">ลูกค้า</th>  
                <th scope="col" class="text-end">แพ็กเกจบริการ</th>  
                <th scope="col" class="text-end">สถานะการชำระเงิน</th>
                <th scope="col" class="text-end">สถานะงาน</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                $num = 1;
                while ($row = mysqli_fetch_assoc($result)) {

                    // กำหนดคลาสของสถานะงาน
                    $work_status_class = '';
                    switch ($row['work_status_no']) {
                        case '00':
                            $work_status_class = 'badge badge-danger';
                            $WorkStatusBar = "<div class='progress progress-sm mt-1'>
                                            <div
                                                class='progress-bar bg-danger w-100'
                                                role='progressbar'
                                                aria-valuenow='100'
                                                aria-valuemin='0'
                                                aria-valuemax='100'
                                            ></div>
                                          </div>";
                            break;
                        case '01':
                            $work_status_class = 'badge badge-info';
                            $WorkStatusBar = "<div class='progress progress-sm mt-1'>
                            <div
                                class='progress-bar bg-info w-50'
                                role='progressbar'
                                aria-valuenow='50'
                                aria-valuemin='0'
                                aria-valuemax='100'
                            ></div>
                          </div>";
                            break;
                        case '02':
                            $work_status_class = 'badge badge-success';
                            $WorkStatusBar = "<div class='progress progress-sm mt-1'>
                            <div
                                class='progress-bar bg-success w-100'
                                role='progressbar'
                                aria-valuenow='100'
                                aria-valuemin='0'
                                aria-valuemax='100'
                            ></div>
                          </div>";
                            break;
                    }

                    // กำหนดคลาสของสถานะการชำระเงิน
                    $payment_status_class = '';
                    switch ($row['payment_status_no']) {
                        case '01':
                            $payment_status_class = 'badge badge-danger';
                            $PayStatusBar = "<div class='progress progress-sm mt-1'>
                                            <div
                                                class='progress-bar bg-danger w-100'
                                                role='progressbar'
                                                aria-valuenow='100'
                                                aria-valuemin='0'
                                                aria-valuemax='100'
                                            ></div>
                                          </div>";
                            break;
                        case '02':
                            $payment_status_class = 'badge badge-count';
                            $PayStatusBar = "<div class='progress progress-sm mt-1'>
                            <div
                                class='progress-bar bg-info w-25'
                                role='progressbar'
                                aria-valuenow='25'
                                aria-valuemin='0'
                                aria-valuemax='100'
                            ></div>
                          </div>";
                            break;
                        case '03':
                            $payment_status_class = 'badge badge-info';
                            $PayStatusBar = "<div class='progress progress-sm mt-1'>
                            <div
                                class='progress-bar bg-info w-50'
                                role='progressbar'
                                aria-valuenow='50'
                                aria-valuemin='0'
                                aria-valuemax='100'
                            ></div>
                          </div>";
                            break;
                        case '04':
                            $payment_status_class = 'badge badge-success';
                            $PayStatusBar = "<div class='progress progress-sm mt-1'>
                            <div
                                class='progress-bar bg-success w-100'
                                role='progressbar'
                                aria-valuenow='100'
                                aria-valuemin='0'
                                aria-valuemax='100'
                            ></div>
                          </div>";
                            break;
                    }

                    // ฟอร์แมตวันที่
                    $start_date = $row['start_time'] === '0000-00-00' ? '-' : date('d/m/Y', strtotime($row['start_time']));
                    $end_date = $row['end_time'] === '0000-00-00' ? '-' : date('d/m/Y', strtotime($row['end_time']));
            ?>
                    <tr>
                        <th scope="row">
                            <a href="../work/search_booking.php?BookingH_No=<?php echo $row['booking_no']; ?>" class="text-black">
                                <?php echo $row['booking_no']; ?> 
                            </a>
                        </th>
                        <td class="text-end">
                            <?php echo $start_date . ' - ' . $end_date; ?> 
                        </td>
                        <td class="text-end"><?php echo $row['customer_name']; ?></td> 
                        <td class="text-end"><?php echo $row['PkgName']; ?></td> 
                        <td class="text-end">
                            <div class="<?= $payment_status_class; ?>">
                                <?php echo $row['payment_status']; ?> 
                            </div>
                            <?php echo $PayStatusBar; ?>
                        </td>
                        <td class="text-end">
                            <div class="<?= $work_status_class; ?>">
                                <?php echo $row['work_status']; ?>
                            </div>
                            <?php echo $WorkStatusBar; ?>
                        </td>
                    </tr>
            <?php
                    $num++;
                }
            } else {
                echo "<tr><td colspan='5' class='text-center'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>