<?php include('../../config/dbcon.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>รายชื่อชุด</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <!-- Fonts and icons -->
    <script src="../../assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["../../assets/css/fonts.min.css"],
            },
            active: function() {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../assets/css/plugins.min.css" />
    <link rel="stylesheet" href="../../assets/css/kaiadmin.min.css" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="../../assets/css/demo.css" />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .image-gallery {
            display: flex;
            gap: 16px;
        }

        .gallery-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .gallery-image:hover {
            transform: scale(2);
        }

        .accordion-button:focus {
            box-shadow: none;
        }
    </style>
</head>


<body>
    <?php
    $table = "dressed";
    $jointable = "dressed_type";
    $sql_select = "SELECT * FROM $table 
    INNER JOIN $jointable ON $table.Dtype_No = $jointable.Dtype_No 
    ORDER BY $table.Dressed_No";
    $result = mysqli_query($conn, $sql_select);
    ?>
    <div class="wrapper">
        <?php include('../../includes/sidebar.php'); ?>
        <div class="main-panel"> <!-- เนื้อหาอยู่ในส่วนนี้ -->
            <div class="container mt-0">
                <div class="page-inner">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title text-center mt-5">รายการชุด</h4>
                                </div>
                                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                                    <div class="d-flex w-100 justify-content-between py-2 py-md-0">
                                        <a href="../../index.php" class="btn btn-label-info btn-round ms-4 mt-3">
                                            <i class="fas fa-home"></i> กลับหน้าแรก
                                        </a>
                                        <a href="dress_fm.php" class="btn btn-label-info btn-round me-4 mt-3">
                                            <i class="fas fa-plus"></i> เพิ่มรายการ
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="basic-datatables" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ลำดับ</th>
                                                    <th>รหัสชุด</th>
                                                    <th>ชื่อ</th>
                                                    <th>สี</th>
                                                    <th>รูป</th>
                                                    <th>ขนาด</th>
                                                    <th class="d-none">ประเภท</th>
                                                    <th>ราคา</th>
                                                    <th>รายละเอียด</th>
                                                    <th>แก้ไข</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (mysqli_num_rows($result) > 0) {
                                                    $num = 1;
                                                    while ($row = mysqli_fetch_array($result)) {
                                                ?>
                                                        <tr>
                                                            <th scope='row'><?= $num ?></th>
                                                            <td><?= $row['Dressed_No']; ?></td>
                                                            <td><?= $row['Dressed_Name']; ?></td>
                                                            <td><?= $row['D_Color']; ?></td>
                                                            <td>
                                                                <?php
                                                                $pics = ['D_Pic1', 'D_Pic2', 'D_Pic3', 'D_Pic4'];
                                                                echo '<div class="image-gallery">';
                                                                foreach ($pics as $pic) {
                                                                    if (!empty($row[$pic])) {
                                                                        echo "<img src='../../assets/pic/dress/{$row[$pic]}' alt='none' class='gallery-image' onerror='this.onerror=null;'>";
                                                                    }
                                                                }
                                                                echo '</div>';
                                                                ?>
                                                            </td>
                                                            <td><?= $row['size']; ?></td>
                                                            <td class="d-none"><?= $row['Dtype_Name']; ?></td>
                                                            <td><?= $row['D_price']; ?></td>
                                                            <td>
                                                                <button class="btn btn-secondary btn-details" data-id="<?= $num ?>" data-type="<?= $row['Dtype_Name'] ?>" data-bust="<?= $row['Bust'] ?>" data-waist="<?= $row['Waist'] ?>" data-hip="<?= $row['Hip'] ?>" data-long="<?= $row['long'] ?>" data-material="<?= $row['material'] ?>" data-detail="<?= $row['D_detail'] ?>">
                                                                    ดู
                                                                </button>
                                                            </td>
                                                            <td>
                                                                <a href='dress_edit.php?Dressed_No=<?= $row['Dressed_No']; ?>' class='btn btn-info mb-1 d-block w-100'>
                                                                    <i class="fas fa-wrench"></i> <!-- ไอคอนสำหรับแก้ไข -->
                                                                </a>
                                                                <a href='dress_list.php?Dressed_No=<?= $row['Dressed_No']; ?>&action=delete' onClick="return confirm('คุณต้องการที่จะลบข้อมูลนี้หรือไม่ ?')" class='btn btn-danger mb-1 d-block w-100'>
                                                                    <i class="fas fa-trash-alt"></i> <!-- ไอคอนสำหรับลบ -->
                                                                </a>
                                                            </td>
                                                        </tr>
                                                <?php
                                                        $num++;
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                            <?php
                                            if (isset($_GET['Dressed_No']) && isset($_GET['action']) && $_GET['action'] == 'delete') {
                                                $Dressed_No = mysqli_real_escape_string($conn, $_GET['Dressed_No']);
                                                $sql_delete = "DELETE FROM $table WHERE Dressed_No='$Dressed_No'";

                                                if (mysqli_query($conn, $sql_delete)) {
                                                    echo "<script>
                                                Swal.fire({
                                                    title: 'สำเร็จ!',
                                                    text: 'ลบข้อมูลรหัส $Dressed_No สำเร็จแล้ว',
                                                    icon: 'success',
                                                    confirmButtonText: 'OK',
                                                    customClass: {
                                                        confirmButton: 'btn btn-success',
                                                    }
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        window.location.href = '$_SERVER[PHP_SELF]';
                                                    }
                                                });
                                            </script>";
                                                } else {
                                                    echo "Error deleting record: " . mysqli_error($conn);
                                                }
                                            }
                                            ?>
                                        </table>
                                        <a href="../report/ReportDress.php" target="_blank">
                                            <button class="btn btn-primary">Export PDF</button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Core JS Files -->
                <script src="../../assets/js/core/jquery-3.7.1.min.js"></script>
                <script src="../../assets/js/core/popper.min.js"></script>
                <script src="../../assets/js/core/bootstrap.min.js"></script>

                <!-- jQuery Scrollbar -->
                <script src="../../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
                <!-- Datatables -->
                <script src="../../assets/js/plugin/datatables/datatables.min.js"></script>

                <!-- Library for Sweet Alerts -->
                <script src="../../assets/js/plugin/sweetalert2/sweetalert2.min.js"></script>

                <!-- Main JS File -->
                <script src="../../assets/js/kaiadmin.min.js"></script>
                <script src="../../assets/js/demo.js"></script>

                <script>
                    $(document).ready(function() {
                        $("#basic-datatables").DataTable({});

                        // Handle details button click
                        $('.btn-details').on('click', function() {
                            var button = $(this);
                            var id = button.data('id');
                            var type = button.data('type');
                            var bust = button.data('bust');
                            var waist = button.data('waist');
                            var hip = button.data('hip');
                            var long = button.data('long');
                            var material = button.data('material');
                            var detail = button.data('detail');

                            Swal.fire({
                                title: 'รายละเอียด',
                                html: `<div><strong>ประเภท :</strong> ${type} </div>
                               <div><strong>ขนาด :</strong></div>
                               <div>รอบอก : ${bust}</div>
                               <div>เอว : ${waist}</div>
                               <div>สะโพก : ${hip}</div>
                               <div>ยาว : ${long}</div>
                               <div><strong>วัสดุ :</strong> ${material}</div><br>
                               <div><strong>รายละเอียดเพิ่มเติม :</strong></div>
                               <div>${detail}</div>`,
                                icon: 'info',
                                confirmButtonText: 'ปิด',
                                customClass: {
                                    confirmButton: 'btn btn-success',
                                }
                            });
                        });
                    });
                </script>
            </div>
        </div>
    </div>


</body>

</html>