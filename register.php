<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="http://localhost/smarthr/public/src/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="http://localhost/smarthr/public/src/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="http://localhost/smarthr/public/css/global.css">
    <link rel="stylesheet" href="http://localhost/smarthr/public/css/registration.css">

    <style>
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
                <p>Registration</p>
            </div>
        </div>
        <form action="register_process.php" method="POST">
            <div class="login-row">
                <div class="login-input">
                    <p>First Name</p>
                    <input type="text" name="firstname" placeholder="First Name" required autocomplete="off">
                </div>
                <div class="login-input">
                    <p>Middle Name</p>
                    <input type="text" name="middlename" placeholder="Middle Name" required autocomplete="off">
                </div>
            </div>
            <div class="login-row">
                <div class="login-input">
                    <p>Last Name</p>
                    <input type="text" name="lastname" placeholder="Last Name" required autocomplete="off">
                </div>
                <div class="login-input">
                    <p>Age</p>
                    <input type="number" name="age" placeholder="Age" required autocomplete="off">
                </div>
            </div>
            <div class="login-row">
                <div class="login-input">
                    <p>Gender</p>
                    <select name="gender" id="gender">
                        <option value="" selected disabled>Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="login-input">
                    <p>Phone Number</p>
                    <input type="text" name="phonenumber" id="phonenumber" placeholder="Phone Number" required autocomplete="off">
                </div>
            </div>
            <div class="login-input">
                <p>Address</p>
                <input type="text" name="address" id="address" placeholder="Address" required autocomplete="off">
            </div>
            <div class="login-input">
                <p>Email</p>
                <input type="email" name="email" id="email" placeholder="Email" required autocomplete="off">
            </div>
            <div class="login-input">
                <p>Password</p>
                <input type="password" name="password" id="password" placeholder="Password" required autocomplete="off">
            </div>
            <div class="login-input">
                <input type="submit" name="submit_register" value="REGISTER">
            </div>
        </form>
        <div class="footer">
            <a href="http://localhost/smarthr/"><i class="fa-solid fa-arrow-left"></i> HOME</a>
            <a href="http://localhost/smarthr/login.php">LOGIN <i class="fa-solid fa-arrow-right"></i></a>
        </div>
    </div>
</body>

</html>