<?php
session_start();
include("../../config/dbcon.php");

// ฟังก์ชันในการสร้างหมายเลขลูกค้าใหม่
function generateCustomerNumber($conn) {
    $sql = "SELECT MAX(CAST(SUBSTRING(customer_No, 4) AS UNSIGNED)) AS max_number FROM customers";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);

    $max_number = $row['max_number'] ? $row['max_number'] : 0;
    $new_number = $max_number + 1;

    return 'CUS' . str_pad($new_number, 3, '0', STR_PAD_LEFT);
}

$newCustomerNo = generateCustomerNumber($conn);
?>
<head>
  <meta charset="UTF-8"> 
  <title>เพิ่มข้อมูลลูกค้าใหม่</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" crossorigin="anonymous">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" crossorigin="anonymous"></script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <div class="card text-center">
    <div class="card-header mt-3">
      <div class="label"><a href="customer_list.php">รายชื่อลูกค้า</a> >> เพิ่มข้อมูลลูกค้าใหม่</div>
    </div>
  </div>

  <div class="container mt-3">
    <form action="" method="post" enctype="multipart/form-data" class="col-6">
      <div class="form-group">
        <label>รหัสลูกค้า</label>
        <input type="text" name="customer_No" class="form-control" readonly value="<?= htmlspecialchars($newCustomerNo, ENT_QUOTES, 'UTF-8') ?>">
      </div>
      <div class="form-group">
        <label>ชื่อ-สกุล</label>
        <input type="text" name="cus_Name" class="form-control" placeholder="กรอกชื่อ-นามสกุล" required>
      </div>

      <div class="form-group">
        <label>เพศ :</label>
        <div class="custom-control custom-radio custom-control-inline">
          <input type="radio" value="M" id="customRadioInline1" name="cus_gender" class="custom-control-input">
          <label class="custom-control-label" for="customRadioInline1">ชาย</label>
        </div>
        <div class="custom-control custom-radio custom-control-inline">
          <input type="radio" value="F" id="customRadioInline2" name="cus_gender" class="custom-control-input">
          <label class="custom-control-label" for="customRadioInline2">หญิง</label>
        </div>
      </div>

      <div class="form-group">
        <label>ที่อยู่</label>
        <textarea class="form-control" name="cus_Address" rows="3"></textarea>
      </div>

      <div class="form-group">
        <label>เบอร์โทรศัพท์</label>
        <input type="text" name="cus_phone" class="form-control" placeholder="กรอกเบอร์โทรศัพท์" required>
      </div>

      <div class="form-group">
        <label>รูปภาพ</label>
        <input type="file" name="cus_pic" class="form-control">
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-primary">บันทึก</button>
        <button type="cancel" class="btn btn-secondary">ยกเลิก</button>
      </div>
    </form>
  </div>

  <br>
  <?php
  if (isset($_POST['cus_Name'])) {
      $customer_No = $newCustomerNo;
      $cus_Name = $_POST['cus_Name'];
      $cus_gender = $_POST['cus_gender'];
      $cus_Address = $_POST['cus_Address'];
      $cus_phone = $_POST['cus_phone'];

      // ตรวจสอบและจัดการการอัพโหลดไฟล์
      if ($_FILES['cus_pic']['name']) {
          $cus_pic = $_FILES['cus_pic']['name'];
          $target_file = "../../assets/pic/" . $cus_pic;

          if (move_uploaded_file($_FILES['cus_pic']['tmp_name'], $target_file)) {
              echo "<p>ไฟล์ถูกอัพโหลดสำเร็จ</p>";
          } else {
              echo "<p>Error: ไม่สามารถอัพโหลดไฟล์ได้</p>";
          }
      } else {
          $cus_pic = "";
      }

      // Insert Data
      $table = "customers";
      $sql = "INSERT INTO $table (customer_No, cus_Name, cus_gender, cus_Address, cus_phone, cus_pic)
              VALUES ('$customer_No', '$cus_Name', '$cus_gender', '$cus_Address', '$cus_phone', '$cus_pic')";

if (mysqli_query($conn, $sql)) {
  echo "<script>
      Swal.fire({
          title: 'สำเร็จ!',
          text: 'เพิ่มข้อมูลลูกค้าใหม่แล้ว',
          icon: 'success',
          confirmButtonText: 'OK',
          customClass: {
              confirmButton: 'btn btn-success',
          }
      }).then((result) => {
          if (result.isConfirmed) {
              window.location = 'customer_list.php';
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
