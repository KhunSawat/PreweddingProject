<?php
session_start();
include("../../config/dbcon.php");

// ฟังก์ชันในการสร้างหมายเลขการจองใหม่
function generateBookingNumber($conn)
{
    $prefix = 'BOOK';
    $sql = "SELECT MAX(CAST(SUBSTRING(BookingH_No, 5) AS UNSIGNED)) AS max_number FROM Booking_H WHERE BookingH_No LIKE '$prefix%'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);

    $max_number = $row['max_number'] ? $row['max_number'] : 0;
    $new_number = $max_number + 1;

    return $prefix . str_pad($new_number, 3, '0', STR_PAD_LEFT);
}

$newBookingNo = generateBookingNumber($conn);

$packagePrices = [];
$sql = "SELECT Package_No, Package_Name, P_Price FROM Packages WHERE ptype_No = '01'";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die('Error: ' . mysqli_error($conn));
}
while ($row = mysqli_fetch_assoc($result)) {
    $packagePrices[$row['Package_No']] = $row;
}

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
$sql = "SELECT * FROM work_status  where Work_Status_No != '00'";
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
    <title>เพิ่มข้อมูลการจองใหม่</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="card text-center">
        <div class="card-header mt-3">
            <div class="label"><a href="booking_list.php">รายการจอง</a> >> เพิ่มข้อมูลการจองใหม่</div>
        </div>
    </div>
    <div class="container mt-3">
        <form action="" method="post" class="col-6">
            <div class="form-group">
                <label>หมายเลขการจอง</label>
                <input type="text" name="BookingH_No" class="form-control" readonly value="<?= $newBookingNo ?>">
            </div>
            <div class="form-group">
                <label>วันเริ่มต้น</label>
                <input type="date" name="B_DateTime_S" class="form-control">
            </div>
            <div class="form-group">
                <label>วันสิ้นสุด</label>
                <input type="date" name="B_DateTime_F" class="form-control">
            </div>
            <div class="form-group">
                <label>ลูกค้า</label>
                <select name="Customer_No" class="form-control" required>
                    <?php
                    $sql = "SELECT customer_No, cus_Name FROM customers";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['customer_No'] . "'>" . $row['cus_Name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>แพ็คเกจ</label>
                <select id="Package_No" name="Package_No" class="form-control" required onchange="updatePrice()">
                    <?php
                    foreach ($packagePrices as $pkg) {
                        echo "<option value='" . $pkg['Package_No'] . "' data-price='" . $pkg['P_Price'] . "'>" . $pkg['Package_Name'] . "</option>";
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
                        echo "<option value='" . $id . "'>" . $name . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>สถานะงาน</label>
                <select name="Work_Status_No" class="form-control" required>
                    <?php
                    foreach ($workStatuses as $id => $name) {
                        echo "<option value='" . $id . "'>" . $name . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>รายละเอียดเพิ่มเติม</label>
                <textarea class="form-control" name="B_detail" rows="3" placeholder="กรอกรายละเอียดเพิ่มเติม"></textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">บันทึก</button>
                <a href="booking_list.php" class="btn btn-secondary">ยกเลิก</a>
            </div>
        </form>
    </div>
    <br>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $BookingH_No = mysqli_real_escape_string($conn, $newBookingNo);
        $B_DateTime_S = mysqli_real_escape_string($conn, $_POST['B_DateTime_S']);
        $B_DateTime_F = mysqli_real_escape_string($conn, $_POST['B_DateTime_F']);
        $Customer_No = mysqli_real_escape_string($conn, $_POST['Customer_No']);
        $Package_No = mysqli_real_escape_string($conn, $_POST['Package_No']);
        $Payment_status_No = mysqli_real_escape_string($conn, $_POST['Payment_status_No']);
        $Work_Status_No = mysqli_real_escape_string($conn, $_POST['Work_Status_No']);
        $B_detail = mysqli_real_escape_string($conn, $_POST['B_detail']);

        // บันทึกข้อมูล
        $sql = "INSERT INTO Booking_H (BookingH_No, B_DateTime_S, B_DateTime_F, Customer_No, Package_No, Payment_status_No, Work_Status_No, B_detail)
              VALUES ('$BookingH_No', '$B_DateTime_S', '$B_DateTime_F', '$Customer_No', '$Package_No', '$Payment_status_No', '$Work_Status_No', '$B_detail')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>
              Swal.fire({
                  title: 'สำเร็จ!',
                  text: 'เพิ่มข้อมูลการจองใหม่แล้ว',
                  icon: 'success',
                  confirmButtonText: 'ตกลง',
                  customClass: {
                      confirmButton: 'btn btn-success',
                  }
              }).then((result) => {
                  if (result.isConfirmed) {
                      window.location = 'booking_list.php';
                  }
              });
          </script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }

        mysqli_close($conn);
    }
    ?>
    <script>
        function updatePrice() {
            const packageSelect = document.getElementById("Package_No");
            const selectedOption = packageSelect.options[packageSelect.selectedIndex];
            const price = parseFloat(selectedOption.getAttribute("data-price"));

            if (!isNaN(price)) {
                document.getElementById("Price").value = price.toLocaleString('en-US', { minimumFractionDigits: 2 });
                const deposit = (price * 0.25).toFixed(2);
                document.getElementById("Deposit").value = parseFloat(deposit).toLocaleString('en-US', { minimumFractionDigits: 2 });
                const remaining = (price - deposit).toFixed(2);
                document.getElementById("Remaining").value = parseFloat(remaining).toLocaleString('en-US', { minimumFractionDigits: 2 });
            }
        }

        // Update price fields on page load
        window.onload = updatePrice;
    </script>
</body>
</html>
