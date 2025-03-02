<?php include("../../config/dbcon.php"); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
  <title>แก้ไขข้อมูลลูกค้า</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" crossorigin="anonymous">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" crossorigin="anonymous"></script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <?php
  if (isset($_GET['customer_No'])) {
    $customer_No = $_GET['customer_No'];
    $table = "customers";
    $sql_select = "SELECT * FROM $table WHERE customer_No ='$customer_No'";
    $result = mysqli_query($conn, $sql_select);
    $row = mysqli_fetch_array($result);
  }
  ?>

  <div class="card text-center">
    <div class="card-header mt-3">
      <div class="label"><a href="customer_list.php">รายชื่อลูกค้า</a> >>แก้ไขข้อมูลลูกค้า</div>
    </div>
  </div>

  <div class="container mt-3">
    <form action="" method="post" enctype="multipart/form-data" class="col-6">

      <div class="form-group">
        <label>รหัสลูกค้า</label>
        <input type="text" name="customer_No" class="form-control" readonly value="<?= $row[0] ?>">
      </div>

      <div class="form-group">
        <label>ชื่อ-สกุล</label>
        <input type="text" name="cus_Name" class="form-control" placeholder="กรอกชื่อ-นามสกุล" value="<?= $row['cus_Name']; ?>" required>
      </div>

      <div class="form-group">
        <label for="">เพศ :</label>
        <div class="custom-control custom-radio custom-control-inline">
          <input type="radio" value="M" id="customRadioInline1" name="cus_gender" class="custom-control-input"
            <?= $row['cus_gender'] == "M" ? "checked" : "" ?>>
          <label class="custom-control-label" for="customRadioInline1">ชาย</label>
        </div>

        <div class="custom-control custom-radio custom-control-inline">
          <input type="radio" value="F" id="customRadioInline2" name="cus_gender" class="custom-control-input"
            <?= $row['cus_gender'] == "F" ? "checked" : "" ?>>
          <label class="custom-control-label" for="customRadioInline2">หญิง</label>
        </div>
      </div>

      <div class="form-group">
        <label for="exampleFormControlTextarea1">ที่อยู่</label>
        <textarea class="form-control" name="cus_Address" rows="3"><?= $row['cus_Address'] ?></textarea>
      </div>

      <div class="form-group">
        <label>เบอร์โทรศัพท์</label>
        <input type="text" name="cus_phone" class="form-control" placeholder="กรอกเบอร์โทรศัพท์" required value="<?= $row['cus_phone'] ?>">
      </div>

      <div class="form-group">
        <label>รูปภาพ</label>
        <div class="ml-3 mb-3">
          <img src="../../assets/pic/<?= $row[3]; ?>" width='200px' height="200px" alt="none" style="object-fit: cover;">
        </div>
        <input type="file" name="cus_pic" class="form-control">

      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-primary">แก้ไข</button>
        <button type="cancel" class="btn btn-secondary">ยกเลิก</button>
      </div>
    </form>
  </div>

  <br>
  <?php
include('../../config/dbcon.php'); // เชื่อมต่อฐานข้อมูล

if (isset($_POST['cus_Name'])) {
    $customer_No = mysqli_real_escape_string($conn, $_POST['customer_No']);
    $cus_Name = mysqli_real_escape_string($conn, $_POST['cus_Name']);
    $cus_gender = mysqli_real_escape_string($conn, $_POST['cus_gender']);
    $cus_Address = mysqli_real_escape_string($conn, $_POST['cus_Address']);
    $cus_phone = mysqli_real_escape_string($conn, $_POST['cus_phone']);

    if ($_FILES['cus_pic']['name']) {
        $cus_pic = basename($_FILES['cus_pic']['name']);
        $target_path = "../../assets/pic/" . $cus_pic;
        
        // ตรวจสอบว่าการอัปโหลดไฟล์สำเร็จหรือไม่
        if (move_uploaded_file($_FILES['cus_pic']['tmp_name'], $target_path)) {
            $pic_update = ", cus_pic='$cus_pic'";
        } else {
            $pic_update = "";
        }
    } else {
        $pic_update = "";
    }

    $sql_update = "UPDATE $table SET
                   cus_Name = '$cus_Name',
                   cus_gender = '$cus_gender',
                   cus_Address = '$cus_Address',
                   cus_phone = '$cus_phone'
                   $pic_update
                   WHERE customer_No = '$customer_No'";

    if (mysqli_query($conn, $sql_update)) {
        echo "<script>
            Swal.fire({
                title: 'สำเร็จ!',
                text: 'แก้ไขข้อมูลรหัส $customer_No แล้ว',
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
