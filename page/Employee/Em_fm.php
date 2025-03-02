<?php
session_start();
include("../../config/dbcon.php");

// ฟังก์ชันในการสร้างหมายเลขพนักงานใหม่
function generateEmployeeNumber($conn) {
    $sql = "SELECT MAX(CAST(SUBSTRING(employee_No, 4) AS UNSIGNED)) AS max_number FROM Employee";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);

    $max_number = $row['max_number'] ? $row['max_number'] : 0;
    $new_number = $max_number + 1;

    return 'EMP' . str_pad($new_number, 3, '0', STR_PAD_LEFT);
}

$newEmployeeNo = generateEmployeeNumber($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>เพิ่มข้อมูลพนักงานใหม่</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" crossorigin="anonymous">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" crossorigin="anonymous"></script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <div class="card text-center">
    <div class="card-header mt-3">
      <div class="label"><a href="em_list.php">รายชื่อพนักงาน</a> >> เพิ่มข้อมูลพนักงานใหม่</div>
    </div>
  </div>

  <div class="container mt-3">
    <form action="" method="post" enctype="multipart/form-data" class="col-6">
      <div class="form-group">
        <label>รหัสพนักงาน</label>
        <input type="text" name="employee_No" class="form-control" readonly value="<?= htmlspecialchars($newEmployeeNo, ENT_QUOTES, 'UTF-8') ?>">
      </div>
      <div class="form-group">
        <label>ชื่อ-สกุล</label>
        <input type="text" name="em_Name" class="form-control" placeholder="กรอกชื่อ-นามสกุล" required>
      </div>
      <div class="form-group">
        <label>ชื่อผู้ใช้งาน</label>
        <input type="text" name="em_Username" class="form-control" placeholder="กรอกชื่อผู้ใช้งาน" required>
      </div>
      <div class="form-group">
        <label>รหัสผ่าน</label>
        <input type="password" name="em_Password" class="form-control" placeholder="กรอกรหัสผ่าน" required>
      </div>
      <div class="form-group">
        <label>วันเกิด</label>
        <input type="date" name="em_Brithdate" class="form-control" required>
      </div>
      <div class="form-group">
        <label>เพศ :</label>
        <div class="custom-control custom-radio custom-control-inline">
          <input type="radio" value="M" id="customRadioInline1" name="em_Gender" class="custom-control-input" required>
          <label class="custom-control-label" for="customRadioInline1">ชาย</label>
        </div>
        <div class="custom-control custom-radio custom-control-inline">
          <input type="radio" value="F" id="customRadioInline2" name="em_Gender" class="custom-control-input" required>
          <label class="custom-control-label" for="customRadioInline2">หญิง</label>
        </div>
      </div>
      <div class="form-group">
        <label>ที่อยู่</label>
        <textarea class="form-control" name="em_Address" rows="3" placeholder="กรอกที่อยู่"></textarea>
      </div>
      <div class="form-group">
        <label>เบอร์โทรศัพท์</label>
        <input type="text" name="em_Phone" class="form-control" placeholder="กรอกเบอร์โทรศัพท์" required>
      </div>
      <div class="form-group">
        <label>รูปภาพ</label>
        <input type="file" name="em_Pic" class="form-control">
      </div>
      <div class="form-group">
        <label>ประเภทพนักงาน</label>
        <select name="EmployeeType_No" class="form-control" required>
          <?php
          $sql = "SELECT EmployeeType_No, EmployeeType_Name FROM EmployeeType";
          $result = mysqli_query($conn, $sql);
          while ($row = mysqli_fetch_assoc($result)) {
              echo "<option value='" . htmlspecialchars($row['EmployeeType_No'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['EmployeeType_Name'], ENT_QUOTES, 'UTF-8') . "</option>";
          }
          ?>
        </select>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-primary">บันทึก</button>
          <a href="Em_list.php" class="btn btn-secondary">ยกเลิก</a>
      </div>
    </form>
  </div>

  <br>
  <?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_No = $newEmployeeNo; // ตรวจสอบให้แน่ใจว่า $newEmployeeNo ถูกกำหนดค่าไว้ที่อื่น
    $em_Name = $_POST['em_Name'];
    $em_Username = $_POST['em_Username'];
    $em_Password = $_POST['em_Password'];
    $em_Brithdate = $_POST['em_Brithdate'];
    $em_Gender = $_POST['em_Gender'];
    $em_Address = $_POST['em_Address'];
    $em_Phone = $_POST['em_Phone'];
    $EmployeeType_No = $_POST['EmployeeType_No'];
    $em_Pic = '';

    // ตรวจสอบและจัดการการอัพโหลดไฟล์
    if (!empty($_FILES['em_Pic']['name'])) {
        $em_Pic = basename($_FILES['em_Pic']['name']);
        $target_file = "../../assets/pic/" . $em_Pic;

        if (move_uploaded_file($_FILES['em_Pic']['tmp_name'], $target_file)) {
            echo "<p>ไฟล์ถูกอัพโหลดสำเร็จ</p>";
        } else {
            echo "<p>Error: ไม่สามารถอัพโหลดไฟล์ได้</p>";
        }
    }

    // Insert Data
    $sql = "INSERT INTO Employee (employee_No, em_Name, em_Username, em_Password, em_Brithdate, em_Gender, em_Address, em_Phone, em_Pic, EmployeeType_No)
            VALUES ('$employee_No', '$em_Name', '$em_Username', '$em_Password', '$em_Brithdate', '$em_Gender', '$em_Address', '$em_Phone', '$em_Pic', '$EmployeeType_No')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
            Swal.fire({
                title: 'สำเร็จ!',
                text: 'เพิ่มข้อมูลพนักงานใหม่แล้ว',
                icon: 'success',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'btn btn-success',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = 'em_list.php';
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
