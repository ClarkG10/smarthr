<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Login</title>
    <link rel="stylesheet" href="http://localhost/smarthr/public/src/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="http://localhost/smarthr/public/src/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="http://localhost/smarthr/public/css/global.css">
    <link rel="stylesheet" href="http://localhost/smarthr/public/css/login.css">

    <style>
        #adminNav {
            display: flex;
            flex-direction: column;
            text-align: center;
            text-decoration: none;
        }

        #adminNav span {
            font-size: 12px;
        }

        .footer a {
            font-size: 14px;
        }
    </style>
</head>

<body id="loginBody">
    <div class="wrapper">
        <div class="header">
            <i class="fa-solid fa-droplet"></i>
            <div>
                <h2>Water District</h2>
                <p>Applicant Login</p>
            </div>
        </div>
        <form action="login_process.php" method="POST">
            <div class="login-input">
                <p>Email</p>
                <input type="email" name="email" id="email" required placeholder="Enter Email" autocomplete="off">
            </div>
            <div class="login-input">
                <p>Password</p>
                <input type="password" name="password" id="password" required placeholder="Enter Password" autocomplete="off">
            </div>
            <div class="login-input">
                <input type="submit" name="submit_login" value="LOGIN">
                <a href="">Forgot Password?</a>
            </div>
        </form>
        <div class="footer">
            <a href="http://localhost/smarthr/"><i class="fa-solid fa-arrow-left"></i> HOME</a>
            <a href="http://localhost/smarthr/emp/login.php" id="adminNav"> <i class="fa-solid fa-user-tie"></i> <span>BWD-EMP</span></a>
            <a href="http://localhost/smarthr/register.php">REGISTER <i class="fa-solid fa-arrow-right"></i></a>
        </div>
    </div>
</body>

</html>