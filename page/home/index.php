<?php
session_start();
include('../../config/loginchk.php');
?>
<?php include('../../includes/header.php') ?>
<div class="wrapper">
  <?php include('../../includes/sidebar.php') ?>

  <div class="main-panel">
    <div class="main-header">
      <div class="main-header-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
          <a href="index.html" class="logo">
            <img
              src="../../assets/img/kaiadmin/logo_light.svg"
              alt="navbar brand"
              class="navbar-brand"
              height="20" />
          </a>
          <div class="nav-toggle">
            <button class="btn btn-toggle toggle-sidebar">
              <i class="gg-menu-right"></i>
            </button>
            <button class="btn btn-toggle sidenav-toggler">
              <i class="gg-menu-left"></i>
            </button>
          </div>
          <button class="topbar-toggler more">
            <i class="gg-more-vertical-alt"></i>
          </button>
        </div>
        <!-- End Logo Header -->
      </div>
      <?php include('../../includes/navbar.php') ?>
    </div>

    <div class="container">
      <div class="page-inner">
        <div
          class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
          <div>
            <h3 class="fw-bold mb-3">หน้าแรก</h3>
          </div>
          <div class="ms-md-auto py-2 py-md-0">
            <a href="../customer/Customer_fm.php" class="btn btn-label-info btn-round me-2">เพิ่มลูกค้า</a>
            <a href="../work/search_booking.php" class="btn btn-label-info btn-round me-2">จัดการงาน</a>
            <a href="../work/booking_fm.php" class="btn btn-primary btn-round">เพิ่มรายการจองคิว</a>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col-icon">
                    <div
                      class="icon-big text-center icon-secondary bubble-shadow-small">
                      <i class="icon-graph"></i>
                    </div>
                  </div>
                  <?php
                  $sql_count = "SELECT COUNT(*) AS queue_count FROM booking_h WHERE work_status_No = '01'";
                  $result = mysqli_query($conn, $sql_count);
                  $row = mysqli_fetch_assoc($result);
                  $queue_count = $row['queue_count'];
                  ?>
                  <div class="col col-stats ms-3 ms-sm-0">
                    <div class="numbers">
                      <p class="card-category">กำลังดำเนินการ(คิว)</p>
                      <h4 class="card-title"><?php echo $queue_count; ?></h4> <!-- แสดงจำนวนคิว -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col-icon">
                    <div
                      class="icon-big text-center icon-info bubble-shadow-small">
                      <i class="fas fa-user-check"></i>
                    </div>
                  </div>
                  <?php
                  $sql_count = "SELECT COUNT(*) AS cus_count FROM customers";
                  $result = mysqli_query($conn, $sql_count);
                  $row = mysqli_fetch_assoc($result);
                  $cus_count = $row['cus_count'];
                  ?>
                  <div class="col col-stats ms-3 ms-sm-0">
                    <div class="numbers">
                      <p class="card-category">จำนวนลูกค้าทั้งหมด</p>
                      <h4 class="card-title"><?php echo $cus_count; ?></h4>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col-icon">
                    <div
                      class="icon-big text-center icon-success bubble-shadow-small">
                      <i class="fas fa-luggage-cart"></i>
                    </div>
                  </div>
                  <?php
                  $sql_count = "SELECT COUNT(*) AS return_count FROM `return` WHERE Return_status_No = '01'";
                  $result = mysqli_query($conn, $sql_count);
                  $row = mysqli_fetch_assoc($result);
                  $return_count = $row['return_count'];
                  ?>
                  <div class="col col-stats ms-3 ms-sm-0">
                    <div class="numbers">
                      <p class="card-category">จำนวนชุดรอคืน</p>
                      <h4 class="card-title"><?php echo $return_count; ?></h4>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col-icon">
                    <div
                      class="icon-big text-center icon-secondary bubble-shadow-small">
                      <i class="fas fa-hand-holding-usd"></i>
                    </div>
                  </div>
                  <?php
                  // ตั้งค่าภาษาไทยสำหรับการแสดงผลวันที่
                  setlocale(LC_TIME, 'th_TH.UTF-8', 'thai');

                  // แทนที่ strftime ด้วย IntlDateFormatter เพื่อดึงชื่อเดือนปัจจุบันเป็นภาษาไทย
                  $formatter = new IntlDateFormatter('th_TH', IntlDateFormatter::LONG, IntlDateFormatter::NONE, 'Asia/Bangkok', IntlDateFormatter::GREGORIAN, 'MMMM');
                  $currentMonth = $formatter->format(new DateTime());

                  // แปลงชื่อเดือนให้เป็นตัวพิมพ์ใหญ่ตัวแรก
                  $currentMonth = ucfirst($currentMonth);
                  ?>
                  <div class="col col-stats ms-3 ms-sm-0">
                    <div class="numbers">
                      <p class="card-category">รายจ่ายเดือน<?php echo $currentMonth; ?></p>
                      <?php
                      setlocale(LC_TIME, 'th_TH.UTF-8', 'thai');

                      // แทนที่ strftime ด้วย IntlDateFormatter เพื่อดึงชื่อเดือนปัจจุบันเป็นภาษาไทย
                      $formatter = new IntlDateFormatter('th_TH', IntlDateFormatter::LONG, IntlDateFormatter::NONE, 'Asia/Bangkok', IntlDateFormatter::GREGORIAN, 'MMMM');
                      $Month = $formatter->format(new DateTime());

                      // แปลงชื่อเดือนให้เป็นตัวพิมพ์ใหญ่ตัวแรก
                      $Month = ucfirst($Month);
                      // คำสั่ง SQL เพื่อรวมยอดรายจ่ายของเดือนปัจจุบัน
                      $currentMonth = date('m');
                      $currentYear = date('Y');
                      $sql_total_expense = "SELECT SUM(Expenses) AS total_expense FROM Expense 
                                              WHERE MONTH(E_DateTime) = '$currentMonth' AND YEAR(E_DateTime) = '$currentYear'";
                      $result_Filter = mysqli_query($conn, $sql_total_expense);
                      $row = mysqli_fetch_assoc($result_Filter);
                      $total_expense = $row['total_expense'] ? $row['total_expense'] : 0; // ตรวจสอบว่ายอดรายจ่ายไม่เป็น null
                      ?>
                      <h4 class="card-title"><?php echo number_format($total_expense, 2); ?></h4>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card card-round">
              <div class="card-header">
                <div class="card-head-row card-tools-still-right">
                  <div class="card-title">ปฏิทินการจองคิว</div>
                </div>
              </div>
              <div class="card-body ">
                <div class="table-responsive">
                  <?php include('../../components/calendar.php'); ?>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card card-round">
              <div class="card-header">
                <div class="card-head-row card-tools-still-right">
                  <div class="card-title">สถานะงาน</div>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <!-- Projects table -->
                  <?php include("./status_list.php") ?>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card">
              <div class="card-header d-flex justify-content-between">
                <div class="card-title">สถิติงาน</div>
                <div>
                  <button class="btn btn-outline-secondary btn-sm" onclick="changeYear(-1)">&lt;</button>
                  <button class="btn btn-outline-secondary btn-sm" onclick="changeYear(1)">&gt;</button>
                </div>
              </div>
              <div class="card-body">
                <h5 class="text-center" id="chartYear">ปี <?php echo date("Y") + 543; ?></h5>
                <div class="chart-container">
                  <canvas id="barChart"></canvas>
                </div>
              </div>
            </div>
          </div>

          <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
          <script>
            var currentYear = new Date().getFullYear();
            var barChart;

            function updateChart(data) {
              document.getElementById("chartYear").innerText = `ปี ${data.year}`;
              barChart.data.datasets[0].data = data.jobCounts;
              barChart.update();
            }

            function changeYear(amount) {
              currentYear += amount;
              fetch("update_chart.php", {
                  method: "POST",
                  headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                  },
                  body: `year=${currentYear}`,
                })
                .then(response => response.json())
                .then(data => updateChart(data))
                .catch(error => console.error("Error:", error));
            }

            window.onload = function() {
              var ctx = document.getElementById("barChart").getContext("2d");
              barChart = new Chart(ctx, {
                type: "bar",
                data: {
                  labels: ["ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค."],
                  datasets: [{
                    label: "จำนวนงานในแต่ละเดือน",
                    backgroundColor: "rgba(23, 125, 255, 0.7)",
                    borderColor: "rgba(23, 125, 255, 1)",
                    data: <?php echo json_encode(array_fill(0, 12, 0)); ?>, // เริ่มต้นด้วยค่า 0
                  }]
                },
                options: {
                  responsive: true,
                  maintainAspectRatio: false,
                  scales: {
                    y: {
                      beginAtZero: true
                    }
                  }
                }
              });

              // โหลดข้อมูลปีปัจจุบันเมื่อเปิดหน้า
              changeYear(0);
            };
          </script pt>



        </div><!-- row end -->

      </div>
    </div>
  </div>
</div>

<?php include('../../includes/footer.php') ?>