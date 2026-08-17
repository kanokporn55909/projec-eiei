<?php
session_start();

// ถ้ายังไม่ได้เข้าสู่ระบบ ให้กลับไปหน้า Login
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["username"];
?>

<!DOCTYPE html>
<html lang="th">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ระบบจัดการหลักสูตร</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f7f4ea;
            font-family: "Segoe UI", Tahoma, sans-serif;
            color: #4b3b1d;
        }

        /* =========================
           Sidebar
        ========================= */

        .sidebar {
            width: 270px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: linear-gradient(
                180deg,
                #b8860b,
                #d4af37
            );
            color: white;
            overflow-y: auto;
            box-shadow: 3px 0 15px rgba(0,0,0,0.12);
        }

        .logo-area {
            text-align: center;
            padding: 25px 15px;
            border-bottom: 1px solid rgba(255,255,255,0.3);
        }

        .logo-area img {
            width: 75px;
            height: 75px;
            object-fit: contain;
            margin-bottom: 10px;
        }

        .logo-area h4 {
            margin: 5px 0;
            font-weight: bold;
        }

        .logo-area small {
            opacity: 0.9;
        }

        /* เมนู */

        .menu-title {
            padding: 18px 20px 8px;
            font-size: 13px;
            opacity: 0.8;
            font-weight: bold;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 13px 20px;
            font-size: 15px;
            transition: 0.2s;
        }

        .sidebar a:hover {
            background: rgba(255,255,255,0.18);
            padding-left: 25px;
        }

        .logout {
            margin-top: 15px;
            border-top: 1px solid rgba(255,255,255,0.25);
        }

        /* =========================
           Main Content
        ========================= */

        .content {
            margin-left: 270px;
            padding: 30px;
        }

        .topbar {
            background: white;
            border-radius: 15px;
            padding: 20px 25px;
            margin-bottom: 25px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.07);

            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar h2 {
            margin: 0;
            font-weight: bold;
            color: #806000;
        }

        .user-info {
            text-align: right;
        }

        .user-info strong {
            color: #b8860b;
        }

        /* =========================
           Welcome
        ========================= */

        .welcome {
            background: linear-gradient(
                135deg,
                #fff8dc,
                #f5e6a8
            );

            border-radius: 18px;
            padding: 30px;

            margin-bottom: 25px;

            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .welcome h3 {
            color: #8a6500;
            font-weight: bold;
        }

        .welcome p {
            margin-bottom: 0;
            color: #6b5a35;
        }

        /* =========================
           Statistics
        ========================= */

        .stat-card {
            background: white;
            border: none;
            border-radius: 16px;

            padding: 20px;

            box-shadow:
                0 4px 12px rgba(0,0,0,0.07);

            transition: 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            font-size: 35px;
            margin-bottom: 10px;
        }

        .stat-number {
            font-size: 30px;
            font-weight: bold;
            color: #b8860b;
        }

        .stat-title {
            color: #777;
            margin: 0;
        }

        /* =========================
           Quick Menu
        ========================= */

        .section-title {
            margin-top: 30px;
            margin-bottom: 15px;
            font-weight: bold;
            color: #806000;
        }

        .quick-card {
            background: white;
            border-radius: 15px;
            padding: 22px;
            text-decoration: none;
            color: #4b3b1d;

            display: block;

            box-shadow:
                0 3px 10px rgba(0,0,0,0.06);

            transition: 0.2s;
            height: 100%;
        }

        .quick-card:hover {
            transform: translateY(-4px);
            color: #8a6500;
            box-shadow:
                0 7px 18px rgba(0,0,0,0.1);
        }

        .quick-icon {
            font-size: 35px;
            margin-bottom: 10px;
        }

        .quick-card h5 {
            font-weight: bold;
        }

        .quick-card p {
            font-size: 14px;
            color: #777;
            margin-bottom: 0;
        }

        /* =========================
           Announcement
        ========================= */

        .announcement {
            background: white;
            border-radius: 15px;
            padding: 25px;

            box-shadow:
                0 3px 10px rgba(0,0,0,0.06);

            margin-top: 30px;
        }

        .announcement li {
            margin-bottom: 12px;
        }

        /* =========================
           Responsive
        ========================= */

        @media (max-width: 900px) {

            .sidebar {
                width: 220px;
            }

            .content {
                margin-left: 220px;
            }

        }

        @media (max-width: 650px) {

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .content {
                margin-left: 0;
                padding: 15px;
            }

            .topbar {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .user-info {
                text-align: left;
            }

        }

    </style>

</head>


<body>


<!-- =====================================================
     SIDEBAR
===================================================== -->

<div class="sidebar">


    <!-- Logo -->

    <div class="logo-area">

        <img
            src="images/RMUTI.png"
            alt="RMUTI Logo"
        >

        <h4>
            ระบบจัดการหลักสูตร
        </h4>

        <small>
            สำหรับประธานหลักสูตร
        </small>

    </div>


    <!-- Dashboard -->

    <div class="menu-title">
        หน้าหลัก
    </div>

    <a href="dashboard.php">
        🏠 Dashboard
    </a>


    <!-- ข้อมูลส่วนตัว -->

    <div class="menu-title">
        จัดการข้อมูลส่วนตัว
    </div>

    <a href="profile.php">
        👤 ข้อมูลส่วนตัว
    </a>


    <!-- อาจารย์ -->

    <div class="menu-title">
        จัดการอาจารย์
    </div>

    <a href="responsible-teachers.php">
        👨‍🏫 อาจารย์ผู้รับผิดชอบหลักสูตร
    </a>

    <a href="teachers.php">
        👨‍🏫 อาจารย์ประจำหลักสูตร
    </a>

    <a href="academic-position.php">
        🎓 ตำแหน่งทางวิชาการ
    </a>


    <!-- รายวิชา -->

    <div class="menu-title">
        จัดการรายวิชา
    </div>

    <a href="courses.php">
        📚 ข้อมูลรายวิชา
    </a>


    <!-- หลักสูตร -->

    <div class="menu-title">
        จัดการหลักสูตร
    </div>

    <a href="upload.php">
        📂 อัปโหลดไฟล์หลักสูตร
    </a>

    <a href="academic-years.php">
        📅 ปีการศึกษา
    </a>

    <a href="semesters.php">
        📋 ภาคการศึกษา
    </a>

    <a href="study-plans.php">
        📖 แผนการศึกษา
    </a>


    <!-- ค้นหา -->

    <div class="menu-title">
        ค้นหาข้อมูล
    </div>

    <a href="search.php">
        🔍 ค้นหาหลักสูตร
    </a>


    <!-- ติดต่อ -->

    <div class="menu-title">
        ติดต่อสอบถาม
    </div>

    <a href="contact.php">
        📩 ข้อความติดต่อสอบถาม
    </a>


    <!-- Logout -->

    <a
        href="logout.php"
        class="logout"
    >
        🚪 ออกจากระบบ
    </a>


</div>



<!-- =====================================================
     CONTENT
===================================================== -->

<div class="content">


    <!-- Top Bar -->

    <div class="topbar">

        <div>

            <h2>
                Dashboard
            </h2>

            <small>
                ระบบบริหารจัดการข้อมูลหลักสูตร
            </small>

        </div>


        <div class="user-info">

            👤 ผู้ใช้งาน

            <br>

            <strong>
                <?php echo htmlspecialchars($username); ?>
            </strong>

        </div>

    </div>



    <!-- Welcome -->

    <div class="welcome">

        <h3>
            ยินดีต้อนรับเข้าสู่ระบบ 👋
        </h3>

    </div>



    <!-- =================================================
         STATISTICS
    ================================================= -->

    <div class="row g-4">


        <div class="col-lg-3 col-md-6">

            <div class="stat-card text-center">

                <div class="stat-icon">
                    📖
                </div>

                <div class="stat-number">
                    15
                </div>

                <p class="stat-title">
                    หลักสูตร
                </p>

            </div>

        </div>


        <div class="col-lg-3 col-md-6">

            <div class="stat-card text-center">

                <div class="stat-icon">
                    📚
                </div>

                <div class="stat-number">
                    124
                </div>

                <p class="stat-title">
                    รายวิชา
                </p>

            </div>

        </div>


        <div class="col-lg-3 col-md-6">

            <div class="stat-card text-center">

                <div class="stat-icon">
                    👨‍🏫
                </div>

                <div class="stat-number">
                    35
                </div>

                <p class="stat-title">
                    อาจารย์
                </p>

            </div>

        </div>


        <div class="col-lg-3 col-md-6">

            <div class="stat-card text-center">

                <div class="stat-icon">
                    📩
                </div>

                <div class="stat-number">
                    8
                </div>

                <p class="stat-title">
                    ข้อความใหม่
                </p>

            </div>

        </div>


    </div>



    <!-- =================================================
         QUICK MENU
    ================================================= -->

    <h4 class="section-title">
        เมนูจัดการข้อมูล
    </h4>


    <div class="row g-4">


        <!-- อาจารย์ -->

        <div class="col-lg-4 col-md-6">

            <a
                href="teachers.php"
                class="quick-card"
            >

                <div class="quick-icon">
                    👨‍🏫
                </div>

                <h5>
                    จัดการอาจารย์
                </h5>

                <p>
                    เพิ่ม ลบ แก้ไขข้อมูลอาจารย์
                    และตำแหน่งทางวิชาการ
                </p>

            </a>

        </div>


        <!-- รายวิชา -->

        <div class="col-lg-4 col-md-6">

            <a
                href="courses.php"
                class="quick-card"
            >

                <div class="quick-icon">
                    📚
                </div>

                <h5>
                    จัดการรายวิชา
                </h5>

                <p>
                    จัดการรหัสรายวิชา ชื่อรายวิชา
                    และจำนวนหน่วยกิต
                </p>

            </a>

        </div>


        <!-- หลักสูตร -->

        <div class="col-lg-4 col-md-6">

            <a
                href="upload.php"
                class="quick-card"
            >

                <div class="quick-icon">
                    📂
                </div>

                <h5>
                    จัดการหลักสูตร
                </h5>

                <p>
                    อัปโหลดไฟล์และจัดการข้อมูลหลักสูตร
                </p>

            </a>

        </div>


        <!-- ปีการศึกษา -->

        <div class="col-lg-4 col-md-6">

            <a
                href="academic-years.php"
                class="quick-card"
            >

                <div class="quick-icon">
                    📅
                </div>

                <h5>
                    ปีการศึกษา
                </h5>

                <p>
                    เพิ่ม ลบ และแก้ไขปีการศึกษา
                </p>

            </a>

        </div>


        <!-- แผนการศึกษา -->

        <div class="col-lg-4 col-md-6">

            <a
                href="study-plans.php"
                class="quick-card"
            >

                <div class="quick-icon">
                    📖
                </div>

                <h5>
                    แผนการศึกษา
                </h5>

                <p>
                    จัดการแผนการศึกษาของหลักสูตร
                </p>

            </a>

        </div>


        <!-- ค้นหา -->

        <div class="col-lg-4 col-md-6">

            <a
                href="search.php"
                class="quick-card"
            >

                <div class="quick-icon">
                    🔍
                </div>

                <h5>
                    ค้นหาหลักสูตร
                </h5>

                <p>
                    ค้นหาข้อมูลหลักสูตรตามสาขา
                    และชื่อหลักสูตร
                </p>

            </a>

        </div>


    </div>



    <!-- =================================================
         ANNOUNCEMENT
    ================================================= -->

    <div class="announcement">

        <h4 class="section-title mt-0">
            📢 ประกาศและแจ้งเตือน
        </h4>

        <ul>

            <li>
                ตรวจสอบข้อมูลอาจารย์ประจำหลักสูตรให้เป็นปัจจุบัน
            </li>

            <li>
                ตรวจสอบรหัสรายวิชา ชื่อรายวิชา
                และจำนวนหน่วยกิต
            </li>

            <li>
                อัปโหลดไฟล์หลักสูตรของปีการศึกษาปัจจุบัน
            </li>

            <li>
                ตรวจสอบข้อมูลแผนการศึกษาให้ครบถ้วน
            </li>

        </ul>

    </div>


</div>


</body>

</html>