<?php
include("../../config/dbcon.php");

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['Package_No'])) {
  $Package_No = $_GET['Package_No'];

  // ป้องกัน SQL Injection
  $sql_select = "SELECT * FROM Packages WHERE Package_No = ?";
  $stmt = mysqli_prepare($conn, $sql_select);
  mysqli_stmt_bind_param($stmt, "s", $Package_No); // 's' สำหรับ string
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_array($result);

  if (!$row) {
    echo "ข้อมูลไม่พบ";
    exit;
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
  <title>แก้ไขข้อมูลแพ็กเกจบริการ</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <div class="card text-center">
    <div class="card-header mt-3">
      <div class="label"><a href="package_list.php">รายการบริการ</a> >> แก้ไขข้อมูลบริการใหม่</div>
    </div>
  </div>
  <div class="container mt-3">
    <form action="" method="post" class="col-6">
      <div class="form-group">
        <label>Package No</label>
        <input type="text" name="Package_No" class="form-control" readonly value="<?= htmlspecialchars($row['Package_No']) ?>">
      </div>
      <div class="form-group">
        <label>Package Name</label>
        <input type="text" name="Package_Name" class="form-control" value="<?= htmlspecialchars($row['Package_Name']) ?>" required>
      </div>
      <div class="form-group">
        <label>Package Details</label>
        <textarea class="form-control" name="P_Detail" rows="10" required><?= htmlspecialchars($row['P_Detail']) ?></textarea>
      </div>
      <div class="form-group">
        <label>Price</label>
        <input type="text" name="Price" class="form-control" value="<?= htmlspecialchars($row['P_Price']) ?>" required>
      </div>
      <div class="form-group">
        <label>Package Type</label>
        <select name="Ptype_No" class="form-control" required>
          <?php
          $sql_types = "SELECT Ptype_No, Ptype_Name FROM PackageType";
          $result_types = mysqli_query($conn, $sql_types);
          while ($type_row = mysqli_fetch_assoc($result_types)) {
            $selected = $row['Ptype_No'] == $type_row['Ptype_No'] ? 'selected' : '';
            echo "<option value='" . $type_row['Ptype_No'] . "' $selected>" . htmlspecialchars($type_row['Ptype_Name']) . "</option>";
          }
          ?>
        </select>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-primary">แก้ไข</button>
        <a href="package_list.php" class="btn btn-secondary">ยกเลิก</a>
      </div>
    </form>
  </div>

  <?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าจากฟอร์ม
    $Package_No = $_POST['Package_No'];
    $Package_Name = $_POST['Package_Name'];
    $P_Detail = $_POST['P_Detail'];
    $Price = $_POST['Price'];
    $Ptype_No = $_POST['Ptype_No'];

    // ป้องกัน SQL Injection
    $sql_update = "UPDATE Packages SET
                   Package_Name = ?, P_Detail = ?, P_Price = ?, Ptype_No = ?
                   WHERE Package_No = ?";
    $stmt_update = mysqli_prepare($conn, $sql_update);
    mysqli_stmt_bind_param($stmt_update, "ssdss", $Package_Name, $P_Detail, $Price, $Ptype_No, $Package_No);

    if (mysqli_stmt_execute($stmt_update)) {
      echo "<script>
          Swal.fire({
              title: 'สำเร็จ!',
              text: 'แก้ไขข้อมูลรหัส $Package_No แล้ว',
              icon: 'success',
              confirmButtonText: 'OK',
              customClass: {
                  confirmButton: 'btn btn-success',
              }
          }).then((result) => {
              if (result.isConfirmed) {
                  window.location = 'package_list.php';
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