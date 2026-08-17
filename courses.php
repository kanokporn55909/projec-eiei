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
// ข้อมูลรายวิชาเริ่มต้น
// ======================================================

$needResetCourses = false;

if (
    !isset($_SESSION["courses"]) ||
    $needResetCourses
) {

    $_SESSION["courses"] = [

        [
            "id" => 1,
            "code" => "CP101",
            "name" => "การเขียนโปรแกรมเบื้องต้น",
            "credits" => 3
        ],

        [
            "id" => 2,
            "code" => "CP201",
            "name" => "โครงสร้างข้อมูลและอัลกอริทึม",
            "credits" => 3
        ],

        [
            "id" => 3,
            "code" => "CP301",
            "name" => "ระบบฐานข้อมูล",
            "credits" => 3
        ]

    ];
}


// ======================================================
// เพิ่ม / แก้ไข / ลบ
// ======================================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $action = $_POST["action"] ?? "";


    // ==================================================
    // เพิ่มรายวิชา
    // ==================================================

    if ($action === "add") {

        $newCourse = [

            "id" => time(),

            "code" => $_POST["code"] ?? "",

            "name" => $_POST["name"] ?? "",

            "credits" => $_POST["credits"] ?? ""

        ];

        $_SESSION["courses"][] = $newCourse;

        header("Location: courses.php");
        exit();
    }


    // ==================================================
    // แก้ไขรายวิชา
    // ==================================================

    if ($action === "edit") {

        $id = $_POST["id"] ?? "";

        foreach ($_SESSION["courses"] as &$course) {

            if ($course["id"] == $id) {

                $course["code"] =
                    $_POST["code"] ?? "";

                $course["name"] =
                    $_POST["name"] ?? "";

                $course["credits"] =
                    $_POST["credits"] ?? "";

                break;
            }
        }

        unset($course);

        header("Location: courses.php");
        exit();
    }


    // ==================================================
    // ลบรายวิชา
    // ==================================================

    if ($action === "delete") {

        $id = $_POST["id"] ?? "";

        foreach (
            $_SESSION["courses"] as $key => $course
        ) {

            if ($course["id"] == $id) {

                unset(
                    $_SESSION["courses"][$key]
                );

                break;
            }
        }

        // จัดลำดับ Array ใหม่
        $_SESSION["courses"] =
            array_values(
                $_SESSION["courses"]
            );

        header("Location: courses.php");
        exit();
    }
}


// ======================================================
// ดึงข้อมูลรายวิชา
// ======================================================

$courses = $_SESSION["courses"];

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
        ข้อมูลรายวิชา
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
           BUTTON
        ================================================== */

        .btn-add {

            background:
                linear-gradient(
                    135deg,
                    #d4af37,
                    #b8860b
                );

            color: white;

            border: none;

            border-radius: 10px;

            padding:
                11px 20px;

            font-weight: bold;
        }


        .btn-add:hover {

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #c69f20,
                    #9f7607
                );
        }


        .btn-edit {

            background: #ffc107;

            color: #4b3b1d;

            border: none;

            border-radius: 7px;

            padding:
                6px 12px;
        }


        .btn-delete {

            background: #dc3545;

            color: white;

            border: none;

            border-radius: 7px;

            padding:
                6px 12px;
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


        .credit-badge {

            background: #fff3cd;

            color: #856404;

            padding:
                6px 12px;

            border-radius: 20px;

            font-size: 14px;

            font-weight: bold;
        }


        .course-code {

            font-weight: bold;

            color: #806000;
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
                brightness(0) invert(1);
        }


        .form-label {

            font-weight: 600;

            color: #5c491e;
        }


        .form-control {

            height: 48px;

            border-radius: 9px;

            border:
                1px solid #d7c78c;
        }


        .btn-save {

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


        .btn-save:hover {

            color: white;
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


    <a
        href="courses.php"
        class="active"
    >
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
            📚 ข้อมูลรายวิชา
        </h2>

        <p>
            เพิ่ม แก้ไข และลบข้อมูลรายวิชา
        </p>

    </div>



    <div class="main-card">


        <div class="page-header">

            <h4>
                รายวิชาในหลักสูตร
            </h4>


            <button
                type="button"
                class="btn btn-add"
                data-bs-toggle="modal"
                data-bs-target="#addCourseModal"
            >

                + เพิ่มรายวิชา

            </button>

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
                            รหัสรายวิชา
                        </th>


                        <th>
                            ชื่อรายวิชา
                        </th>


                        <th class="text-center">
                            จำนวนหน่วยกิต
                        </th>


                        <th class="text-center">
                            จัดการ
                        </th>

                    </tr>

                </thead>


                <tbody>


                    <?php if (count($courses) > 0): ?>


                        <?php foreach (
                            $courses as $index => $course
                        ): ?>


                            <tr>


                                <td>
                                    <?= $index + 1 ?>
                                </td>


                                <td>

                                    <span class="course-code">

                                        <?= htmlspecialchars(
                                            $course["code"]
                                        ) ?>

                                    </span>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $course["name"]
                                    ) ?>

                                </td>


                                <td class="text-center">

                                    <span class="credit-badge">

                                        <?= htmlspecialchars(
                                            $course["credits"]
                                        ) ?>

                                        หน่วยกิต

                                    </span>

                                </td>


                                <td class="text-center">


                                    <!-- แก้ไข -->

                                    <button
                                        type="button"
                                        class="btn-edit me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editCourseModal"

                                        data-id="<?= htmlspecialchars($course["id"]) ?>"

                                        data-code="<?= htmlspecialchars($course["code"]) ?>"

                                        data-name="<?= htmlspecialchars($course["name"]) ?>"

                                        data-credits="<?= htmlspecialchars($course["credits"]) ?>"
                                    >

                                        ✏️ แก้ไข

                                    </button>



                                    <!-- ลบ -->

                                    <form
                                        method="POST"
                                        style="display:inline;"
                                        onsubmit="return confirm('ต้องการลบรายวิชา <?= htmlspecialchars($course["name"]) ?> หรือไม่?');"
                                    >

                                        <input
                                            type="hidden"
                                            name="action"
                                            value="delete"
                                        >


                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= htmlspecialchars($course["id"]) ?>"
                                        >


                                        <button
                                            type="submit"
                                            class="btn-delete"
                                        >

                                            🗑️ ลบ

                                        </button>

                                    </form>


                                </td>


                            </tr>


                        <?php endforeach; ?>


                    <?php else: ?>


                        <tr>

                            <td
                                colspan="5"
                                class="text-center py-4"
                            >

                                ยังไม่มีข้อมูลรายวิชา

                            </td>

                        </tr>


                    <?php endif; ?>


                </tbody>

            </table>

        </div>


    </div>


</div>



<!-- ======================================================
     MODAL เพิ่มรายวิชา
====================================================== -->

<div
    class="modal fade"
    id="addCourseModal"
    tabindex="-1"
>


    <div class="modal-dialog modal-lg">


        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">

                    📚 เพิ่มรายวิชา

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
                        value="add"
                    >


                    <div class="row">


                        <!-- รหัสรายวิชา -->

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                รหัสรายวิชา
                            </label>


                            <input
                                type="text"
                                name="code"
                                class="form-control"
                                placeholder="เช่น CP101"
                                required
                            >

                        </div>


                        <!-- ชื่อรายวิชา -->

                        <div class="col-md-8 mb-3">

                            <label class="form-label">
                                ชื่อรายวิชา
                            </label>


                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                placeholder="กรอกชื่อรายวิชา"
                                required
                            >

                        </div>


                    </div>


                    <!-- หน่วยกิต -->

                    <div class="mb-3">

                        <label class="form-label">
                            จำนวนหน่วยกิต
                        </label>


                        <input
                            type="number"
                            name="credits"
                            class="form-control"
                            placeholder="เช่น 3"
                            min="1"
                            max="10"
                            required
                        >

                    </div>


                </div>


                <div class="modal-footer">


                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >

                        ยกเลิก

                    </button>


                    <button
                        type="submit"
                        class="btn btn-save"
                    >

                        ✓ เพิ่มข้อมูล

                    </button>


                </div>


            </form>


        </div>

    </div>

</div>



<!-- ======================================================
     MODAL แก้ไขรายวิชา
====================================================== -->

<div
    class="modal fade"
    id="editCourseModal"
    tabindex="-1"
>


    <div class="modal-dialog modal-lg">


        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">

                    ✏️ แก้ไขข้อมูลรายวิชา

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
                        value="edit"
                    >


                    <input
                        type="hidden"
                        name="id"
                        id="edit-id"
                    >


                    <div class="row">


                        <!-- รหัสรายวิชา -->

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                รหัสรายวิชา
                            </label>


                            <input
                                type="text"
                                name="code"
                                id="edit-code"
                                class="form-control"
                                required
                            >

                        </div>


                        <!-- ชื่อรายวิชา -->

                        <div class="col-md-8 mb-3">

                            <label class="form-label">
                                ชื่อรายวิชา
                            </label>


                            <input
                                type="text"
                                name="name"
                                id="edit-name"
                                class="form-control"
                                required
                            >

                        </div>


                    </div>


                    <!-- หน่วยกิต -->

                    <div class="mb-3">

                        <label class="form-label">
                            จำนวนหน่วยกิต
                        </label>


                        <input
                            type="number"
                            name="credits"
                            id="edit-credits"
                            class="form-control"
                            min="1"
                            max="10"
                            required
                        >

                    </div>


                </div>


                <div class="modal-footer">


                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >

                        ยกเลิก

                    </button>


                    <button
                        type="submit"
                        class="btn btn-save"
                    >

                        ✓ บันทึกการแก้ไข

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
                            "edit-code"
                        ).value =
                            this.dataset.code;


                        document.getElementById(
                            "edit-name"
                        ).value =
                            this.dataset.name;


                        document.getElementById(
                            "edit-credits"
                        ).value =
                            this.dataset.credits;

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