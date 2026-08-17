<?php
session_start();

// ถ้ายังไม่ได้เข้าสู่ระบบ ให้กลับไปหน้า Login
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["username"];

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // รับค่าจากฟอร์ม
    $firstname = $_POST["firstname"] ?? "";
    $lastname  = $_POST["lastname"] ?? "";
    $phone     = $_POST["phone"] ?? "";

    /*
        ตอนนี้ยังไม่ลงฐานข้อมูล
        เก็บไว้แค่สำหรับทดสอบการทำงานของหน้าเว็บ
    */

    $message = "บันทึกข้อมูลเรียบร้อยแล้ว (ยังไม่ได้เชื่อมฐานข้อมูล)";
}

?>

<!DOCTYPE html>
<html lang="th">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>ข้อมูลส่วนตัว</title>

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

            font-family:
                "Segoe UI",
                Tahoma,
                sans-serif;

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

            background:
                linear-gradient(
                    180deg,
                    #b8860b,
                    #d4af37
                );

            color: white;

            overflow-y: auto;

            box-shadow:
                3px 0 15px rgba(0,0,0,0.12);

        }


        .logo-area {

            text-align: center;

            padding: 25px 15px;

            border-bottom:
                1px solid rgba(255,255,255,0.3);

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


        .menu-title {

            padding:
                18px 20px 8px;

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

            background:
                rgba(255,255,255,0.18);

            padding-left: 25px;

        }


        /* เมนูที่กำลังเปิดอยู่ */

        .sidebar a.active {

            background:
                rgba(255,255,255,0.25);

            font-weight: bold;

        }


        .logout {

            margin-top: 15px;

            border-top:
                1px solid rgba(255,255,255,0.25);

        }


        /* =========================
           Content
        ========================= */

        .content {

            margin-left: 270px;

            padding: 30px;

            min-height: 100vh;

        }


        .topbar {

            background: white;

            border-radius: 15px;

            padding: 20px 25px;

            margin-bottom: 25px;

            box-shadow:
                0 3px 12px rgba(0,0,0,0.07);

        }


        .topbar h2 {

            margin: 0;

            color: #806000;

            font-weight: bold;

        }


        .topbar p {

            margin:
                5px 0 0;

            color: #777;

        }


        /* =========================
           Profile Card
        ========================= */

        .profile-card {

            background: white;

            border-radius: 18px;

            padding: 35px;

            max-width: 850px;

            margin: auto;

            box-shadow:
                0 4px 15px rgba(0,0,0,0.08);

        }


        .profile-header {

            text-align: center;

            margin-bottom: 30px;

        }


        .profile-icon {

            width: 90px;

            height: 90px;

            margin: auto;

            border-radius: 50%;

            background:
                linear-gradient(
                    135deg,
                    #d4af37,
                    #b8860b
                );

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 45px;

            color: white;

            box-shadow:
                0 5px 15px rgba(0,0,0,0.15);

        }


        .profile-header h3 {

            margin-top: 15px;

            color: #806000;

            font-weight: bold;

        }


        .profile-header p {

            color: #777;

        }


        /* =========================
           Form
        ========================= */

        .form-label {

            font-weight: 600;

            color: #5c491e;

        }


        .form-control {

            height: 50px;

            border-radius: 10px;

            border:
                1px solid #d7c78c;

        }


        .form-control:focus {

            border-color: #b8860b;

            box-shadow:
                0 0 0 3px rgba(184,134,11,0.15);

        }


        /* =========================
           Buttons
        ========================= */

        .btn-confirm {

            background:
                linear-gradient(
                    135deg,
                    #d4af37,
                    #b8860b
                );

            border: none;

            color: white;

            height: 50px;

            border-radius: 10px;

            font-weight: bold;

            padding:
                0 35px;

        }


        .btn-confirm:hover {

            background:
                linear-gradient(
                    135deg,
                    #c69f20,
                    #9f7607
                );

            color: white;

        }


        .btn-back {

            height: 50px;

            border-radius: 10px;

            padding:
                0 30px;

        }


        /* =========================
           Alert
        ========================= */

        .success-message {

            background: #e8f7ed;

            border:
                1px solid #a8dfba;

            color: #237a3b;

            padding: 15px;

            border-radius: 10px;

            margin-bottom: 25px;

            text-align: center;

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

            .profile-card {

                padding: 25px 20px;

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


    <!-- หน้าหลัก -->

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

    <a
        href="profile.php"
        class="active"
    >
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


    <!-- หัวข้อ -->

    <div class="topbar">

        <h2>
            👤 ข้อมูลส่วนตัว
        </h2>

        <p>
            แก้ไขข้อมูลส่วนตัวของประธานหลักสูตร
        </p>

    </div>



    <!-- Profile -->

    <div class="profile-card">


        <!-- Header -->

        <div class="profile-header">

            <div class="profile-icon">
                👤
            </div>

            <h3>
                แก้ไขข้อมูลส่วนตัว
            </h3>

            <p>
                กรุณากรอกข้อมูลให้ครบถ้วน
            </p>

        </div>



        <!-- Message -->

        <?php if ($message != ""): ?>

            <div class="success-message">

                ✅
                <?php echo htmlspecialchars($message); ?>

            </div>

        <?php endif; ?>



        <!-- Form -->

        <form method="POST">


            <div class="row">


                <!-- ชื่อ -->

                <div class="col-md-6 mb-4">

                    <label
                        class="form-label"
                        for="firstname"
                    >
                        ชื่อ
                    </label>

                    <input
                        type="text"
                        id="firstname"
                        name="firstname"
                        class="form-control"
                        placeholder="กรอกชื่อ"
                        required
                    >

                </div>



                <!-- นามสกุล -->

                <div class="col-md-6 mb-4">

                    <label
                        class="form-label"
                        for="lastname"
                    >
                        นามสกุล
                    </label>

                    <input
                        type="text"
                        id="lastname"
                        name="lastname"
                        class="form-control"
                        placeholder="กรอกนามสกุล"
                        required
                    >

                </div>


            </div>



            <!-- เบอร์โทร -->

            <div class="mb-4">

                <label
                    class="form-label"
                    for="phone"
                >
                    เบอร์โทรศัพท์
                </label>

                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    class="form-control"
                    placeholder="กรอกเบอร์โทรศัพท์"
                    maxlength="10"
                    required
                >

            </div>



            <!-- Username -->

            <div class="mb-4">

                <label class="form-label">
                    ชื่อผู้ใช้
                </label>

                <input
                    type="text"
                    class="form-control"
                    value="<?php echo htmlspecialchars($username); ?>"
                    readonly
                >

                <small class="text-muted">
                    ชื่อผู้ใช้ไม่สามารถแก้ไขได้
                </small>

            </div>



            <!-- Buttons -->

            <div class="d-flex justify-content-center gap-3 mt-4">


                <a
                    href="dashboard.php"
                    class="btn btn-secondary btn-back"
                >
                    ← ยกเลิก
                </a>


                <button
                    type="submit"
                    class="btn btn-confirm"
                >
                    ✓ ยืนยันและบันทึก
                </button>


            </div>


        </form>


    </div>


</div>


</body>

</html>