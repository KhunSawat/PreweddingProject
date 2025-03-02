<?php
if(empty($_SESSION['logtype'])){
    header("Location: ../login.php");
    exit(); // ควรใช้ exit() เพื่อหยุดการทำงานของสคริปต์หลังจากการเปลี่ยนเส้นทาง
}
?>
