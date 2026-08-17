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
// ข้อมูลข้อความติดต่อสอบถาม
// ยังไม่ใช้ฐานข้อมูล ใช้ SESSION จำลองก่อน
// ======================================================

if (!isset($_SESSION["contacts"])) {

    $_SESSION["contacts"] = [

        [
            "id" => 1,
            "name" => "สมชาย ใจดี",
            "email" => "somchai@example.com",
            "subject" => "สอบถามข้อมูลหลักสูตร",
            "message" => "อยากทราบรายละเอียดเกี่ยวกับหลักสูตรเทคโนโลยีคอมพิวเตอร์ครับ",
            "date" => "13/08/2026 09:30",
            "status" => "unread"
        ],

        [
            "id" => 2,
            "name" => "กนกพร มหานาม",
            "email" => "kanokporn@example.com",
            "subject" => "สอบถามการสมัครเรียน",
            "message" => "ขอสอบถามรายละเอียดเกี่ยวกับการสมัครเข้าศึกษาและเอกสารที่ต้องใช้ค่ะ",
            "date" => "12/08/2026 15:20",
            "status" => "read"
        ],

        [
            "id" => 3,
            "name" => "สุดา แสงทอง",
            "email" => "suda@example.com",
            "subject" => "สอบถามรายวิชา",
            "message" => "ต้องการสอบถามเกี่ยวกับรายวิชาที่เปิดสอนในหลักสูตรค่ะ",
            "date" => "11/08/2026 10:15",
            "status" => "read"
        ]

    ];
}


// ======================================================
// จัดการข้อความ
// ======================================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $action = $_POST["action"] ?? "";
    $id = $_POST["id"] ?? "";


    // ==================================================
    // เปิดอ่านข้อความ
    // ==================================================

    if ($action === "read") {

        foreach ($_SESSION["contacts"] as &$contact) {

            if ($contact["id"] == $id) {

                $contact["status"] = "read";

                break;
            }
        }

        unset($contact);

        header("Location: contact.php");
        exit();
    }


    // ==================================================
    // ลบข้อความ
    // ==================================================

    if ($action === "delete") {

        foreach ($_SESSION["contacts"] as $key => $contact) {

            if ($contact["id"] == $id) {

                unset($_SESSION["contacts"][$key]);

                break;
            }
        }

        $_SESSION["contacts"] = array_values(
            $_SESSION["contacts"]
        );

        header("Location: contact.php");
        exit();
    }
}


// ======================================================
// ดึงข้อมูลข้อความ
// ======================================================

$contacts = $_SESSION["contacts"];


// นับจำนวนข้อความที่ยังไม่ได้อ่าน
$unreadCount = 0;

foreach ($contacts as $contact) {

    if ($contact["status"] === "unread") {
        $unreadCount++;
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

    <title>ข้อความติดต่อสอบถาม</title>


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
           MAIL CARD
        ================================================== */

        .mail-card {

            background: white;

            border-radius: 18px;

            box-shadow:
                0 4px 15px rgba(0,0,0,0.08);

            overflow: hidden;
        }


        .mail-header {

            padding: 22px 25px;

            border-bottom:
                1px solid #eee;

            display: flex;

            justify-content: space-between;

            align-items: center;
        }


        .mail-header h4 {

            margin: 0;

            color: #806000;

            font-weight: bold;
        }


        .unread-badge {

            background: #dc3545;

            color: white;

            padding:
                6px 12px;

            border-radius: 20px;

            font-size: 13px;

            font-weight: bold;
        }


        /* ==================================================
           MAIL ITEM
        ================================================== */

        .mail-item {

            display: flex;

            align-items: center;

            padding: 18px 25px;

            border-bottom:
                1px solid #eee;

            transition: 0.2s;

            cursor: pointer;
        }


        .mail-item:hover {

            background: #fffdf3;
        }


        .mail-item.unread {

            background: #fffaf0;

            font-weight: 600;
        }


        .mail-icon {

            width: 48px;

            height: 48px;

            min-width: 48px;

            border-radius: 50%;

            background: #fff3cd;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 22px;

            margin-right: 18px;
        }


        .mail-info {

            flex: 1;

            min-width: 0;
        }


        .mail-name {

            color: #5c491e;

            font-size: 16px;

            margin-bottom: 3px;
        }


        .mail-subject {

            color: #806000;

            margin-bottom: 3px;
        }


        .mail-preview {

            color: #888;

            font-size: 14px;

            white-space: nowrap;

            overflow: hidden;

            text-overflow: ellipsis;

            max-width: 650px;
        }


        .mail-date {

            color: #999;

            font-size: 13px;

            margin-left: 20px;

            white-space: nowrap;
        }


        .mail-actions {

            margin-left: 15px;

            display: flex;

            gap: 7px;
        }


        .btn-view {

            background: #d4af37;

            color: white;

            border: none;

            border-radius: 7px;

            padding:
                6px 12px;
        }


        .btn-view:hover {

            background: #b8860b;

            color: white;
        }


        .btn-delete {

            background: #dc3545;

            color: white;

            border: none;

            border-radius: 7px;

            padding:
                6px 12px;
        }


        .btn-delete:hover {

            background: #bb2d3b;
        }


        .empty-box {

            padding: 70px 20px;

            text-align: center;

            color: #999;
        }


        .empty-box .icon {

            font-size: 55px;

            margin-bottom: 15px;
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


        .message-box {

            background: #fffdf5;

            border:

                1px solid #eadb9b;

            border-radius: 12px;

            padding: 18px;

            line-height: 1.8;

            min-height: 150px;

            white-space: pre-line;
        }


        .info-label {

            font-weight: bold;

            color: #806000;
        }


        @media (max-width: 800px) {

            .sidebar {

                position: relative;

                width: 100%;

                height: auto;
            }


            .content {

                margin-left: 0;

                padding: 15px;
            }


            .mail-item {

                flex-wrap: wrap;
            }


            .mail-date {

                margin-left: 66px;

                margin-top: 5px;
            }


            .mail-actions {

                margin-left: 66px;

                margin-top: 10px;
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


    <a href="search.php">
        🔍 ค้นหาหลักสูตร
    </a>


    <div class="menu-title">
        ติดต่อสอบถาม
    </div>


    <a
        href="contact.php"
        class="active"
    >
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
            📩 ข้อความติดต่อสอบถาม
        </h2>

        <p>
            กล่องจดหมายสำหรับรับข้อความติดต่อสอบถามจากผู้ใช้ทั่วไป
        </p>

    </div>



    <div class="mail-card">


        <div class="mail-header">

            <h4>
                📬 กล่องข้อความ
            </h4>


            <?php if ($unreadCount > 0): ?>

                <span class="unread-badge">

                    <?= $unreadCount ?>

                    ข้อความยังไม่ได้อ่าน

                </span>

            <?php else: ?>

                <span class="badge bg-success">

                    อ่านครบแล้ว

                </span>

            <?php endif; ?>

        </div>



        <?php if (count($contacts) > 0): ?>


            <?php foreach ($contacts as $contact): ?>


                <div
                    class="mail-item
                    <?= $contact["status"] === "unread"
                        ? "unread"
                        : "" ?>"
                >


                    <div class="mail-icon">

                        <?= $contact["status"] === "unread"
                            ? "✉️"
                            : "📨" ?>

                    </div>



                    <div class="mail-info">


                        <div class="mail-name">

                            <?= htmlspecialchars(
                                $contact["name"]
                            ) ?>

                        </div>


                        <div class="mail-subject">

                            <?= htmlspecialchars(
                                $contact["subject"]
                            ) ?>

                        </div>


                        <div class="mail-preview">

                            <?= htmlspecialchars(
                                $contact["message"]
                            ) ?>

                        </div>


                    </div>



                    <div class="mail-date">

                        <?= htmlspecialchars(
                            $contact["date"]
                        ) ?>

                    </div>



                    <div class="mail-actions">


                        <!-- เปิดอ่านข้อความ -->

                        <button
                            type="button"
                            class="btn-view"
                            data-bs-toggle="modal"
                            data-bs-target="#viewMessageModal"

                            data-id="<?= htmlspecialchars(
                                $contact["id"]
                            ) ?>"

                            data-name="<?= htmlspecialchars(
                                $contact["name"]
                            ) ?>"

                            data-email="<?= htmlspecialchars(
                                $contact["email"]
                            ) ?>"

                            data-subject="<?= htmlspecialchars(
                                $contact["subject"]
                            ) ?>"

                            data-message="<?= htmlspecialchars(
                                $contact["message"]
                            ) ?>"

                            data-date="<?= htmlspecialchars(
                                $contact["date"]
                            ) ?>"

                        >

                            👁️ อ่าน

                        </button>



                        <!-- ลบ -->

                        <form
                            method="POST"
                            style="display:inline;"
                            onsubmit="return confirm(
                                'ต้องการลบข้อความนี้หรือไม่?'
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
                                    $contact["id"]
                                ) ?>"
                            >

                            <button
                                type="submit"
                                class="btn-delete"
                            >

                                🗑️

                            </button>

                        </form>


                    </div>


                </div>


            <?php endforeach; ?>


        <?php else: ?>


            <div class="empty-box">

                <div class="icon">
                    📭
                </div>

                <h5>
                    ยังไม่มีข้อความติดต่อสอบถาม
                </h5>

                <p>
                    เมื่อมีผู้ใช้ส่งข้อความเข้ามา
                    ข้อความจะแสดงที่นี่
                </p>

            </div>


        <?php endif; ?>


    </div>


</div>



<!-- ======================================================
     MODAL อ่านข้อความ
====================================================== -->

<div
    class="modal fade"
    id="viewMessageModal"
    tabindex="-1"
>


    <div class="modal-dialog modal-lg">


        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">
                    📩 รายละเอียดข้อความ
                </h5>


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>



            <div class="modal-body">


                <div class="row mb-3">

                    <div class="col-md-6">

                        <span class="info-label">
                            ผู้ติดต่อ
                        </span>

                        <div id="view-name"></div>

                    </div>


                    <div class="col-md-6">

                        <span class="info-label">
                            อีเมล
                        </span>

                        <div id="view-email"></div>

                    </div>

                </div>



                <div class="mb-3">

                    <span class="info-label">
                        หัวข้อ
                    </span>

                    <div id="view-subject"></div>

                </div>



                <div class="mb-3">

                    <span class="info-label">
                        วันที่ติดต่อ
                    </span>

                    <div id="view-date"></div>

                </div>



                <div>

                    <span class="info-label">
                        ข้อความ
                    </span>

                    <div
                        class="message-box"
                        id="view-message"
                    ></div>

                </div>


            </div>



            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >

                    ปิด

                </button>

            </div>


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


        const viewButtons =
            document.querySelectorAll(
                ".btn-view"
            );


        viewButtons.forEach(
            function (button) {


                button.addEventListener(
                    "click",
                    function () {


                        document.getElementById(
                            "view-name"
                        ).textContent =
                            this.dataset.name;


                        document.getElementById(
                            "view-email"
                        ).textContent =
                            this.dataset.email;


                        document.getElementById(
                            "view-subject"
                        ).textContent =
                            this.dataset.subject;


                        document.getElementById(
                            "view-date"
                        ).textContent =
                            this.dataset.date;


                        document.getElementById(
                            "view-message"
                        ).textContent =
                            this.dataset.message;


                        // เปลี่ยนสถานะเป็นอ่านแล้ว

                        const form =
                            document.createElement(
                                "form"
                            );

                        form.method = "POST";


                        const action =
                            document.createElement(
                                "input"
                            );

                        action.type = "hidden";

                        action.name = "action";

                        action.value = "read";


                        const id =
                            document.createElement(
                                "input"
                            );

                        id.type = "hidden";

                        id.name = "id";

                        id.value =
                            this.dataset.id;


                        form.appendChild(action);

                        form.appendChild(id);

                        document.body.appendChild(
                            form
                        );

                        form.submit();

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