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
        .login-input select {
            height: 30px;
        }
    </style>
</head>

<body id="loginBody">
    <div class="wrapper">
        <div class="header">
            <i class="fa-solid fa-droplet"></i>
            <div>
                <h2>Water District</h2>
                <p>EMP LOGIN</p>
            </div>
        </div>
        <form action="handlers/login_process.php" method="POST">
            <div class="login-input">
                <p>Email</p>
                <input type="email" name="email" id="email" required placeholder="Enter Email" autocomplete="off">
            </div>
            <div class="login-input">
                <p>Password</p>
                <input type="password" name="password" id="password" required placeholder="Enter Password" autocomplete="off">
            </div>
            <div class="login-input">
                <p>Role</p>
                <select name="role" id="role" required>
                    <option value="" selected disabled>Select Role</option>
                    <option value="Staff">Staff</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>
            <div class="login-input">
                <input type="submit" name="submit_login" value="LOGIN">
            </div>
        </form>
        <div class="footer">
            <a href="http://localhost/smarthr/"><i class="fa-solid fa-arrow-left"></i> HOME</a>
        </div>
    </div>
</body>

</html>