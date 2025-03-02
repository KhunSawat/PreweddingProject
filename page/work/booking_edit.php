<?php
session_start();
include("../../config/dbcon.php");

// รับหมายเลขการจองจากพารามิเตอร์
if (!isset($_GET['BookingH_No'])) {
    header("Location: booking_list.php");
    exit;
}
$BookingH_No = mysqli_real_escape_string($conn, $_GET['BookingH_No']);

// ดึงข้อมูลการจองที่มีอยู่
$sql = "SELECT * FROM Booking_H WHERE BookingH_No = '$BookingH_No'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) === 0) {
    header("Location: booking_list.php");
    exit;
}
$booking = mysqli_fetch_assoc($result);

// ดึงข้อมูลราคาแพ็คเกจที่มี ptype_No = '01'
$packagePrices = [];
$sql = "SELECT Package_No, Package_Name, P_Price FROM Packages WHERE ptype_No = '01'";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die('Error: ' . mysqli_error($conn));
}
while ($row = mysqli_fetch_assoc($result)) {
    $packagePrices[$row['Package_No']] = $row;
}

// ดึงข้อมูลสถานะการชำระเงินและสถานะงาน
$paymentStatuses = [];
$sql = "SELECT * FROM payment_status";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die('Error: ' . mysqli_error($conn));
}
while ($row = mysqli_fetch_assoc($result)) {
    $paymentStatuses[$row['Payment_status_No']] = $row['Payment_status_Name'];
}

$workStatuses = [];
$sql = "SELECT * FROM work_status";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die('Error: ' . mysqli_error($conn));
}
while ($row = mysqli_fetch_assoc($result)) {
    $workStatuses[$row['Work_Status_No']] = $row['Work_Name'];
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลการจอง</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="card text-center">
        <div class="card-header mt-3">
            <div class="label"><a href="booking_list.php">รายการจอง</a> >> แก้ไขข้อมูลการจอง</div>
        </div>
    </div>
    <div class="container mt-3">
        <form action="" method="post" class="col-8 mx-auto">
            <div class="form-group">
                <label>หมายเลขการจอง</label>
                <input type="text" name="BookingH_No" class="form-control" readonly value="<?= htmlspecialchars($booking['BookingH_No']) ?>">
            </div>
            <div class="form-group">
                <label>วันเริ่มต้น</label>
                <input type="date" name="B_DateTime_S" class="form-control" value="<?= htmlspecialchars($booking['B_DateTime_S']) ?>">
            </div>
            <div class="form-group">
                <label>วันสิ้นสุด</label>
                <input type="date" name="B_DateTime_F" class="form-control" value="<?= htmlspecialchars($booking['B_DateTime_F']) ?>">
            </div>
            <div class="form-group">
                <label>ลูกค้า</label>
                <select name="Customer_No" class="form-control" required>
                    <?php
                    $sql_customers = "SELECT customer_No, cus_Name FROM customers";
                    $result_customers = mysqli_query($conn, $sql_customers);
                    while ($row = mysqli_fetch_assoc($result_customers)) {
                        $selected = ($row['customer_No'] == $booking['Customer_No']) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($row['customer_No']) . "' $selected>" . htmlspecialchars($row['cus_Name']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>แพ็คเกจ</label>
                <select id="Package_No" name="Package_No" class="form-control" required onchange="updatePrice()">
                    <?php
                    foreach ($packagePrices as $pkg) {
                        $selected = ($pkg['Package_No'] == $booking['Package_No']) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($pkg['Package_No']) . "' data-price='" . htmlspecialchars($pkg['P_Price']) . "' $selected>" . htmlspecialchars($pkg['Package_Name']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>ราคา</label>
                <input type="text" id="Price" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label>มัดจำ 25%</label>
                <input type="text" id="Deposit" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label>ยอดคงเหลือหลังหักมัดจำ</label>
                <input type="text" id="Remaining" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label>สถานะการชำระเงิน</label>
                <select name="Payment_status_No" class="form-control" required>
                    <?php
                    foreach ($paymentStatuses as $id => $name) {
                        $selected = ($id == $booking['Payment_status_No']) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($id) . "' $selected>" . htmlspecialchars($name) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>สถานะงาน</label>
                <select name="Work_Status_No" class="form-control" required>
                    <?php
                    foreach ($workStatuses as $id => $name) {
                        $selected = ($id == $booking['Work_Status_No']) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($id) . "' $selected>" . htmlspecialchars($name) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>รายละเอียดเพิ่มเติม</label>
                <textarea class="form-control" name="B_detail" rows="3" placeholder="กรอกรายละเอียดเพิ่มเติม"><?= htmlspecialchars($booking['B_detail']) ?></textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">บันทึก</button>
                <a href="booking_list.php" class="btn btn-secondary" onclick='window.history.back()'>ยกเลิก</a>
            </div>
        </form>
    </div>
    <script>
        function updatePrice() {
            const packageSelect = document.getElementById("Package_No");
            const selectedOption = packageSelect.options[packageSelect.selectedIndex];
            const price = parseFloat(selectedOption.getAttribute("data-price"));

            if (!isNaN(price)) {
                document.getElementById("Price").value = price.toLocaleString('en-US', {
                    minimumFractionDigits: 2
                });
                const deposit = (price * 0.25).toFixed(2);
                document.getElementById("Deposit").value = parseFloat(deposit).toLocaleString('en-US', {
                    minimumFractionDigits: 2
                });
                const remaining = (price - deposit).toFixed(2);
                document.getElementById("Remaining").value = parseFloat(remaining).toLocaleString('en-US', {
                    minimumFractionDigits: 2
                });
            }
        }

        // Update price fields on page load
        window.onload = updatePrice;
    </script>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $BookingH_No = mysqli_real_escape_string($conn, $_POST['BookingH_No']);
        $B_DateTime_S = mysqli_real_escape_string($conn, $_POST['B_DateTime_S']);
        $B_DateTime_F = mysqli_real_escape_string($conn, $_POST['B_DateTime_F']);
        $Customer_No = mysqli_real_escape_string($conn, $_POST['Customer_No']);
        $Package_No = mysqli_real_escape_string($conn, $_POST['Package_No']);
        $Payment_status_No = mysqli_real_escape_string($conn, $_POST['Payment_status_No']);
        $Work_Status_No = mysqli_real_escape_string($conn, $_POST['Work_Status_No']);
        $B_detail = mysqli_real_escape_string($conn, $_POST['B_detail']);

        $sql = "UPDATE Booking_H SET B_DateTime_S = '$B_DateTime_S', B_DateTime_F = '$B_DateTime_F', Customer_No = '$Customer_No', Package_No = '$Package_No', Payment_status_No = '$Payment_status_No', Work_Status_No = '$Work_Status_No', B_detail = '$B_detail' WHERE BookingH_No = '$BookingH_No'";
        if (mysqli_query($conn, $sql)) {
            echo '<script>
                        Swal.fire({
                            title: "สำเร็จ",
                            text: "แก้ไขข้อมูลการจองรหัส ' . $BookingH_No . ' สำเร็จ!",
                            icon: "success",
                            confirmButtonText: "ตกลง"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "booking_list.php";
                            }
                        });
                    </script>';
        } else {
            echo '<script>
                    Swal.fire({
                        title: "ข้อผิดพลาด",
                        text: "ไม่สามารถแก้ไขข้อมูลการจองได้: ' . mysqli_error($conn) . '",
                        icon: "error",
                        confirmButtonText: "ตกลง"
                    });
                  </script>';
        }
    }
    ?>
</body>

</html>