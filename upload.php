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
// สร้างโฟลเดอร์ uploads ถ้ายังไม่มี
// ======================================================

$uploadDir = "uploads/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// ======================================================
// สร้าง Session สำหรับเก็บข้อมูลไฟล์ชั่วคราว
// ======================================================

if (!isset($_SESSION["course_files"])) {
    $_SESSION["course_files"] = [];
}

// ======================================================
// อัปโหลดไฟล์
// ======================================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $action = $_POST["action"] ?? "";

    // ==================================================
    // เพิ่มไฟล์
    // ==================================================

    if ($action === "upload") {

        if (isset($_FILES["course_file"]) &&
            $_FILES["course_file"]["error"] === UPLOAD_ERR_OK) {

            $file = $_FILES["course_file"];

            $originalName = $file["name"];
            $tmpName = $file["tmp_name"];
            $fileSize = $file["size"];
            $fileType = $file["type"];

            // ตรวจสอบนามสกุลไฟล์
            $extension = strtolower(
                pathinfo($originalName, PATHINFO_EXTENSION)
            );

            $allowedExtensions = [
                "pdf",
                "doc",
                "docx",
                "xls",
                "xlsx"
            ];

            if (!in_array($extension, $allowedExtensions)) {

                $_SESSION["upload_error"] =
                    "อนุญาตเฉพาะไฟล์ PDF, Word และ Excel เท่านั้น";

            } else {

                // สร้างชื่อไฟล์ใหม่ ป้องกันชื่อซ้ำ
                $newFileName =
                    time() . "_" .
                    uniqid() . "." .
                    $extension;

                $destination =
                    $uploadDir . $newFileName;

                // ย้ายไฟล์ไปยังโฟลเดอร์ uploads
                if (move_uploaded_file(
                    $tmpName,
                    $destination
                )) {

                    // เก็บข้อมูลไว้ใน Session
                    $_SESSION["course_files"][] = [

                        "id" => time(),

                        "original_name" =>
                            $originalName,

                        "file_name" =>
                            $newFileName,

                        "file_path" =>
                            $destination,

                        "file_size" =>
                            $fileSize,

                        "file_type" =>
                            $fileType,

                        "upload_date" =>
                            date("d/m/Y H:i")

                    ];

                    $_SESSION["upload_success"] =
                        "อัปโหลดไฟล์เรียบร้อยแล้ว";

                } else {

                    $_SESSION["upload_error"] =
                        "ไม่สามารถอัปโหลดไฟล์ได้";
                }
            }

        } else {

            $_SESSION["upload_error"] =
                "กรุณาเลือกไฟล์ก่อนอัปโหลด";
        }

        header("Location: upload.php");
        exit();
    }

    // ==================================================
    // ลบไฟล์
    // ==================================================

    if ($action === "delete") {

        $id = $_POST["id"] ?? "";

        foreach (
            $_SESSION["course_files"]
            as $key => $file
        ) {

            if ($file["id"] == $id) {

                // ลบไฟล์จริงออกจากโฟลเดอร์
                if (
                    isset($file["file_path"]) &&
                    file_exists($file["file_path"])
                ) {

                    unlink($file["file_path"]);
                }

                unset(
                    $_SESSION["course_files"][$key]
                );

                break;
            }
        }

        $_SESSION["course_files"] =
            array_values(
                $_SESSION["course_files"]
            );

        header("Location: upload.php");
        exit();
    }
}

// ======================================================
// ดึงข้อมูลไฟล์
// ======================================================

$courseFiles = $_SESSION["course_files"];

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
        อัปโหลดไฟล์หลักสูตร
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
                1px solid
                rgba(255,255,255,0.3);
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
                1px solid
                rgba(255,255,255,0.25);
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
                0 3px 12px
                rgba(0,0,0,0.07);
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
                0 4px 15px
                rgba(0,0,0,0.08);

            margin-bottom: 25px;
        }

        .page-header {

            display: flex;

            justify-content:
                space-between;

            align-items: center;

            margin-bottom: 20px;
        }

        .page-header h4 {

            margin: 0;

            color: #806000;

            font-weight: bold;
        }

        /* ==================================================
           UPLOAD BOX
        ================================================== */

        .upload-box {

            border:
                2px dashed #d4af37;

            border-radius: 15px;

            padding: 45px 30px;

            text-align: center;

            background: #fffdf5;
        }

        .upload-icon {

            font-size: 55px;

            margin-bottom: 15px;
        }

        .upload-box h5 {

            color: #806000;

            font-weight: bold;

            margin-bottom: 10px;
        }

        .upload-box p {

            color: #777;

            margin-bottom: 20px;
        }

        .file-input {

            max-width: 500px;

            margin: auto;
        }

        .btn-upload {

            margin-top: 15px;

            background:
                linear-gradient(
                    135deg,
                    #d4af37,
                    #b8860b
                );

            color: white;

            border: none;

            border-radius: 10px;

            padding: 11px 25px;

            font-weight: bold;
        }

        .btn-upload:hover {

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #c69f20,
                    #9f7607
                );
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

        .file-name {

            font-weight: 600;

            color: #5c491e;
        }

        .file-type {

            background: #fff3cd;

            color: #856404;

            padding:
                6px 10px;

            border-radius: 20px;

            font-size: 13px;
        }

        .btn-delete {

            background: #dc3545;

            color: white;

            border: none;

            border-radius: 7px;

            padding: 6px 12px;
        }

        .btn-view {

            background: #198754;

            color: white;

            border: none;

            border-radius: 7px;

            padding: 6px 12px;

            text-decoration: none;
        }

        .btn-view:hover {

            color: white;
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

    <a
        href="upload.php"
        class="active"
    >
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
            📂 อัปโหลดไฟล์หลักสูตร
        </h2>

        <p>
            อัปโหลดไฟล์เอกสารหลักสูตรสำหรับจัดเก็บและใช้งานในระบบ
        </p>

    </div>


    <!-- ==================================================
         UPLOAD
    ================================================== -->

    <div class="main-card">

        <div class="page-header">

            <h4>
                อัปโหลดเอกสารหลักสูตร
            </h4>

        </div>


        <?php if (isset($_SESSION["upload_success"])): ?>

            <div class="alert alert-success">

                ✓
                <?= htmlspecialchars(
                    $_SESSION["upload_success"]
                ) ?>

            </div>

            <?php unset($_SESSION["upload_success"]); ?>

        <?php endif; ?>


        <?php if (isset($_SESSION["upload_error"])): ?>

            <div class="alert alert-danger">

                ⚠️
                <?= htmlspecialchars(
                    $_SESSION["upload_error"]
                ) ?>

            </div>

            <?php unset($_SESSION["upload_error"]); ?>

        <?php endif; ?>


        <div class="upload-box">

            <div class="upload-icon">
                📄
            </div>

            <h5>
                เลือกไฟล์หลักสูตรที่ต้องการอัปโหลด
            </h5>

            <p>
                รองรับไฟล์ PDF, Word และ Excel
            </p>


            <form
                method="POST"
                enctype="multipart/form-data"
            >

                <input
                    type="hidden"
                    name="action"
                    value="upload"
                >


                <div class="file-input">

                    <input
                        type="file"
                        name="course_file"
                        class="form-control"
                        accept=".pdf,.doc,.docx,.xls,.xlsx"
                        required
                    >

                </div>


                <button
                    type="submit"
                    class="btn btn-upload"
                >

                    ⬆️ อัปโหลดไฟล์

                </button>

            </form>

        </div>

    </div>


    <!-- ==================================================
         FILE LIST
    ================================================== -->

    <div class="main-card">

        <div class="page-header">

            <h4>
                📋 ไฟล์หลักสูตรที่อัปโหลด
            </h4>

        </div>


        <div class="table-responsive">

            <table class="table table-hover">

                <thead>

                    <tr>

                        <th>
                            ลำดับ
                        </th>

                        <th>
                            ชื่อไฟล์
                        </th>

                        <th>
                            ประเภทไฟล์
                        </th>

                        <th>
                            ขนาดไฟล์
                        </th>

                        <th>
                            วันที่อัปโหลด
                        </th>

                        <th class="text-center">
                            จัดการ
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <?php if (count($courseFiles) > 0): ?>

                        <?php foreach (
                            $courseFiles
                            as $index => $file
                        ): ?>

                            <tr>

                                <td>
                                    <?= $index + 1 ?>
                                </td>


                                <td>

                                    <span class="file-name">

                                        📄
                                        <?= htmlspecialchars(
                                            $file["original_name"]
                                        ) ?>

                                    </span>

                                </td>


                                <td>

                                    <span class="file-type">

                                        <?= strtoupper(
                                            htmlspecialchars(
                                                pathinfo(
                                                    $file["original_name"],
                                                    PATHINFO_EXTENSION
                                                )
                                            )
                                        ) ?>

                                    </span>

                                </td>


                                <td>

                                    <?php

                                    $size = $file["file_size"];

                                    if ($size >= 1048576) {

                                        echo round(
                                            $size / 1048576,
                                            2
                                        ) . " MB";

                                    } else {

                                        echo round(
                                            $size / 1024,
                                            2
                                        ) . " KB";
                                    }

                                    ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $file["upload_date"]
                                    ) ?>

                                </td>


                                <td class="text-center">


                                    <!-- เปิดไฟล์ -->

                                    <a
                                        href="<?= htmlspecialchars(
                                            $file["file_path"]
                                        ) ?>"
                                        target="_blank"
                                        class="btn-view me-1"
                                    >

                                        👁️ ดูไฟล์

                                    </a>


                                    <!-- ลบไฟล์ -->

                                    <form
                                        method="POST"
                                        style="display:inline;"
                                        onsubmit="return confirm(
                                            'ต้องการลบไฟล์นี้หรือไม่?'
                                        );"
                                    >

                                        <input
                                            type="hidden"
                                            name="action"
                                            value="delete"
                                        >

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= htmlspecialchars(
                                                $file["id"]
                                            ) ?>"
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
                                colspan="6"
                                class="text-center py-4"
                            >

                                📂 ยังไม่มีไฟล์หลักสูตร

                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>

</html>