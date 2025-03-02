<?php
session_start();
include("../../config/dbcon.php");

if (isset($_GET['Dressed_No'])) {
    $Dressed_No = $_GET['Dressed_No'];
    $sql_select = "SELECT * FROM dressed WHERE Dressed_No = '$Dressed_No'";
    $result = mysqli_query($conn, $sql_select);
    $row = mysqli_fetch_array($result);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลชุด</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="card text-center">
        <div class="card-header mt-3">
            <div class="label"><a href="dress_list.php">รายการชุด</a> >> แก้ไขข้อมูลชุด</div>
        </div>
    </div>

    <div class="container mt-3">
        <form action="" method="post" enctype="multipart/form-data" class="col-6">
            <div class="form-group">
                <label>รหัสชุด</label>
                <input type="text" name="Dressed_No" class="form-control" readonly value="<?= $row['Dressed_No'] ?>">
            </div>
            <div class="form-group">
                <label>ชื่อชุด</label>
                <input type="text" name="Dressed_Name" class="form-control" placeholder="กรอกชื่อสินค้า" value="<?= $row['Dressed_Name'] ?>" required>
            </div>
            <div class="form-group">
                <label>สี</label>
                <input type="text" name="D_Color" class="form-control" placeholder="กรอกสีสินค้า" value="<?= $row['D_Color'] ?>" required>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>รูปภาพ 1</label>
                        <input type="file" name="D_Pic1" class="form-control" accept="image/*" onchange="previewImage(event, 'preview1')">
                        <img id="preview1" src="../../assets/pic/dress/<?= $row['D_Pic1'] ?>" alt="Preview Image 1" style="display: block; margin-top: 10px;object-fit: cover;" width="200px" height="200px">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>รูปภาพ 2</label>
                        <input type="file" name="D_Pic2" class="form-control" accept="image/*" onchange="previewImage(event, 'preview2')">
                        <img id="preview2" src="../../assets/pic/dress/<?= $row['D_Pic2'] ?>" alt="Preview Image 2" style="display: block; margin-top: 10px;object-fit: cover;" width="200px" height="200px">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>รูปภาพ 3</label>
                        <input type="file" name="D_Pic3" class="form-control" accept="image/*" onchange="previewImage(event, 'preview3')">
                        <img id="preview3" src="../../assets/pic/dress/<?= $row['D_Pic3'] ?>" alt="Preview Image 3" style="display: block; margin-top: 10px;object-fit: cover;" width="200px" height="200px">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>รูปภาพ 4</label>
                        <input type="file" name="D_Pic4" class="form-control" accept="image/*" onchange="previewImage(event, 'preview4')">
                        <img id="preview4" src="../../assets/pic/dress/<?= $row['D_Pic4'] ?>" alt="Preview Image 4" style="display: block; margin-top: 10px;object-fit: cover;" width="200px" height="200px">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>ขนาด</label>
                <select name="size" class="form-control" id="sizeDropdown" required onchange="toggleCustomSize()">
                    <option value="" disabled>เลือกขนาดชุด</option>
                    <option value="XS" <?= $row['size'] == 'XS' ? 'selected' : '' ?>>XS</option>
                    <option value="S" <?= $row['size'] == 'S' ? 'selected' : '' ?>>S</option>
                    <option value="M" <?= $row['size'] == 'M' ? 'selected' : '' ?>>M</option>
                    <option value="L" <?= $row['size'] == 'L' ? 'selected' : '' ?>>L</option>
                    <option value="XL" <?= $row['size'] == 'XL' ? 'selected' : '' ?>>XL</option>
                    <option value="2XL" <?= $row['size'] == '2XL' ? 'selected' : '' ?>>2XL</option>
                </select>
            </div>

            <div class="form-group">
                <label>รอบอก</label>
                <input type="text" name="Bust" class="form-control" placeholder="กรอกรอบอก" value="<?= $row['Bust'] ?>" required>
            </div>
            <div class="form-group">
                <label>รอบเอว</label>
                <input type="text" name="Waist" class="form-control" placeholder="กรอกรอบเอว" value="<?= $row['Waist'] ?>" required>
            </div>
            <div class="form-group">
                <label>รอบสะโพก</label>
                <input type="text" name="Hip" class="form-control" placeholder="กรอกรอบสะโพก" value="<?= $row['Hip'] ?>" required>
            </div>
            <div class="form-group">
                <label>ความยาว</label>
                <input type="text" name="long" class="form-control" placeholder="กรอกความยาว" value="<?= $row['long'] ?>" required>
            </div>
            <div class="form-group">
                <label>วัสดุ</label>
                <input type="text" name="material" class="form-control" placeholder="กรอกวัสดุที่ใช้" value="<?= $row['material'] ?>" required>
            </div>
            <div class="form-group">
                <label>รายละเอียดสินค้า</label>
                <textarea class="form-control" name="D_detail" rows="3" placeholder="กรอกรายละเอียดสินค้า"><?= $row['D_detail'] ?></textarea>
            </div>
            <div class="form-group">
                <label>ประเภทสินค้า</label>
                <select name="Dtype_No" class="form-control" required>
                    <?php
                    $sql = "SELECT Dtype_No, Dtype_Name FROM dressed_type";
                    $result = mysqli_query($conn, $sql);
                    while ($dtype_row = mysqli_fetch_assoc($result)) {
                        $selected = $row['Dtype_No'] == $dtype_row['Dtype_No'] ? 'selected' : '';
                        echo "<option value='" . $dtype_row['Dtype_No'] . "' $selected>" . $dtype_row['Dtype_Name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>ราคา</label>
                <input type="number" name="D_price" class="form-control" placeholder="กรอกราคา" value="<?= $row['D_price'] ?>" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">แก้ไข</button>
                <a href="dress_list.php" class="btn btn-secondary">ยกเลิก</a>
            </div>
        </form>
    </div>

    <br>
    <?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Variables for text fields
    $Dressed_No = $_POST['Dressed_No'];
    $Dressed_Name = $_POST['Dressed_Name'];
    $D_Color = $_POST['D_Color'];
    $size = isset($_POST['custom_size']) && $_POST['size'] == 'other' ? $_POST['custom_size'] : $_POST['size'];
    $Bust = $_POST['Bust'];
    $Waist = $_POST['Waist'];
    $Hip = $_POST['Hip'];
    $long = $_POST['long'];
    $material = $_POST['material'];
    $D_detail = $_POST['D_detail'];
    $Dtype_No = $_POST['Dtype_No'];

    // File upload configuration
    $uploads_dir = '../../assets/pic/dress/';
    $allowed_types = array('jpg', 'jpeg', 'png', 'gif');

    // Initialize update SQL statement
    $update_sql = "UPDATE dressed SET Dressed_Name='$Dressed_Name', D_Color='$D_Color', size='$size', Bust='$Bust', Waist='$Waist', Hip='$Hip', `long`='$long', material='$material', D_detail='$D_detail', Dtype_No='$Dtype_No' WHERE Dressed_No='$Dressed_No'";

    // Handle image uploads
    $file_names = ['D_Pic1', 'D_Pic2', 'D_Pic3', 'D_Pic4'];
    $image_columns = ['D_Pic1', 'D_Pic2', 'D_Pic3', 'D_Pic4'];

    for ($i = 0; $i < count($file_names); $i++) {
        if (!empty($_FILES[$file_names[$i]]['name'])) {
            $file_name = basename($_FILES[$file_names[$i]]['name']);
            $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if (in_array($file_type, $allowed_types)) {
                $new_file_name = uniqid() . '.' . $file_type;
                $target_file = $uploads_dir . $new_file_name;

                // Move file to target directory
                if (move_uploaded_file($_FILES[$file_names[$i]]['tmp_name'], $target_file)) {
                    // Update SQL statement to include image
                    $update_sql .= ", " . $image_columns[$i] . "='$new_file_name'";
                } else {
                    echo "<script>Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: 'ไม่สามารถอัปโหลดรูปภาพได้' });</script>";
                    exit;
                }
            } else {
                echo "<script>Swal.fire({ icon: 'error', title: 'รูปภาพไม่ถูกต้อง', text: 'รองรับเฉพาะไฟล์ JPG, JPEG, PNG และ GIF เท่านั้น' });</script>";
                exit;
            }
        }
    }

    // Execute the final SQL statement
    if (mysqli_query($conn, $update_sql)) {
        echo "<script>
            Swal.fire({
                title: 'สำเร็จ!',
                text: 'แก้ไขข้อมูลรหัส $Dressed_No แล้ว',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = 'dress_list.php';
                }
            });
        </script>";
    } else {
        echo "<script>Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: 'ไม่สามารถบันทึกข้อมูลได้: " . mysqli_error($conn) . "' });</script>";
    }

    mysqli_close($conn);
}
?>


    <script>
        function toggleCustomSize() {
            const sizeDropdown = document.getElementById('sizeDropdown');
            const customSizeInput = document.getElementById('customSizeInput');

            if (sizeDropdown.value === 'other') {
                customSizeInput.style.display = 'block';
                customSizeInput.required = true;
            } else {
                customSizeInput.style.display = 'none';
                customSizeInput.required = false;
            }
        }

        function previewImage(event, previewId) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById(previewId);
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>
