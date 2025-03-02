<?php
  $table = "Employee";
  $employee_no = $_SESSION['Employee_No'];
  $sql_select = "SELECT * FROM $table WHERE Employee_No = '$employee_no' ORDER BY Employee_No";
?>


<!-- Navbar Header -->

<nav
  class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
  <div class="container-fluid">

    <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">

      <li class="nav-item topbar-user dropdown hidden-caret">
        <a
          class="dropdown-toggle profile-pic"
          data-bs-toggle="dropdown"
          href="#"
          aria-expanded="false">
          <div class="avatar-sm">
            <img
              src="../../assets/pic/<?= $_SESSION['Em_pic'] ?>"
              alt="..."
              class="avatar-img rounded-circle" />
          </div>
          <span class="profile-username">
            <span class="op-7"><?php echo $_SESSION['Em_Name']; ?></span>
          </span>
        </a>
        <ul class="dropdown-menu dropdown-user animated fadeIn">
          <div class="dropdown-user-scroll scrollbar-outer">
            <li>
              <div class="user-box">
                <div class="avatar-lg">
                  <img
                    src="../../assets/pic/<?= $_SESSION['Em_pic'] ?>"
                    alt="image profile"
                    class="avatar-img rounded" />
                </div>
                <div class="u-text">
                  <h4><?php echo $_SESSION['Em_Name']; ?></h4>
                  <p class="text-muted"><?php echo  $_SESSION['Em_Username']; ?></p>
                  <a
                    href="../employee/Em_edit.php?Employee_No=<?= $_SESSION['Employee_No']?>"
                    class="btn btn-xs btn-secondary btn-sm">แก้ไขข้อมูล</a>
                </div>
              </div>
            </li>
          </div>
        </ul>
      </li>
    </ul>
  </div>
</nav>
<!-- End Navbar -->