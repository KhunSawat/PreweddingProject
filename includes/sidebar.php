<?php
// session_start();
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
  <div class="sidebar-logo">
    <!-- Logo Header -->
    <div class="logo-header" data-background-color="dark">
      <a href="../Prewedding" class="logo">
        <p class="text-white fw-bold mt-1 mb-0 p-0">Kawaii Wedding Studio</p>
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
  <div class="sidebar-wrapper scrollbar scrollbar-inner">
    <div class="sidebar-content">
      <ul class="nav nav-secondary">
        <li class="nav-item <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
          <a href="../../" class="nav-link">
            <i class="fas fa-home"></i>
            <p>หน้าแรก</p>
          </a>
        </li>
        <li class="nav-section ">
          <span class="sidebar-mini-icon">
            <i class="fa fa-ellipsis-h"></i>
          </span>
          <h4 class="text-section">รายการ</h4>
        </li>
        <li class="nav-item <?php echo ($current_page === 'customer_list.php' || $current_page === 'Em_list.php' || $current_page === 'dress_list.php' || $current_page === 'package_list.php') ? 'active' : ''; ?>">
          <a data-bs-toggle="collapse" href="#base">
            <i class="fas fa-layer-group"></i>
            <p>จัดการข้อมูลพื้นฐาน</p>
            <span class="caret"></span>
          </a>
          <div class="collapse <?php echo ($current_page === 'customer_list.php' || $current_page === 'Em_list.php' || $current_page === 'dress_list.php' || $current_page === 'package_list.php') ? 'show' : ''; ?>" id="base">
            <ul class="nav nav-collapse">
              <li class="<?php echo ($current_page === 'customer_list.php') ? 'active' : ''; ?>">
                <a href="../customer/customer_list.php" class="py-0">
                  <span class="sub-item">ลูกค้า</span>
                </a>
              </li>
              <li class="<?php echo ($current_page === 'Em_list.php') ? 'active' : ''; ?>">
                <a href="../Employee/Em_list.php" class="py-0">
                  <span class="sub-item">พนักงาน</span>
                </a>
              </li>
              <li class="<?php echo ($current_page === 'dress_list.php') ? 'active' : ''; ?>">
                <a href="../dress/dress_list.php" class="py-0">
                  <span class="sub-item">ชุด</span>
                </a>
              </li>
              <li class="<?php echo ($current_page === 'package_list.php') ? 'active' : ''; ?>">
                <a href="../package/package_list.php" class="py-0">
                  <span class="sub-item">บริการแพ็คเกจ</span>
                </a>
              </li>
            </ul>
          </div>
        </li>

        <li class="nav-item <?php echo ($current_page === 'booking_list.php' || $current_page === 'search_booking.php') ? 'active' : ''; ?>">
          <a data-bs-toggle="collapse" href="#workQueue">
            <i class="fas fa-calendar-alt"></i>
            <p>คิวงาน</p>
            <span class="caret"></span>
          </a>
          <div class="collapse <?php echo ($current_page === 'booking_list.php' || $current_page === 'search_booking.php') ? 'show' : ''; ?>" id="workQueue">
            <ul class="nav nav-collapse">
              <li class="<?php echo ($current_page === 'booking_list.php') ? 'active' : ''; ?>">
                <a href="../work/booking_list.php">
                  <span class="sub-item">การจองคิว</span>
                </a>
              </li>
              <li class="<?php echo ($current_page === 'search_booking.php') ? 'active' : ''; ?>">
                <a href="../work/search_booking.php">
                  <span class="sub-item">จัดการงาน</span>
                </a>
              </li>
            </ul>
          </div>
        </li>

        <li class="nav-item <?php echo ($current_page === 'dress_status.php' || $current_page === 'return_list.php' || $current_page === 'receive.php') ? 'active' : ''; ?>">
          <a data-bs-toggle="collapse" href="#forms">
            <i class="fas fa-tshirt"></i>
            <p>เบิกคืนชุด</p>
            <span class="caret"></span>
          </a>
          <div class="collapse <?php echo ($current_page === 'dress_status.php' || $current_page === 'return_list.php' || $current_page === 'receive.php') ? 'show' : ''; ?>" id="forms">
            <ul class="nav nav-collapse">
              <li class="<?php echo ($current_page === 'dress_status.php') ? 'active' : ''; ?>">
                <a href="../work/dress_status.php">
                  <span class="sub-item">สถานะชุด</span>
                </a>
              </li>
              <li class="<?php echo ($current_page === 'return_list.php') ? 'active' : ''; ?>">
                <a href="../work/return_list.php">
                  <span class="sub-item">เบิกชุด</span>
                </a>
              </li>
              <li class="<?php echo ($current_page === 'receive.php') ? 'active' : ''; ?>">
                <a href="../work/receive.php">
                  <span class="sub-item">คืนชุด</span>
                </a>
              </li>
            </ul>
          </div>
        </li>


        <li class="nav-item <?php echo (strpos($current_page, 'Expense_list') !== false || strpos($current_page, 'report') !== false) ? 'active' : ''; ?>">
          <a data-bs-toggle="collapse" href="#tables">
            <i class="fas fa-table"></i>
            <p>จัดการร้าน</p>
            <span class="caret"></span>
          </a>
          <div class="collapse <?php echo (strpos($current_page, 'Expense_list') !== false || strpos($current_page, 'ReportList.php') !== false) ? 'show' : ''; ?>" id="tables">
            <ul class="nav nav-collapse">
              <li class="<?php echo (strpos($current_page, 'Expense_list') !== false) ? 'active' : ''; ?>">
                <a href="../Expense/Expense_list.php">
                  <span class="sub-item">รายจ่าย</span>
                </a>
              </li>
              <li class="<?php echo (strpos($current_page, 'ReportList.php') !== false) ? 'active' : ''; ?>">
                <a href="../report/ReportList.php">
                  <span class="sub-item">ออกรายงาน</span>
                </a>
              </li>
            </ul>
          </div>
        </li>

        <li class="nav-item logout-item">
          <a href="../../config/logout.php" class="nav-link">
            <i class="fas fa-sign-out-alt"></i>
            <p>ออกจากระบบ</p>
          </a>
        </li>
      </ul>
    </div>
  </div>
</div>
<!-- End Sidebar -->