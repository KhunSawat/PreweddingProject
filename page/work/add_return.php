<?php
session_start();
include("../../config/dbcon.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Dressed_No = mysqli_real_escape_string($conn, $_POST['Dressed_No']);
    $Customers_No = mysqli_real_escape_string($conn, $_POST['Customers_No']);
    $bookingH_No = mysqli_real_escape_string($conn, $_POST['bookingH_No']);
    $Date_out = mysqli_real_escape_string($conn, $_POST['Date_out']);
    $Date_wait = mysqli_real_escape_string($conn, $_POST['Date_wait']);
    $Date_in = mysqli_real_escape_string($conn, $_POST['Date_in']);
    $Return_Status_No = mysqli_real_escape_string($conn, $_POST['Return_Status_No']);

    $sql_insert = "INSERT INTO `return` (Dressed_No, Customers_No, bookingH_No, Date_out, Date_wait, Date_in, Return_Status_No)
    VALUES ('$Dressed_No', '$Customers_No', '$bookingH_No', '$Date_out', '$Date_wait', '$Date_in', '$Return_Status_No')";

    if (mysqli_query($conn, $sql_insert)) {
        echo "<script>alert('เพิ่มข้อมูลการคืนชุดสำเร็จ'); window.location.href='return_list.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการเพิ่มข้อมูล'); window.history.back();</script>";
    }
}

$sql_dressed = "SELECT Dressed_No, Dressed_Name FROM dressed";
$result_dressed = mysqli_query($conn, $sql_dressed);

$sql_customers = "SELECT customer_No, cus_Name FROM customers";
$result_customers = mysqli_query($conn, $sql_customers);

$sql_booking = "SELECT BookingH_No FROM booking_h";
$result_booking = mysqli_query($conn, $sql_booking);

$sql_return_status = "SELECT Return_Status_No, Return_status_Name FROM return_status";
$result_return_status = mysqli_query($conn, $sql_return_status);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มข้อมูลการคืนชุด</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-3">
        <div class="card">
            <div class="card-header">
                <h4>เพิ่มข้อมูลการคืนชุด</h4>
            </div>
            <div class="card-body">
                <form action="" method="post">
                    <div class="form-group">
                        <label for="Dressed_No">ชุด</label>
                        <select name="Dressed_No" id="Dressed_No" class="form-control" required>
                            <option value="" disabled selected>เลือกชุด</option>
                            <?php while ($row_dressed = mysqli_fetch_assoc($result_dressed)): ?>
                                <option value="<?= htmlspecialchars($row_dressed['Dressed_No']) ?>">
                                    <?= htmlspecialchars($row_dressed['Dressed_Name']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="Customers_No">ลูกค้า</label>
                        <select name="Customers_No" id="Customers_No" class="form-control" required>
                            <option value="" disabled selected>เลือกลูกค้า</option>
                            <?php while ($row_customers = mysqli_fetch_assoc($result_customers)): ?>
                                <option value="<?= htmlspecialchars($row_customers['customer_No']) ?>">
                                    <?= htmlspecialchars($row_customers['cus_Name']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="bookingH_No">หมายเลขการจอง</label>
                        <select name="bookingH_No" id="bookingH_No" class="form-control" required>
                            <option value="" disabled selected>เลือกหมายเลขการจอง</option>
                            <?php while ($row_booking = mysqli_fetch_assoc($result_booking)): ?>
                                <option value="<?= htmlspecialchars($row_booking['BookingH_No']) ?>">
                                    <?= htmlspecialchars($row_booking['BookingH_No']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="Date_out">วันที่เบิกออก</label>
                        <input type="date" name="Date_out" id="Date_out" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="Date_wait">วันที่รอ</label>
                        <input type="date" name="Date_wait" id="Date_wait" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="Date_in">วันที่คืน</label>
                        <input type="date" name="Date_in" id="Date_in" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="Return_Status_No">สถานะการคืนชุด</label>
                        <select name="Return_Status_No" id="Return_Status_No" class="form-control" required>
                            <option value="" disabled selected>เลือกสถานะ</option>
                            <?php while ($row_return_status = mysqli_fetch_assoc($result_return_status)): ?>
                                <option value="<?= htmlspecialchars($row_return_status['Return_Status_No']) ?>">
                                    <?= htmlspecialchars($row_return_status['Return_status_Name']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">เพิ่มข้อมูล</button>
                    <a href="return_list.php" class="btn btn-secondary">กลับ</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<?php mysqli_close($conn); ?>
