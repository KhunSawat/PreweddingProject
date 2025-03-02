<?php
include("../../config/dbcon.php");
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
  <title>แก้ไขข้อมูลรายจ่าย</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" crossorigin="anonymous">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <?php
  if (isset($_GET['Expense_No'])) {
    $Expense_No = $_GET['Expense_No'];
    $table = "Expense";
    $sql_select = "SELECT * FROM $table WHERE Expense_No ='$Expense_No'";
    $result = mysqli_query($conn, $sql_select);
    $row = mysqli_fetch_array($result);
  }
  ?>

  <div class="card text-center">
    <div class="card-header mt-3">
      <div class="label"><a href="expense_list.php">รายการรายจ่าย</a> >> แก้ไขข้อมูลรายจ่าย</div>
    </div>
  </div>

  <div class="container mt-3">
    <form action="" method="post" class="col-6">

      <div class="form-group">
        <label>รหัสรายจ่าย</label>
        <input type="text" name="Expense_No" class="form-control" readonly value="<?= $row['Expense_No'] ?>">
      </div>

      <div class="form-group">
        <label>ชื่อรายจ่าย</label>
        <input type="text" name="Expense_Name" class="form-control" placeholder="กรอกชื่อรายจ่าย" value="<?= $row['Expense_Name']; ?>" required>
      </div>

      <div class="form-group">
        <label>ยอดจ่ายรวม (บาท)</label>
        <input type="number" step="0.01" name="Expenses" class="form-control" placeholder="กรอกยอดจ่ายรวม" required value="<?= $row['Expenses'] ?>">
      </div>
      <div class="form-group">
        <label>วันที่เดือนปี</label>
        <input type="date" name="DateTime" value="<?= isset($row['E_DateTime']) ? date('Y-m-d', strtotime($row['E_DateTime'])) : '' ?>" required>
      </div>
      <div class="form-group">
        <label>รายละเอียดรายจ่าย</label>
        <textarea class="form-control" name="E_details" rows="3"><?= $row['E_details'] ?></textarea>
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-primary">แก้ไข</button>
        <button type="cancel" class="btn btn-secondary">ยกเลิก</button>
      </div>
    </form>
  </div>

  <br>
  <?php
  if (isset($_POST['Expense_Name'])) {
    $Expense_No = mysqli_real_escape_string($conn, $_POST['Expense_No']);
    $Expense_Name = mysqli_real_escape_string($conn, $_POST['Expense_Name']);
    $Expenses = mysqli_real_escape_string($conn, $_POST['Expenses']);
    $E_details = mysqli_real_escape_string($conn, $_POST['E_details']);
    $E_DateTime = mysqli_real_escape_string($conn, $_POST['DateTime']);

    // SQL สำหรับอัปเดตข้อมูล
    $sql_update = "UPDATE $table SET
                   Expense_Name = '$Expense_Name',
                   Expenses = '$Expenses',
                   E_details = '$E_details',
                   E_DateTime = '$E_DateTime'
                   WHERE Expense_No = '$Expense_No'";

    if (mysqli_query($conn, $sql_update)) {
      echo "<script>
            Swal.fire({
                title: 'สำเร็จ!',
                text: 'แก้ไขข้อมูลรหัส $Expense_No แล้ว',
                icon: 'success',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'btn btn-success',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = 'expense_list.php';
                }
            });
        </script>";
    } else {
      echo "<script>
            Swal.fire({
                title: 'ผิดพลาด!',
                text: 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล: " . mysqli_error($conn) . "',
                icon: 'error',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'btn btn-danger',
                }
            });
        </script>";
    }
  }
  ?>
</body>

</html>