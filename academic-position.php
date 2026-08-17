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
// ตรวจสอบข้อมูลอาจารย์
// ======================================================

if (!isset($_SESSION["teachers_regular"])) {
    $_SESSION["teachers_regular"] = [
        [
            "id" => 1,
            "prefix" => "อาจารย์",
            "firstname" => "อัญญาวีย์",
            "lastname" => "ไชวะชิรกัมพล",
            "position" => "อาจารย์ประจำหลักสูตร",
            "phone" => "0812345678",
            "email" => "anyuavee@example.com"
        ],
        [
            "id" => 2,
            "prefix" => "อาจารย์",
            "firstname" => "ศักชาญดิ์",
            "lastname" => "เหลืองมณีโรจน์",
            "position" => "อาจารย์ประจำหลักสูตร",
            "phone" => "0823456789",
            "email" => "sakcharn@example.com"
        ]
    ];
}


// ======================================================
// แก้ไขตำแหน่งทางวิชาการ
// ======================================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $action = $_POST["action"] ?? "";

    if ($action === "edit_position") {

        $id = $_POST["id"] ?? "";
        $academic_position = $_POST["academic_position"] ?? "";

        foreach ($_SESSION["teachers_regular"] as &$teacher) {

            if ($teacher["id"] == $id) {

                $teacher["academic_position"] = $academic_position;

                break;
            }
        }

        unset($teacher);

        header("Location: academic-position.php");
        exit();
    }
}


// ======================================================
// ดึงข้อมูลอาจารย์
// ======================================================

$teachers = $_SESSION["teachers_regular"];

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
        ตำแหน่งทางวิชาการ
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


        .main-card {

            background: white;

            border-radius: 18px;

            padding: 25px;

            box-shadow:
                0 4px 15px rgba(0,0,0,0.08);

        }


        .page-header {

            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 20px;

        }


        .page-header h4 {

            margin: 0;

            color: #806000;

            font-weight: bold;

        }


        /* ==================================================
           TABLE
        ================================================== */

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


        .academic-badge {

            background: #fff3cd;

            color: #856404;

            padding:
                7px 13px;

            border-radius: 20px;

            font-size: 13px;

        }


        /* ==================================================
           BUTTON
        ================================================== */

        .btn-edit {

            background: #ffc107;

            color: #4b3b1d;

            border: none;

            border-radius: 7px;

            padding:
                7px 14px;

        }


        .btn-edit:hover {

            background: #e0a800;

            color: #4b3b1d;

        }


        .btn-confirm {

            background:
                linear-gradient(
                    135deg,
                    #d4af37,
                    #b8860b
                );

            color: white;

            border: none;

            border-radius: 9px;

            padding:
                10px 25px;

            font-weight: bold;

        }


        .btn-confirm:hover {

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #c69f20,
                    #9f7607
                );

        }


        /* ==================================================
           MODAL
        ================================================== */

        .modal-content {

            border: none;

            border-radius: 18px;

            overflow: hidden;

        }


        .modal-header {

            background:
                linear-gradient(
                    135deg,
                    #d4af37,
                    #b8860b
                );

            color: white;

            border: none;

        }


        .modal-header .btn-close {

            filter:
                brightness(0)
                invert(1);

        }


        .form-label {

            font-weight: 600;

            color: #5c491e;

        }


        .form-select {

            height: 48px;

            border-radius: 9px;

            border:
                1px solid #d7c78c;

        }


        /* ==================================================
           RESPONSIVE
        ================================================== */

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


    <a
        href="academic-position.php"
        class="active"
    >
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


    <a href="search.php">
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


    <div class="topbar">

        <h2>
            🎓 ตำแหน่งทางวิชาการ
        </h2>

        <p>
            แก้ไขตำแหน่งทางวิชาการของอาจารย์ประจำหลักสูตร
        </p>

    </div>


    <div class="main-card">


        <div class="page-header">

            <h4>
                รายชื่อตำแหน่งทางวิชาการ
            </h4>

        </div>


        <!-- ==================================================
             TABLE
        ================================================== -->

        <div class="table-responsive">

            <table class="table table-hover">


                <thead>

                    <tr>

                        <th>
                            ลำดับ
                        </th>


                        <th>
                            ชื่อ - นามสกุล
                        </th>


                        <th>
                            ตำแหน่งทางวิชาการ
                        </th>


                        <th class="text-center">
                            จัดการ
                        </th>

                    </tr>

                </thead>


                <tbody>


                    <?php if (count($teachers) > 0): ?>


                        <?php foreach (
                            $teachers as $index => $teacher
                        ): ?>


                            <?php

                            $academicPosition =
                                $teacher["academic_position"]
                                ?? "อาจารย์";

                            ?>


                            <tr>


                                <td>

                                    <?= $index + 1 ?>

                                </td>


                                <td>

                                    <strong>

                                        <?= htmlspecialchars(
                                            $teacher["prefix"]
                                            . " "
                                            . $teacher["firstname"]
                                            . " "
                                            . $teacher["lastname"]
                                        ) ?>

                                    </strong>

                                </td>


                                <td>

                                    <span class="academic-badge">

                                        <?= htmlspecialchars(
                                            $academicPosition
                                        ) ?>

                                    </span>

                                </td>


                                <td class="text-center">


                                    <button
                                        type="button"
                                        class="btn-edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editPositionModal"

                                        data-id="<?= htmlspecialchars(
                                            $teacher["id"]
                                        ) ?>"

                                        data-name="<?= htmlspecialchars(
                                            $teacher["prefix"]
                                            . " "
                                            . $teacher["firstname"]
                                            . " "
                                            . $teacher["lastname"]
                                        ) ?>"

                                        data-position="<?= htmlspecialchars(
                                            $academicPosition
                                        ) ?>"
                                    >

                                        ✏️ แก้ไข

                                    </button>


                                </td>


                            </tr>


                        <?php endforeach; ?>


                    <?php else: ?>


                        <tr>

                            <td
                                colspan="4"
                                class="text-center py-4"
                            >

                                ยังไม่มีข้อมูลอาจารย์

                            </td>

                        </tr>


                    <?php endif; ?>


                </tbody>

            </table>

        </div>


    </div>


</div>


<!-- ======================================================
     MODAL แก้ไขตำแหน่งทางวิชาการ
====================================================== -->

<div
    class="modal fade"
    id="editPositionModal"
    tabindex="-1"
>


    <div class="modal-dialog">


        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">

                    🎓 แก้ไขตำแหน่งทางวิชาการ

                </h5>


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <form method="POST">


                <div class="modal-body">


                    <input
                        type="hidden"
                        name="action"
                        value="edit_position"
                    >


                    <input
                        type="hidden"
                        name="id"
                        id="edit-id"
                    >


                    <div class="mb-3">

                        <label class="form-label">

                            อาจารย์

                        </label>


                        <input
                            type="text"
                            class="form-control"
                            id="edit-name"
                            readonly
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label">

                            ตำแหน่งทางวิชาการ

                        </label>


                        <select
                            name="academic_position"
                            id="edit-academic-position"
                            class="form-select"
                            required
                        >

                            <option value="">
                                เลือกตำแหน่งทางวิชาการ
                            </option>


                            <option value="อาจารย์">
                                อาจารย์
                            </option>


                            <option value="ผู้ช่วยศาสตราจารย์">
                                ผู้ช่วยศาสตราจารย์
                            </option>


                            <option value="รองศาสตราจารย์">
                                รองศาสตราจารย์
                            </option>


                            <option value="ศาสตราจารย์">
                                ศาสตราจารย์
                            </option>


                        </select>

                    </div>


                </div>


                <div class="modal-footer">


                    <!-- ปุ่มยกเลิก -->

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >

                        ยกเลิก

                    </button>


                    <!-- ปุ่มยืนยัน -->

                    <button
                        type="submit"
                        class="btn btn-confirm"
                    >

                        ✓ ยืนยัน

                    </button>


                </div>


            </form>


        </div>

    </div>

</div>


<!-- ======================================================
     JAVASCRIPT
====================================================== -->

<script>

document.addEventListener(
    "DOMContentLoaded",
    function () {


        const editButtons =
            document.querySelectorAll(
                ".btn-edit"
            );


        editButtons.forEach(
            function (button) {


                button.addEventListener(
                    "click",
                    function () {


                        document.getElementById(
                            "edit-id"
                        ).value =
                            this.dataset.id;


                        document.getElementById(
                            "edit-name"
                        ).value =
                            this.dataset.name;


                        document.getElementById(
                            "edit-academic-position"
                        ).value =
                            this.dataset.position;


                    }
                );

            }
        );

    }
);

</script>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>