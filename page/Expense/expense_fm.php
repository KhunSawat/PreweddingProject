<?php
session_start();
include("../../config/dbcon.php");

// ฟังก์ชันในการสร้างหมายเลขรายจ่ายใหม่
function generateExpenseNumber($conn) {
    $sql = "SELECT MAX(CAST(SUBSTRING(Expense_No, 4) AS UNSIGNED)) AS max_number FROM Expense";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);

    $max_number = $row['max_number'] ? $row['max_number'] : 0;
    $new_number = $max_number + 1;

    return 'EXP' . str_pad($new_number, 3, '0', STR_PAD_LEFT);
}

$newExpenseNo = generateExpenseNumber($conn);
?>
<head>
  <meta charset="UTF-8"> 
  <title>เพิ่มข้อมูลรายจ่ายใหม่</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" crossorigin="anonymous">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" crossorigin="anonymous"></script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <div class="card text-center">
    <div class="card-header mt-3">
      <div class="label"><a href="expense_list.php">รายการรายจ่าย</a> >> เพิ่มข้อมูลรายจ่ายใหม่</div>
    </div>
  </div>

  <div class="container mt-3">
    <form action="" method="post" class="col-6">
      <div class="form-group">
        <label>รหัสรายจ่าย</label>
        <input type="text" name="Expense_No" class="form-control" readonly value="<?= htmlspecialchars($newExpenseNo, ENT_QUOTES, 'UTF-8') ?>">
      </div>
      <div class="form-group">
        <label>ชื่อรายจ่าย</label>
        <input type="text" name="Expense_Name" class="form-control" placeholder="กรอกชื่อรายจ่าย" required>
      </div>

      <div class="form-group">
        <label>ยอดจ่ายรวม (บาท)</label>
        <input type="number" step="0.01" name="Expenses" class="form-control" placeholder="กรอกยอดจ่ายรวม" required>
      </div>
      <div class="form-group">
        <label>วันที่เดือนปี</label>
        <input type="date" name="DateTime" required>
      </div>

      <div class="form-group">
        <label>รายละเอียด</label>
        <textarea class="form-control" name="E_details" rows="3" placeholder="กรอกรายละเอียดรายจ่าย"></textarea>
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-primary">บันทึก</button>
        <button type="cancel" class="btn btn-secondary">ยกเลิก</button>
      </div>
    </form>
  </div>

  <br>
  <?php
  if (isset($_POST['Expense_Name'])) {
      $Expense_No = $newExpenseNo;
      $Expense_Name = $_POST['Expense_Name'];
      $Expenses = $_POST['Expenses'];
      $E_details = $_POST['E_details'];
      $E_DateTime = $_POST['DateTime']; 

      // Insert Data
      $table = "Expense";
      $sql = "INSERT INTO $table (Expense_No, Expense_Name, Expenses, E_DateTime, E_details)
              VALUES ('$Expense_No', '$Expense_Name', '$Expenses', '$E_DateTime', '$E_details')";

      if (mysqli_query($conn, $sql)) {
          echo "<script>
              Swal.fire({
                  title: 'สำเร็จ!',
                  text: 'เพิ่มข้อมูลรายจ่ายใหม่แล้ว',
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
          echo "Error: " . mysqli_error($conn);
      }

      mysqli_close($conn);
  }
  ?>
</body>
</html>
