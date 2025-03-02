<?php
session_start();
include("../../config/dbcon.php");

// ฟังก์ชันในการสร้างหมายเลขสินค้าใหม่
function generateDressedNumber($conn){
    $sql = "SELECT MAX(CAST(SUBSTRING(Dressed_No, 4) AS UNSIGNED)) AS max_number FROM dressed";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);

    $max_number = $row['max_number'] ? $row['max_number'] : 0;
    $new_number = $max_number + 1;

    return 'DRS' . str_pad($new_number, 3, '0', STR_PAD_LEFT);
}

$newDressedNo = generateDressedNumber($conn);
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มข้อมูลชุดใหม่</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="card text-center">
        <div class="card-header mt-3">
            <div class="label"><a href="dress_list.php">รายการชุด</a> >> เพิ่มข้อมูลชุดใหม่</div>
        </div>
    </div>

    <div class="container mt-3">
        <form action="" method="post" enctype="multipart/form-data" class="col-6">
            <div class="form-group">
                <label>รหัสชุด</label>
                <input type="text" name="Dressed_No" class="form-control" readonly value="<?= $newDressedNo ?>">
            </div>
            <div class="form-group">
                <label>ชื่อชุด</label>
                <input type="text" name="Dressed_Name" class="form-control" placeholder="กรอกชื่อสินค้า" required>
            </div>
            <div class="form-group">
                <label>สี</label>
                <input type="text" name="D_Color" class="form-control" placeholder="กรอกสีสินค้า" required>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>รูปภาพ 1</label>
                        <input type="file" name="D_Pic1" class="form-control" accept="image/*" onchange="previewImage(event, 'preview1')" required>
                        <img id="preview1" src="#" alt="Preview Image 1" style="display: none; margin-top: 10px;object-fit: cover;" width="200px" height="200px">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>รูปภาพ 2</label>
                        <input type="file" name="D_Pic2" class="form-control" accept="image/*" onchange="previewImage(event, 'preview2')">
                        <img id="preview2" src="#" alt="Preview Image 2" style="display: none; margin-top: 10px;object-fit: cover;" width="200px" height="200px">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>รูปภาพ 3</label>
                        <input type="file" name="D_Pic3" class="form-control" accept="image/*" onchange="previewImage(event, 'preview3')">
                        <img id="preview3" src="#" alt="Preview Image 3" style="display: none; margin-top: 10px;object-fit: cover;" width="200px" height="200px">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>รูปภาพ 4</label>
                        <input type="file" name="D_Pic4" class="form-control" accept="image/*" onchange="previewImage(event, 'preview4')">
                        <img id="preview4" src="#" alt="Preview Image 4" style="display: none; margin-top: 10px;object-fit: cover;" width="200px" height="200px">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>ขนาด</label>
                <select name="size" class="form-control" id="sizeDropdown" required onchange="toggleCustomSize()">
                <option value="None" disabled selected>เลือกขนาดชุด</option>
                    <option value="XS">XS</option>
                    <option value="S">S</option>
                    <option value="M">M</option>
                    <option value="L">L</option>
                    <option value="XL">XL</option>
                    <option value="2XL">2XL</option>
                </select>
            </div>

            <div class="form-group">
                <label>รอบอก</label>
                <input type="number" name="Bust" class="form-control" placeholder="กรอกรอบอก (ซม.)" required>
            </div>
            <div class="form-group">
                <label>รอบเอว</label>
                <input type="number" name="Waist" class="form-control" placeholder="กรอกรอบเอว (ซม.)" required>
            </div>
            <div class="form-group">
                <label>รอบสะโพก</label>
                <input type="number" name="Hip" class="form-control" placeholder="กรอกรอบสะโพก (ซม.)" required>
            </div>
            <div class="form-group">
                <label>ความยาว</label>
                <input type="number" name="long" class="form-control" placeholder="กรอกความยาว (ซม.)" required>
            </div>
            <div class="form-group">
                <label>วัสดุ</label>
                <input type="text" name="material" class="form-control" placeholder="กรอกวัสดุที่ใช้" required>
            </div>
            <div class="form-group">
                <label>รายละเอียดสินค้า</label>
                <textarea class="form-control" name="D_detail" rows="3" placeholder="กรอกรายละเอียดสินค้า"></textarea>
            </div>
            <div class="form-group">
                <label>ประเภทสินค้า</label>
                <select name="Dtype_No" class="form-control" required>
                    <?php
                    $sql = "SELECT Dtype_No, Dtype_Name FROM dressed_type";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['Dtype_No'] . "'>" . $row['Dtype_Name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>ราคา (บาท)</label>
                <input type="number" name="D_price" class="form-control" placeholder="กรอกราคา" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">บันทึก</button>
                <button type="reset" class="btn btn-secondary">ยกเลิก</button>
            </div>
        </form>
    </div>

    <br>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $Dressed_No = $newDressedNo;
        $Dressed_Name = $_POST['Dressed_Name'];
        $D_Color = $_POST['D_Color'];
        $size = $_POST['size'];
        $Bust = (int)$_POST['Bust'];
        $Waist = (int)$_POST['Waist'];
        $Hip = (int)$_POST['Hip'];
        $long = (int)$_POST['long'];
        $material = $_POST['material'];
        $D_detail = $_POST['D_detail'];
        $Dtype_No = $_POST['Dtype_No'];
        $D_price = (int)$_POST['D_price'];

        $D_Pic1 = '';
        $D_Pic2 = '';
        $D_Pic3 = '';
        $D_Pic4 = '';

        // การอัปโหลดรูปภาพ
        if (isset($_FILES['D_Pic1']['name']) && $_FILES['D_Pic1']['name'] != "") {
            $D_Pic1 = uploadImage($_FILES['D_Pic1']);
        }
        if (isset($_FILES['D_Pic2']['name']) && $_FILES['D_Pic2']['name'] != "") {
            $D_Pic2 = uploadImage($_FILES['D_Pic2']);
        }
        if (isset($_FILES['D_Pic3']['name']) && $_FILES['D_Pic3']['name'] != "") {
            $D_Pic3 = uploadImage($_FILES['D_Pic3']);
        }
        if (isset($_FILES['D_Pic4']['name']) && $_FILES['D_Pic4']['name'] != "") {
            $D_Pic4 = uploadImage($_FILES['D_Pic4']);
        }

        $sql = "INSERT INTO dressed (Dressed_No, Dressed_Name, D_Color, Bust, Waist, Hip, `long`, material, D_detail, Dtype_No, D_Pic1, D_Pic2, D_Pic3, D_Pic4, D_price)
                VALUES ('$Dressed_No', '$Dressed_Name', '$D_Color', $Bust, $Waist, $Hip, $long, '$material', '$D_detail', '$Dtype_No', '$D_Pic1', '$D_Pic2', '$D_Pic3', '$D_Pic4', $D_price)";

        if (mysqli_query($conn, $sql)) {
            echo "<script>Swal.fire({ icon: 'success', title: 'บันทึกข้อมูลสำเร็จ', text: 'เพิ่มข้อมูลสินค้าใหม่เรียบร้อยแล้ว' });</script>";
        } else {
            echo "<script>Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: 'ไม่สามารถบันทึกข้อมูลได้: " . mysqli_error($conn) . "' });</script>";
        }
    }

    function uploadImage($file)
    {
        $target_dir = "../../assets/pic/dress/";
        $target_file = $target_dir . basename($file["name"]);
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return basename($file["name"]); // ส่งคืนชื่อไฟล์
        } else {
            return ""; // หากไม่สามารถอัปโหลดได้
        }
    }

    function toggleCustomSize() {
        echo "<script>
                const sizeDropdown = document.getElementById('sizeDropdown');
                const customSizeInput = document.getElementById('customSizeInput');

                sizeDropdown.addEventListener('change', function() {
                    if (this.value === 'other') {
                        customSizeInput.style.display = 'block';
                        customSizeInput.required = true;
                    } else {
                        customSizeInput.style.display = 'none';
                        customSizeInput.required = false;
                    }
                });
              </script>";
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
