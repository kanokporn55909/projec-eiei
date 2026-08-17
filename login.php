<?php
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"] ?? "";
    $password = $_POST["password"] ?? "";

    // ข้อมูลสำหรับทดสอบ
    $correct_username = "admin";
    $correct_password = "1234";

    if ($username === $correct_username && $password === $correct_password) {

        $_SESSION["username"] = $username;

        // ไปหน้า Dashboard
        header("Location: dashboard.php");
        exit();

    } else {

        $message = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";

    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>เข้าสู่ระบบ</title>

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

            min-height: 100vh;

            font-family:
                "Segoe UI",
                "Tahoma",
                sans-serif;

            /* พื้นหลังสีทองครีม */
            background:
                radial-gradient(
                    circle at 55% 35%,
                    #fffde7 0%,
                    #f8efc5 22%,
                    #dfcf91 50%,
                    #b9a966 100%
                );

            overflow: hidden;

            position: relative;

        }


        /* แสงฟุ้งด้านบน */

        body::before {

            content: "";

            position: absolute;

            width: 750px;

            height: 750px;

            left: 45%;

            top: -300px;

            transform: translateX(-50%);

            background:
                radial-gradient(
                    circle,
                    rgba(255,255,240,0.9) 0%,
                    rgba(255,255,220,0.5) 30%,
                    rgba(255,255,220,0) 70%
                );

            filter: blur(25px);

            pointer-events: none;

        }


        /* วงกลมแสงเล็ก ๆ */

        body::after {

            content: "";

            position: absolute;

            width: 500px;

            height: 500px;

            right: 5%;

            top: 5%;

            background:
                radial-gradient(
                    circle,
                    rgba(255,255,255,0.35) 0%,
                    rgba(255,255,255,0) 70%
                );

            filter: blur(20px);

            pointer-events: none;

        }


        /* พื้นที่หลัก */

        .login-container {

            width: 100%;

            min-height: 100vh;

            display: flex;

            align-items: center;

            justify-content: center;

            position: relative;

            z-index: 2;

        }


        /* ส่วนรูปด้านซ้าย */

        .person-area {

            width: 50%;

            height: 100vh;

            position: relative;

            display: flex;

            align-items: flex-end;

            justify-content: center;

        }


        .person-image {
    position: absolute;

    left: 50%;
    top: 50%;

    transform: translate(-50%, -50%);

    width: 280px;
    height: 280px;

    object-fit: contain;

    z-index: 1;

    filter: drop-shadow(0 10px 20px rgba(80, 60, 15, 0.15));
}


        /* ถ้ายังไม่มีรูป */

        .person-placeholder {

            position: absolute;

            bottom: 0;

            left: 50%;

            transform: translateX(-50%);

            width: 350px;

            height: 500px;

            border-radius: 50% 50% 0 0;

            background:
                linear-gradient(
                    180deg,
                    rgba(255,255,255,0.2),
                    rgba(150,120,50,0.15)
                );

            display: flex;

            align-items: center;

            justify-content: center;

            color: #806d38;

            font-size: 24px;

        }


        /* ฝั่ง Login */

        .login-area {

            width: 50%;

            min-height: 100vh;

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 30px;

        }


        /* กล่อง Login */

        .login-box {

            width: 430px;

            max-width: 100%;

            padding: 42px;

            border-radius: 25px;

            background:
                rgba(255, 252, 235, 0.72);

            border:
                1px solid rgba(255,255,255,0.65);

            box-shadow:
                0 25px 60px rgba(80, 60, 15, 0.20);

            backdrop-filter: blur(15px);

            -webkit-backdrop-filter: blur(15px);

        }


        /* หัวข้อ */

        .login-title {

            text-align: center;

            color: #594817;

            font-size: 34px;

            font-weight: 700;

            margin-bottom: 8px;

        }


        .login-subtitle {

            text-align: center;

            color: #806f3d;

            margin-bottom: 30px;

            font-size: 15px;

        }


        /* Label */

        .form-label {

            color: #5d4b1e;

            font-weight: 600;

        }


        /* ช่องกรอก */

        .form-control {

            height: 52px;

            border-radius: 12px;

            border: 1px solid #d3c183;

            background: rgba(255,255,255,0.75);

            padding-left: 17px;

            color: #4d401d;

        }


        .form-control:focus {

            border-color: #a88b3c;

            box-shadow:
                0 0 0 3px rgba(168,139,60,0.15);

        }


        /* ปุ่ม */

        .login-button {

            height: 52px;

            border: none;

            border-radius: 12px;

            background:
                linear-gradient(
                    135deg,
                    #d4a72c,
                    #a77c16
                );

            color: white;

            font-size: 17px;

            font-weight: 600;

            box-shadow:
                0 8px 18px rgba(130,95,15,0.25);

            transition: 0.25s;

        }


        .login-button:hover {

            transform: translateY(-2px);

            box-shadow:
                0 12px 25px rgba(130,95,15,0.35);

        }


        /* ลิงก์กลับหน้าหลัก */

        .back-link {

            color: #765f25;

            text-decoration: none;

            font-size: 14px;

        }


        .back-link:hover {

            color: #4e3c13;

            text-decoration: underline;

        }


        /* แจ้งเตือน */

        .alert-danger {

            border-radius: 10px;

            font-size: 14px;

        }


        /* Responsive สำหรับมือถือ */

        @media (max-width: 900px) {

            body {

                overflow-y: auto;

            }

            .login-container {

                flex-direction: column;

            }

            .person-area {

                width: 100%;

                height: 35vh;

                min-height: 250px;

            }

            .person-image {

                height: 35vh;

            }

            .login-area {

                width: 100%;

                min-height: 65vh;

                padding: 20px;

                align-items: flex-start;

            }

            .login-box {

                padding: 30px;

            }

        }


        @media (max-width: 500px) {

            .person-area {

                display: none;

            }

            .login-area {

                width: 100%;

                min-height: 100vh;

                align-items: center;

            }

            .login-box {

                padding: 28px 22px;

            }

            .login-title {

                font-size: 28px;

            }

        }

    </style>

</head>


<body>


<div class="login-container">


    <div class="person-area">


        <img src="images/RMUTI.png" class="person-image" alt="RMUTI Logo">
        >

    </div>


    <div class="login-area">


        <div class="login-box">


            <div class="login-title">

                เข้าสู่ระบบ

            </div>


            <div class="login-subtitle">

                ระบบจัดการข้อมูลการศึกษา

            </div>



            <?php if ($message != ""): ?>

                <div class="alert alert-danger text-center">

                    <?php echo htmlspecialchars($message); ?>

                </div>

            <?php endif; ?>



            <form method="POST">



                <div class="mb-4">

                    <label class="form-label">

                        ชื่อผู้ใช้

                    </label>

                    <input
                        type="text"
                        name="username"
                        class="form-control"
                        placeholder="กรอกชื่อผู้ใช้"
                        autocomplete="username"
                        required
                    >

                </div>



                <div class="mb-4">

                    <label class="form-label">

                        รหัสผ่าน

                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="กรอกรหัสผ่าน"
                        autocomplete="current-password"
                        required
                    >

                </div>



                <div class="d-grid">

                    <button
                        type="submit"
                        class="login-button"
                    >

                        เข้าสู่ระบบ

                    </button>

                </div>


            </form>



            <hr style="border-color: #d8c996; margin: 28px 0;">



            <div class="text-center">

    

            </div>


        </div>

    </div>


</div>


</body>

</html>