<?php

session_start();


// ======================================================
// ตรวจสอบการเข้าสู่ระบบ
// ======================================================

if (!isset($_SESSION["username"])) {

    header("Location: login.php");

    exit();

}


$needResetTeachers = false;

if (
    !isset($_SESSION["teachers_regular"]) ||
    $needResetTeachers
) {

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
// เพิ่ม / แก้ไข / ลบ
// ======================================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $action = $_POST["action"] ?? "";


    // ==================================================
    // เพิ่มอาจารย์
    // ==================================================

    if ($action === "add") {

        $newTeacher = [

            "id" => time(),

            "prefix" => $_POST["prefix"] ?? "",

            "firstname" => $_POST["firstname"] ?? "",

            "lastname" => $_POST["lastname"] ?? "",

            "position" => $_POST["position"] ?? "",

            "phone" => $_POST["phone"] ?? "",

            "email" => $_POST["email"] ?? ""

        ];


        $_SESSION["teachers_regular"][] = $newTeacher;


        header("Location: teachers.php");

        exit();

    }


    // ==================================================
    // แก้ไขอาจารย์
    // ==================================================

    if ($action === "edit") {

        $id = $_POST["id"] ?? "";


        foreach (
            $_SESSION["teachers_regular"] as &$teacher
        ) {

            if ($teacher["id"] == $id) {

                $teacher["prefix"] =
                    $_POST["prefix"] ?? "";


                $teacher["firstname"] =
                    $_POST["firstname"] ?? "";


                $teacher["lastname"] =
                    $_POST["lastname"] ?? "";


                $teacher["position"] =
                    $_POST["position"] ?? "";


                $teacher["phone"] =
                    $_POST["phone"] ?? "";


                $teacher["email"] =
                    $_POST["email"] ?? "";


                break;

            }

        }


        unset($teacher);


        header("Location: teachers.php");

        exit();

    }


    // ==================================================
    // ลบอาจารย์
    // ==================================================

    if ($action === "delete") {

        $id = $_POST["id"] ?? "";


        foreach (
            $_SESSION["teachers_regular"] as $key => $teacher
        ) {

            if ($teacher["id"] == $id) {

                unset(
                    $_SESSION["teachers_regular"][$key]
                );

                break;

            }

        }


        // จัดลำดับ Array ใหม่

        $_SESSION["teachers_regular"] =
            array_values(
                $_SESSION["teachers_regular"]
            );


        header("Location: teachers.php");

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
        อาจารย์ประจำหลักสูตร
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


        .position-badge {

            background: #fff3cd;

            color: #856404;

            padding:
                6px 10px;

            border-radius: 20px;

            font-size: 13px;

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


        .form-control,
        .form-select {

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


    <a
        href="teachers.php"
        class="active"
    >
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
            👨‍🏫 อาจารย์ประจำหลักสูตร
        </h2>


        <p>
            เพิ่ม แก้ไข และลบข้อมูลอาจารย์ประจำหลักสูตร
        </p>

    </div>



    <div class="main-card">


        <div class="page-header">

            <h4>
                รายชื่ออาจารย์ประจำหลักสูตร
            </h4>


            <button
                type="button"
                class="btn btn-add"
                data-bs-toggle="modal"
                data-bs-target="#addTeacherModal"
            >

                + เพิ่มอาจารย์

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
                            ชื่อ - นามสกุล
                        </th>


                        <th>
                            ตำแหน่ง
                        </th>


                        <th>
                            หน้าที่
                        </th>


                        <th>
                            เบอร์โทรศัพท์
                        </th>


                        <th>
                            อีเมล
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

                                    <span
                                        class="position-badge"
                                    >

                                        <?= htmlspecialchars(
                                            $teacher["prefix"]
                                        ) ?>

                                    </span>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $teacher["position"]
                                    ) ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $teacher["phone"]
                                    ) ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $teacher["email"]
                                    ) ?>

                                </td>


                                <td class="text-center">


                                    <!-- แก้ไข -->

                                    <button
                                        type="button"
                                        class="btn-edit me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editTeacherModal"

                                        data-id="<?= htmlspecialchars($teacher["id"]) ?>"

                                        data-prefix="<?= htmlspecialchars($teacher["prefix"]) ?>"

                                        data-firstname="<?= htmlspecialchars($teacher["firstname"]) ?>"

                                        data-lastname="<?= htmlspecialchars($teacher["lastname"]) ?>"

                                        data-position="<?= htmlspecialchars($teacher["position"]) ?>"

                                        data-phone="<?= htmlspecialchars($teacher["phone"]) ?>"

                                        data-email="<?= htmlspecialchars($teacher["email"]) ?>"
                                    >

                                        ✏️ แก้ไข

                                    </button>



                                    <!-- ลบ -->

                                    <form
                                        method="POST"
                                        style="display:inline;"
                                        onsubmit="
                                            return confirm(
                                                'ต้องการลบ <?= htmlspecialchars($teacher["firstname"]) ?> <?= htmlspecialchars($teacher["lastname"]) ?> หรือไม่?'
                                            );
                                        "
                                    >


                                        <input
                                            type="hidden"
                                            name="action"
                                            value="delete"
                                        >


                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= htmlspecialchars($teacher["id"]) ?>"
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
                                colspan="7"
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
     MODAL เพิ่ม
====================================================== -->

<div
    class="modal fade"
    id="addTeacherModal"
    tabindex="-1"
>


    <div class="modal-dialog modal-lg">


        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">

                    👨‍🏫 เพิ่มอาจารย์ประจำหลักสูตร

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


                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                คำนำหน้า
                            </label>


                            <select
                                name="prefix"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    เลือกคำนำหน้า
                                </option>


                                <option>
                                    อาจารย์
                                </option>


                                <option>
                                    ผู้ช่วยศาสตราจารย์
                                </option>


                                <option>
                                    รองศาสตราจารย์
                                </option>


                                <option>
                                    ศาสตราจารย์
                                </option>

                            </select>

                        </div>


                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                ชื่อ
                            </label>


                            <input
                                type="text"
                                name="firstname"
                                class="form-control"
                                placeholder="กรอกชื่อ"
                                required
                            >

                        </div>


                        <div class="col-md-5 mb-3">

                            <label class="form-label">
                                นามสกุล
                            </label>


                            <input
                                type="text"
                                name="lastname"
                                class="form-control"
                                placeholder="กรอกนามสกุล"
                                required
                            >

                        </div>


                    </div>


                    <div class="row">


                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                หน้าที่
                            </label>


                            <select
                                name="position"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    เลือกหน้าที่
                                </option>


                                <option>
                                    อาจารย์ประจำหลักสูตร
                                </option>

                            </select>

                        </div>


                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                เบอร์โทรศัพท์
                            </label>


                            <input
                                type="text"
                                name="phone"
                                class="form-control"
                                placeholder="กรอกเบอร์โทรศัพท์"
                                maxlength="10"
                                required
                            >

                        </div>


                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            อีเมล
                        </label>


                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            placeholder="example@email.com"
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
     MODAL แก้ไข
====================================================== -->

<div
    class="modal fade"
    id="editTeacherModal"
    tabindex="-1"
>


    <div class="modal-dialog modal-lg">


        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">

                    ✏️ แก้ไขข้อมูลอาจารย์

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


                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                คำนำหน้า
                            </label>


                            <select
                                name="prefix"
                                id="edit-prefix"
                                class="form-select"
                                required
                            >

                                <option>
                                    อาจารย์
                                </option>


                                <option>
                                    ผู้ช่วยศาสตราจารย์
                                </option>


                                <option>
                                    รองศาสตราจารย์
                                </option>


                                <option>
                                    ศาสตราจารย์
                                </option>

                            </select>

                        </div>


                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                ชื่อ
                            </label>


                            <input
                                type="text"
                                name="firstname"
                                id="edit-firstname"
                                class="form-control"
                                required
                            >

                        </div>


                        <div class="col-md-5 mb-3">

                            <label class="form-label">
                                นามสกุล
                            </label>


                            <input
                                type="text"
                                name="lastname"
                                id="edit-lastname"
                                class="form-control"
                                required
                            >

                        </div>


                    </div>


                    <div class="row">


                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                หน้าที่
                            </label>


                            <select
                                name="position"
                                id="edit-position"
                                class="form-select"
                                required
                            >

                                <option>
                                    อาจารย์ประจำหลักสูตร
                                </option>

                            </select>

                        </div>


                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                เบอร์โทรศัพท์
                            </label>


                            <input
                                type="text"
                                name="phone"
                                id="edit-phone"
                                class="form-control"
                                maxlength="10"
                                required
                            >

                        </div>


                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            อีเมล
                        </label>


                        <input
                            type="email"
                            name="email"
                            id="edit-email"
                            class="form-control"
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
                            "edit-prefix"
                        ).value =
                            this.dataset.prefix;


                        document.getElementById(
                            "edit-firstname"
                        ).value =
                            this.dataset.firstname;


                        document.getElementById(
                            "edit-lastname"
                        ).value =
                            this.dataset.lastname;


                        document.getElementById(
                            "edit-position"
                        ).value =
                            this.dataset.position;


                        document.getElementById(
                            "edit-phone"
                        ).value =
                            this.dataset.phone;


                        document.getElementById(
                            "edit-email"
                        ).value =
                            this.dataset.email;

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