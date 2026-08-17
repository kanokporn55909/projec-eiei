<?php
session_start();

// ======================================================
// ตรวจสอบการเข้าสู่ระบบ
// ======================================================

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}


// ======================================================
// ข้อมูลหลักสูตรตัวอย่าง
// ======================================================

if (!isset($_SESSION["curriculum_data"])) {

    $_SESSION["curriculum_data"] = [

        [
            "id" => 1,
            "program" => "เทคโนโลยีคอมพิวเตอร์",
            "curriculum" => "หลักสูตรวิทยาศาสตรบัณฑิต",
            "degree" => "วิทยาศาสตรบัณฑิต (วท.บ.)",
            "year" => "2568",
            "type" => "ปริญญาตรี",
            "details" => "หลักสูตรมุ่งเน้นด้านเทคโนโลยีคอมพิวเตอร์และระบบสารสนเทศ"
        ],

        [
            "id" => 2,
            "program" => "เทคโนโลยีสารสนเทศ",
            "curriculum" => "หลักสูตรวิทยาศาสตรบัณฑิต",
            "degree" => "วิทยาศาสตรบัณฑิต (วท.บ.)",
            "year" => "2568",
            "type" => "ปริญญาตรี",
            "details" => "หลักสูตรมุ่งเน้นด้านเทคโนโลยีสารสนเทศและการพัฒนาซอฟต์แวร์"
        ],

        [
            "id" => 3,
            "program" => "วิศวกรรมคอมพิวเตอร์",
            "curriculum" => "หลักสูตรวิศวกรรมศาสตรบัณฑิต",
            "degree" => "วิศวกรรมศาสตรบัณฑิต (วศ.บ.)",
            "year" => "2567",
            "type" => "ปริญญาตรี",
            "details" => "หลักสูตรด้านวิศวกรรมคอมพิวเตอร์และระบบอัจฉริยะ"
        ],

        [
            "id" => 4,
            "program" => "บริหารธุรกิจ",
            "curriculum" => "หลักสูตรบริหารธุรกิจบัณฑิต",
            "degree" => "บริหารธุรกิจบัณฑิต (บธ.บ.)",
            "year" => "2568",
            "type" => "ปริญญาตรี",
            "details" => "หลักสูตรด้านการบริหารธุรกิจและการจัดการ"
        ]

    ];
}


// ======================================================
// รับค่าการค้นหา
// ======================================================

$searchType = $_GET["search_type"] ?? "";

$keyword = trim(
    $_GET["keyword"] ?? ""
);

$results = [];


// ======================================================
// ค้นหาข้อมูล
// ======================================================

if ($keyword !== "") {

    foreach (
        $_SESSION["curriculum_data"]
        as $curriculum
    ) {

        $found = false;


        // ----------------------------------------------
        // ค้นหาตามสาขา
        // ----------------------------------------------

        if ($searchType === "program") {

            if (
                mb_stripos(
                    $curriculum["program"],
                    $keyword
                ) !== false
            ) {

                $found = true;

            }

        }


        // ----------------------------------------------
        // ค้นหาตามหลักสูตร
        // ----------------------------------------------

        elseif ($searchType === "curriculum") {

            if (
                mb_stripos(
                    $curriculum["curriculum"],
                    $keyword
                ) !== false
            ) {

                $found = true;

            }

        }


        // ----------------------------------------------
        // ค้นหาทั้งหมด
        // ----------------------------------------------

        elseif ($searchType === "all") {

            if (
                mb_stripos(
                    $curriculum["program"],
                    $keyword
                ) !== false

                ||

                mb_stripos(
                    $curriculum["curriculum"],
                    $keyword
                ) !== false

                ||

                mb_stripos(
                    $curriculum["degree"],
                    $keyword
                ) !== false

                ||

                mb_stripos(
                    $curriculum["year"],
                    $keyword
                ) !== false
            ) {

                $found = true;

            }

        }


        if ($found) {

            $results[] = $curriculum;

        }

    }

}

?>

<!DOCTYPE html>

<html lang="th">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        ค้นหาข้อมูลหลักสูตร
    </title>


    <!-- Bootstrap -->

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


        /* ==================================================
           SIDEBAR
        ================================================== */

        .sidebar {

            width: 270px;

            height: 100vh;

            position: fixed;

            top: 0;

            left: 0;

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


        /* ==================================================
           CONTENT
        ================================================== */

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


        /* ==================================================
           SEARCH CARD
        ================================================== */

        .search-card {

            background: white;

            border-radius: 18px;

            padding: 25px;

            margin-bottom: 25px;

            box-shadow:
                0 4px 15px rgba(0,0,0,0.08);
        }


        .search-title {

            color: #806000;

            font-weight: bold;

            margin-bottom: 20px;
        }


        .form-label {

            font-weight: 600;

            color: #5c491e;
        }


        .form-control,
        .form-select {

            height: 48px;

            border-radius: 9px;

            border:
                1px solid #d7c78c;
        }


        .btn-search {

            height: 48px;

            padding:
                0 25px;

            border: none;

            border-radius: 9px;

            color: white;

            font-weight: bold;

            background:
                linear-gradient(
                    135deg,
                    #d4af37,
                    #b8860b
                );
        }


        .btn-search:hover {

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #c69f20,
                    #9f7607
                );
        }


        .btn-reset {

            height: 48px;

            border-radius: 9px;

            padding:
                0 20px;
        }


        /* ==================================================
           RESULT
        ================================================== */

        .result-card {

            background: white;

            border-radius: 18px;

            padding: 25px;

            box-shadow:
                0 4px 15px rgba(0,0,0,0.08);
        }


        .result-header {

            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 20px;
        }


        .result-header h4 {

            margin: 0;

            color: #806000;

            font-weight: bold;
        }


        .result-count {

            background: #fff3cd;

            color: #856404;

            padding:
                7px 14px;

            border-radius: 20px;

            font-size: 14px;
        }


        .table {

            vertical-align: middle;

            margin-bottom: 0;
        }


        .table thead th {

            background: #fff8dc;

            color: #6b5200;

            border-bottom:
                2px solid #d4af37;

            white-space: nowrap;
        }


        .table tbody tr:hover {

            background: #fffdf3;
        }


        .program-badge {

            background: #fff3cd;

            color: #856404;

            padding:
                6px 10px;

            border-radius: 20px;

            font-size: 13px;
        }


        .year-badge {

            background: #e8f5e9;

            color: #2e7d32;

            padding:
                6px 10px;

            border-radius: 20px;

            font-size: 13px;
        }


        .empty-result {

            text-align: center;

            padding: 50px 20px;

            color: #888;
        }


        .empty-result .icon {

            font-size: 50px;

            margin-bottom: 10px;
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


            .result-header {

                flex-direction: column;

                align-items: flex-start;

                gap: 10px;
            }

        }

    </style>

</head>


<body>


<!-- ======================================================
     SIDEBAR
====================================================== -->

<div class="sidebar">


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


    <div class="menu-title">
        หน้าหลัก
    </div>


    <a href="dashboard.php">
        🏠 Dashboard
    </a>


    <div class="menu-title">
        จัดการข้อมูลส่วนตัว
    </div>


    <a href="profile.php">
        👤 ข้อมูลส่วนตัว
    </a>


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


    <div class="menu-title">
        จัดการรายวิชา
    </div>


    <a href="courses.php">
        📚 ข้อมูลรายวิชา
    </a>


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


    <div class="menu-title">
        ค้นหาข้อมูล
    </div>


    <a
        href="search.php"
        class="active"
    >
        🔍 ค้นหาหลักสูตร
    </a>


    <div class="menu-title">
        ติดต่อสอบถาม
    </div>


    <a href="contact.php">
        📩 ข้อความติดต่อสอบถาม
    </a>


    <a
        href="logout.php"
        class="logout"
    >
        🚪 ออกจากระบบ
    </a>


</div>



<!-- ======================================================
     CONTENT
====================================================== -->

<div class="content">


    <!-- TOPBAR -->

    <div class="topbar">

        <h2>
            🔍 ค้นหาข้อมูลหลักสูตร
        </h2>


        <p>
            ค้นหาและตรวจสอบข้อมูลหลักสูตรตามสาขาและหลักสูตร
        </p>

    </div>



    <!-- ==================================================
         SEARCH
    ================================================== -->

    <div class="search-card">


        <h4 class="search-title">
            🔎 ค้นหาข้อมูลหลักสูตร
        </h4>


        <form method="GET">


            <div class="row">


                <!-- ประเภทการค้นหา -->

                <div class="col-md-4 mb-3">

                    <label class="form-label">
                        ค้นหาข้อมูลตาม
                    </label>


                    <select
                        name="search_type"
                        class="form-select"
                        required
                    >

                        <option value="">
                            เลือกประเภทการค้นหา
                        </option>


                        <option
                            value="program"
                            <?= $searchType === "program"
                                ? "selected"
                                : "" ?>
                        >
                            🏫 สาขา
                        </option>


                        <option
                            value="curriculum"
                            <?= $searchType === "curriculum"
                                ? "selected"
                                : "" ?>
                        >
                            📚 หลักสูตร
                        </option>


                        <option
                            value="all"
                            <?= $searchType === "all"
                                ? "selected"
                                : "" ?>
                        >
                            🔍 ค้นหาทั้งหมด
                        </option>

                    </select>

                </div>



                <!-- คำค้นหา -->

                <div class="col-md-5 mb-3">

                    <label class="form-label">
                        คำค้นหา
                    </label>


                    <input
                        type="text"
                        name="keyword"
                        class="form-control"
                        placeholder="กรอกชื่อสาขาหรือชื่อหลักสูตร"
                        value="<?= htmlspecialchars(
                            $keyword
                        ) ?>"
                        required
                    >

                </div>



                <!-- ปุ่ม -->

                <div class="col-md-3 mb-3 d-flex align-items-end gap-2">


                    <button
                        type="submit"
                        class="btn-search"
                    >

                        🔍 ค้นหา

                    </button>


                    <a
                        href="search.php"
                        class="btn btn-secondary btn-reset"
                    >

                        รีเซ็ต

                    </a>


                </div>


            </div>


        </form>


    </div>



    <!-- ==================================================
         RESULT
    ================================================== -->

    <div class="result-card">


        <div class="result-header">


            <h4>
                📋 ผลการค้นหาข้อมูลหลักสูตร
            </h4>


            <?php if ($keyword !== ""): ?>

                <span class="result-count">

                    พบ <?= count($results) ?> รายการ

                </span>

            <?php endif; ?>


        </div>



        <?php if ($keyword === ""): ?>


            <div class="empty-result">

                <div class="icon">
                    🔍
                </div>

                <h5>
                    กรุณาเลือกประเภทและกรอกคำค้นหา
                </h5>

                <p>
                    สามารถค้นหาข้อมูลหลักสูตรตามสาขา
                    หรือชื่อหลักสูตรได้
                </p>

            </div>


        <?php elseif (count($results) > 0): ?>


            <div class="table-responsive">

                <table class="table table-hover">


                    <thead>

                        <tr>

                            <th>
                                ลำดับ
                            </th>

                            <th>
                                สาขา
                            </th>

                            <th>
                                หลักสูตร
                            </th>

                            <th>
                                ชื่อปริญญา
                            </th>

                            <th>
                                ปีการศึกษา
                            </th>

                            <th>
                                ระดับ
                            </th>

                            <th>
                                รายละเอียด
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                        <?php foreach (
                            $results as $index => $curriculum
                        ): ?>


                            <tr>


                                <td>

                                    <?= $index + 1 ?>

                                </td>


                                <td>

                                    <span class="program-badge">

                                        <?= htmlspecialchars(
                                            $curriculum["program"]
                                        ) ?>

                                    </span>

                                </td>


                                <td>

                                    <strong>

                                        <?= htmlspecialchars(
                                            $curriculum["curriculum"]
                                        ) ?>

                                    </strong>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $curriculum["degree"]
                                    ) ?>

                                </td>


                                <td>

                                    <span class="year-badge">

                                        <?= htmlspecialchars(
                                            $curriculum["year"]
                                        ) ?>

                                    </span>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $curriculum["type"]
                                    ) ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $curriculum["details"]
                                    ) ?>

                                </td>


                            </tr>


                        <?php endforeach; ?>


                    </tbody>


                </table>

            </div>


        <?php else: ?>


            <div class="empty-result">

                <div class="icon">
                    😢
                </div>

                <h5>
                    ไม่พบข้อมูลหลักสูตร
                </h5>

                <p>
                    ลองเปลี่ยนคำค้นหาหรือประเภทการค้นหา
                </p>

            </div>


        <?php endif; ?>


    </div>


</div>


</body>

</html>