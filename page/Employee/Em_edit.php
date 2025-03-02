<?php include("../../config/dbcon.php"); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
  <title>แก้ไขข้อมูลพนักงาน</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" crossorigin="anonymous">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" crossorigin="anonymous"></script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
  <?php
  if (isset($_GET['Employee_No'])) {
    $Employee_No = $_GET['Employee_No'];
    $sql_select = "SELECT * FROM Employee WHERE Employee_No = '$Employee_No'";
    $result = mysqli_query($conn, $sql_select);
    $row = mysqli_fetch_array($result);
  }
  ?>

  <div class="card text-center">
    <div class="card-header mt-3">
      <div class="label"><a href="em_list.php">รายชื่อพนักงาน</a> >> แก้ไขข้อมูลพนักงาน</div>
    </div>
  </div>

  <div class="container mt-3">
    <form action="" method="post" enctype="multipart/form-data" class="col-6">
      <div class="form-group">
        <label>รหัสพนักงาน</label>
        <input type="text" name="Employee_No" class="form-control" readonly value="<?= $row[0] ?>">
      </div>
      <div class="form-group">
        <label>ชื่อ-สกุล</label>
        <input type="text" name="Name" class="form-control" placeholder="กรอกชื่อ-นามสกุล" value="<?= $row[1] ?>" required>
      </div>
      <div class="form-group">
        <label>ชื่อผู้ใช้งาน</label>
        <input type="text" name="UserName" class="form-control" placeholder="กรอกชื่อผู้ใช้งาน" value="<?= $row[2] ?>" required>
      </div>
      <div class="form-group">
        <label>รหัสผ่าน</label>
        <input type="text" name="Password" class="form-control" placeholder="กรอกรหัสผ่าน" value="<?= $row[3] ?>" required>
      </div>
      <div class="form-group">
        <label>วันเกิด</label>
        <input type="date" name="Brithdate" class="form-control" value="<?= $row[6] ?>" required>
      </div>
      <div class="form-group">
        <label>เพศ :</label>
        <div class="custom-control custom-radio custom-control-inline">
          <input type="radio" value="M" id="customRadioInline1" name="Gender" class="custom-control-input" <?= $row[8] == 'M' ? 'checked' : '' ?>>
          <label class="custom-control-label" for="customRadioInline1">ชาย</label>
        </div>
        <div class="custom-control custom-radio custom-control-inline">
          <input type="radio" value="F" id="customRadioInline2" name="Gender" class="custom-control-input" <?= $row[8] == 'F' ? 'checked' : '' ?>>
          <label class="custom-control-label" for="customRadioInline2">หญิง</label>
        </div>
      </div>
      <div class="form-group">
        <label>ที่อยู่</label>
        <textarea class="form-control" name="Address" rows="3"><?= $row[4] ?></textarea>
      </div>
      <div class="form-group">
        <label>เบอร์โทรศัพท์</label>
        <input type="text" name="Phone" class="form-control" placeholder="กรอกเบอร์โทรศัพท์" value="<?= $row[5] ?>" required>
      </div>
      <div class="form-group">
        <label>รูปภาพ</label>
        <div class="ml-3 mb-3">
          <img src="../../assets/pic/<?= $row[7] ?>" width='200px' height="200px" alt="none" style="object-fit: cover;">
        </div>
        <input type="file" name="Photo" class="form-control">
      </div>
      <div class="form-group">
        <label>ประเภทพนักงาน</label>
        <select name="EmployeeType_No" class="form-control" required>
          <option value="">เลือกประเภทพนักงาน</option>
          <?php
          $sql_types = "SELECT EmployeeType_No, EmployeeType_Name FROM EmployeeType";
          $result_types = mysqli_query($conn, $sql_types);
          while ($type_row = mysqli_fetch_assoc($result_types)) {
            $selected = $row['EmployeeType_No'] == $type_row['EmployeeType_No'] ? 'selected' : '';
            echo "<option value='" .  $type_row['EmployeeType_No'] . "' $selected>" . $type_row['EmployeeType_Name'] . "</option>";
          }
          ?>
          <option value="new">อื่นๆ (กรอกใหม่)</option>
        </select>
      </div>

      <!-- กล่องกรอกประเภทพนักงานสำหรับที่ไม่อยู่ในรายการ -->
      <div class="form-group" id="newEmployeeTypeGroup" style="display:none;">
        <label>กรอกประเภทพนักงาน</label>
        <input type="text" name="NewEmployeeType" class="form-control" placeholder="กรอกประเภทพนักงาน">
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-primary">แก้ไข</button>
        <a href="em_list.php" class="btn btn-secondary">ยกเลิก</a>
      </div>
    </form>
  </div>

  <br>
  <?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Employee_No = $_POST['Employee_No'];
    $Name = $_POST['Name'];
    $UserName = $_POST['UserName'];
    $Password = $_POST['Password'];
    $Brithdate = $_POST['Brithdate'];
    $Gender = $_POST['Gender'];
    $Address = $_POST['Address'];
    $Phone = $_POST['Phone'];
    $EmployeeType_No = $_POST['EmployeeType_No'];
    $em_Pic = $row['em_Pic'];

    // ตรวจสอบว่ามีการกรอกประเภทพนักงานใหม่หรือไม่
    if ($EmployeeType_No === 'new' && !empty($_POST['NewEmployeeType'])) {
      $NewEmployeeType = $_POST['NewEmployeeType'];

      // หา EmployeeType_No สูงสุดแล้วเพิ่มค่าใหม่
      $sql_max_type_no = "SELECT MAX(EmployeeType_No) AS MaxTypeNo FROM EmployeeType";
      $result_max_type_no = mysqli_query($conn, $sql_max_type_no);
      $row_max_type_no = mysqli_fetch_assoc($result_max_type_no);
      $max_type_no = $row_max_type_no['MaxTypeNo'];

      // กำหนด EmployeeType_No ใหม่โดยเพิ่ม 1
      $new_type_no = str_pad((int)$max_type_no + 1, 2, '0', STR_PAD_LEFT); // เพิ่ม 1 และให้ได้ 2 หลักเสมอ

      // เพิ่มประเภทพนักงานใหม่ลงในฐานข้อมูล
      $sql_insert_type = "INSERT INTO EmployeeType (EmployeeType_No, EmployeeType_Name) VALUES ('$new_type_no', '$NewEmployeeType')";
      if (mysqli_query($conn, $sql_insert_type)) {
        $EmployeeType_No = $new_type_no;
      } else {
        echo "Error: " . mysqli_error($conn);
      }
    }

    // ตรวจสอบและจัดการการอัพโหลดไฟล์
    if (!empty($_FILES['Photo']['name'])) {
      $em_Pic = basename($_FILES['Photo']['name']);
      $target_file = "../../assets/pic/" . $em_Pic;

      if (move_uploaded_file($_FILES['Photo']['tmp_name'], $target_file)) {
        echo "<p>ไฟล์ถูกอัพโหลดสำเร็จ</p>";
      } else {
        echo "<p>Error: ไม่สามารถอัพโหลดไฟล์ได้</p>";
      }
    }

    // Update Data
    $sql_update = "UPDATE Employee SET
                   em_Name = '$Name',
                   em_Username = '$UserName',
                   em_Password = '$Password',
                   em_Brithdate = '$Brithdate',
                   em_Gender = '$Gender',
                   em_Address = '$Address',
                   em_Phone = '$Phone',
                   em_Pic = '$em_Pic',
                   EmployeeType_No = '$EmployeeType_No'
                   WHERE Employee_No = '$Employee_No'";

    if (mysqli_query($conn, $sql_update)) {
      echo "<script>
          Swal.fire({
              title: 'สำเร็จ!',
              text: 'แก้ไขข้อมูลรหัส $Employee_No แล้ว',
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

  <script>
    // แสดง/ซ่อนช่องกรอกประเภทพนักงานใหม่ตามการเลือกใน dropdown
    document.querySelector('select[name="EmployeeType_No"]').addEventListener('change', function() {
      if (this.value === 'new') {
        document.getElementById('newEmployeeTypeGroup').style.display = 'block';
      } else {
        document.getElementById('newEmployeeTypeGroup').style.display = 'none';
      }
    });
  </script>

</body>

</html>