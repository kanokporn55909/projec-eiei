<?php

session_start();

// ล้างข้อมูล Session ทั้งหมด
session_unset();

// ทำลาย Session
session_destroy();

// กลับไปหน้าล็อกอิน
header("Location: login.php");
exit();

?>