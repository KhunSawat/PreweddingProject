<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดชุด</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
        /* ทำให้ภาพทั้งหมดใน Carousel มีขนาดเท่ากัน */
        .carousel-item img {
            width: 100%;
            /* ให้ครอบคลุมความกว้าง */
            height: auto;
            /* ให้ปรับความสูงอัตโนมัติเพื่อรักษาสัดส่วน */
            max-width: 500px;
            /* จำกัดขนาดด้านกว้างสุด */
            max-height: 500px;
            /* จำกัดขนาดด้านสูงสุด */
            aspect-ratio: 1 / 1;
            /* กำหนดสัดส่วนภาพเป็น 1:1 */
            object-fit: contain;
            /* ปรับภาพให้คงสัดส่วนโดยครอบคลุมพื้นที่ */
            margin: auto;
            /* จัดกึ่งกลาง */
        }

        /* ปรับแต่งปุ่มให้มีพื้นหลังสีขาวและอยู่ตรงกลางแนวตั้ง */
        .custom-carousel-control {
            width: 50px;
            height: 50px;
            background-color: #ffffff;
            border: 1px solid #ccc;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            top: 50%;
            /* ปุ่มอยู่ตรงกลางแนวตั้ง */
            transform: translateY(-50%);
            /* ปรับให้ปุ่มอยู่กึ่งกลาง */
        }

    /* ปรับตำแหน่งปุ่ม Previous */
    .carousel-control-prev {
        left: 15px; /* ขยับเข้ามา 15px จากขอบ Carousel */
    }

    /* ปรับตำแหน่งปุ่ม Next */
    .carousel-control-next {
        right: 15px; /* ขยับเข้ามา 15px จากขอบ Carousel */
    }
        /* เปลี่ยนไอคอนในปุ่ม */
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: #000;
            /* เปลี่ยนไอคอนเป็นสีดำ */
            border-radius: 50%;
            /* ทำให้ไอคอนดูสมมาตร */
        }

        /* เพิ่มเอฟเฟกต์ Hover */
        .custom-carousel-control:hover {
            background-color: #f0f0f0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        /* ซ่อนข้อความ visually-hidden */
        .custom-carousel-control .visually-hidden {
            display: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title text-center mt-5">รายละเอียดชุด</h4>
                        </div>
                        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                            <div class="d-flex w-100 justify-content-between py-2 py-md-0">
                                <a href="./DressReturnList.php" class="btn btn-label-info btn-round ms-4 mt-3">
                                    <i class="fas fa-arrow-left"></i> ย้อนกลับ
                                </a>
                                <a class="btn btn-label-success btn-round me-4 mt-3"
                                    onclick="selectDress('<?php echo $row['Dressed_No']; ?>', '<?php echo $row['Dressed_Name']; ?>', '<?php echo $bookingH_No; ?>')">
                                    <i class="fas fa-check-circle"></i> เลือกชุด
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <?php
                                include("../../config/dbcon.php");

                                // รับรหัส Dressed_No และ BookingH_No จาก URL
                                $dressedNo = isset($_GET['Dressed_No']) ? $_GET['Dressed_No'] : '';
                                $bookingH_No = isset($_GET['BookingH_No']) ? $_GET['BookingH_No'] : '';

                                // ดึงข้อมูลชุดจากตาราง Dressed
                                $sql = "SELECT * FROM Dressed WHERE Dressed_No = '$dressedNo'";
                                $result = mysqli_query($conn, $sql);
                                $row = mysqli_fetch_assoc($result);

                                // แสดงรายละเอียดชุด
                                if ($row) {
                                    echo '
                                        <div class="col-md-6">
                                            <div id="carouselExampleControlsNoTouching" class="carousel slide" data-bs-touch="false" data-bs-interval="false">
                                                <div class="carousel-inner">';

                                    // แสดงภาพในแกลเลอรี (carousel)
                                    $pics = ['D_Pic1', 'D_Pic2', 'D_Pic3', 'D_Pic4'];
                                    $first = true;
                                    foreach ($pics as $pic) {
                                        if (!empty($row[$pic])) {
                                            echo '  <div class="carousel-item' . ($first ? ' active' : '') . '">
                                                        <img src="../../assets/pic/dress/' . $row[$pic] . '" class="card-img-top d-block w-100" alt="' . $row['Dressed_Name'] . '">
                                                    </div>';
                                            $first = false;
                                        }
                                    }

                                    echo '  </div>
                                                    <button class="carousel-control-prev custom-carousel-control" type="button" data-bs-target="#carouselExampleControlsNoTouching" data-bs-slide="prev">
                                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">Previous</span>
                                                    </button>
                                                    <button class="carousel-control-next custom-carousel-control" type="button" data-bs-target="#carouselExampleControlsNoTouching" data-bs-slide="next">
                                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                        <span class="visually-hidden">Next</span>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card-body">
                                                    <h5 class="card-title">' . $row['Dressed_Name'] . '</h5><br>
                                                    <p class="card-text">สี: ' . $row['D_Color'] . '</p>
                                                    <p class="card-text">ขนาด: ' . $row['size'] . '</p>
                                                    <p class="card-text">รอบอก: ' . $row['Bust'] . '</p>
                                                    <p class="card-text">เอว: ' . $row['Waist'] . '</p>
                                                    <p class="card-text">สะโพก: ' . $row['Hip'] . '</p>
                                                    <p class="card-text">ยาว: ' . $row['long'] . '</p>
                                                    <p class="card-text">วัสดุ: ' . $row['material'] . '</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card-body">
                                                    <p class="card-text mt-5">
                                                        รายละเอียดเพิ่มเติม: 
                                                        <p class="card-text">' . $row['D_detail'] . '</p>
                                                        
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="card-body">
                                                    <div class="h4 text-end"><span>ราคาเช่า: </span><strong> ' . number_format($row['D_price'], 2) . '</strong> บาท</div>
                                                </div>
                                            </div>';
                                } else {
                                    echo '<p>ไม่พบข้อมูลชุด</p>';
                                }
                                ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="page-inner">
                <h1></h1>

            </div>
        </div>


        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
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
            function selectDress(dressNo, dressName, bookingHNo) {
                Swal.fire({
                    title: 'คุณต้องการเลือกชุดนี้ใช่ไหม?',
                    text: dressName,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ใช่, เลือกชุดนี้'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // ส่งข้อมูลไปที่ไฟล์ save_return.php ด้วย AJAX โดยไม่ต้องส่ง customersNo
                        $.ajax({
                            url: 'save_return.php',
                            type: 'POST',
                            data: {
                                dressedNo: dressNo,
                                bookingHNo: bookingHNo
                            },
                            success: function(response) {
                                console.log("Response from server: ", response); // แสดงผลการตอบกลับจากเซิร์ฟเวอร์ในคอนโซล
                                let data = JSON.parse(response); // แปลงข้อมูลที่ได้รับกลับเป็น JSON
                                if (data.status === 'success') {
                                    Swal.fire(
                                        'สำเร็จ!',
                                        'ชุดถูกบันทึกลงในตารางเบิกคืนแล้ว',
                                        'success'
                                    ).then(() => {
                                        window.location.href = 'DressReturnList.php?BookingH_No=' + bookingHNo;
                                    });
                                } else {
                                    Swal.fire(
                                        'เกิดข้อผิดพลาด!',
                                        data.message, // แสดงข้อความผิดพลาดจากเซิร์ฟเวอร์
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log('Error: ', error); // แสดงข้อผิดพลาดที่เกิดขึ้นในคอนโซล
                                Swal.fire(
                                    'เกิดข้อผิดพลาด!',
                                    'ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่',
                                    'error'
                                );
                            }
                        });
                    }
                });
            }
        </script>
</body>

</html>