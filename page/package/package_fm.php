<?php
session_start();
include("../../config/dbcon.php");

function generatePackageNumber($conn) {
    $sql = "SELECT MAX(CAST(SUBSTRING(Package_No, 4) AS UNSIGNED)) AS max_number FROM Packages";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);

    $max_number = $row['max_number'] ? $row['max_number'] : 0;
    $new_number = $max_number + 1;

    return 'PKG' . str_pad($new_number, 3, '0', STR_PAD_LEFT);
}

$newPackageNo = generatePackageNumber($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>เพิ่มข้อมูลบริการใหม่</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" crossorigin="anonymous">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" crossorigin="anonymous"></script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <div class="card text-center">
    <div class="card-header mt-3">
      <div class="label"><a href="package_list.php">รายการบริการ</a> >> เพิ่มข้อมูลบริการใหม่</div>
    </div>
  </div>

  <div class="container mt-3">
    <form action="" method="post" enctype="multipart/form-data" class="col-6">
      <div class="form-group">
        <label>รหัสบริการ</label>
        <input type="text" name="Package_No" class="form-control" readonly value="<?= htmlspecialchars($newPackageNo, ENT_QUOTES, 'UTF-8') ?>">
      </div>
      <div class="form-group">
        <label>ชื่อบริการ</label>
        <input type="text" name="Package_Name" class="form-control" placeholder="กรอกชื่อบริการ" required>
      </div>
      <div class="form-group">
        <label>รายละเอียดบริการ</label>
        <textarea name="P_Detail" class="form-control" rows="10" placeholder="กรอกรายละเอียดบริการ" required></textarea>
      </div>
      <div class="form-group">
        <label>ราคา</label>
        <input type="number" step="0.01" name="Price" class="form-control" placeholder="กรอกราคา" required>
      </div>
      <div class="form-group">
        <label>ประเภทบริการ</label>
        <select name="Ptype_No" class="form-control" required>
          <?php
          $sql = "SELECT Ptype_No, Ptype_Name FROM PackageType";
          $result = mysqli_query($conn, $sql);
          while ($row = mysqli_fetch_assoc($result)) {
              echo "<option value='" . htmlspecialchars($row['Ptype_No'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['Ptype_Name'], ENT_QUOTES, 'UTF-8') . "</option>";
          }
          ?>
        </select>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-primary">บันทึก</button>
        <a href="package_list.php" class="btn btn-secondary">ยกเลิก</a>
       
      </div>
    </form>
  </div>

  <br>
  <?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Package_No = $newPackageNo; // ใช้รหัสบริการใหม่ที่ถูกสร้าง
    $Package_Name = $_POST['Package_Name'];
    $P_Detail = $_POST['P_Detail'];
    $Price = $_POST['Price'];
    $Ptype_No = $_POST['Ptype_No'];

    // Insert Data
    $sql = "INSERT INTO Packages (Package_No, Package_Name, P_Detail, P_Price, Ptype_No)
            VALUES ('$Package_No', '$Package_Name', '$P_Detail', '$Price', '$Ptype_No')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
            Swal.fire({
                title: 'สำเร็จ!',
                text: 'เพิ่มข้อมูลบริการใหม่แล้ว',
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
