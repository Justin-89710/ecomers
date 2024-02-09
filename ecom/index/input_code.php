<?php
// start session
session_start();
// check if person already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    header("Location: ../index.php");
    exit();
}

// show server errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// connect to database
$db = new sqlite3('../database/database.db');

if (isset($_POST['Submit_Code'])){
    //get code from session
    $code = $_SESSION['code'];
    $Email = $_SESSION['Email'];
    $Code = $_POST['Code'];
    if (empty($Code)) {
        $error = "Code is empty";
    }
    elseif ($Code != $code) {
        $error = "Code is incorrect";
    } else {
        header("Location: reset_password.php");
        exit();
    }
} elseif (isset($_POST['Submit_Login'])){
    $Email = $_POST['Email'];
    $Password = $_POST['Password'];
    // check if email is empty
    if (empty($Email)) {
        $error = "Email is empty";
    }
    // check if password is empty
    elseif (empty($Password)) {
        $error = "Password is empty";
    }
    // check if user exists
    $result = $db->query("SELECT * FROM user WHERE email = '$Email'");
    $row = $result->fetchArray();
    if (!$row) {
        $error = "Email does not exist";
    }
    // check if password is correct
    elseif (!password_verify($Password, $row['password'])) {
        $error = "Password is incorrect";
    } else {
        // Start session
        session_start();
        // Store data in session variables
        $_SESSION["loggedin"] = true;
        $_SESSION["Email"] = $Email;
        $_SESSION["First_Name"] = $row['first_name'];
        $_SESSION["Last_Name"] = $row['last_name'];
        $_SESSION["Rank"] = $row['rank'];
        header("Location: ../index.php");
        exit();
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login/Register</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- custom css -->
    <link rel="stylesheet" href="../CSS/login.css">
</head>
<body>
<div class="container" id="container">
    <div class="form-container sign-up-container">
        <form METHOD="post">
            <h1>Sign in</h1>
            <span>or use your account</span>
            <input type="email" placeholder="Email" name="Email" />
            <input type="password" placeholder="Password" name="Password" />
            <a href="login.php">Don't have an account?</a>
            <button name="Submit_Login">Sign In</button>
        </form>
    </div>
    <div class="form-container sign-in-container">
        <form METHOD="post">
            <h1>Reset password</h1>
            <span>Put in the code you got in your mail!</span>
            <input type="text" placeholder="Code" name="Code" />
            <button name="Submit_Code">Check code</button>
            <span>After you submit the code you got in your mail you can reset your password!</span>
        </form>
    </div>
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>Welcome Back!</h1>
                <p>To keep connected with us please login with your personal info</p>
                <button class="ghost" id="signIn">Sign In</button>
                <?php
                if (isset($error)) {
                    echo "<p class='alert alert-danger'>$error</p>";
                }
                ?>
            </div>
            <div class="overlay-panel overlay-right">
                <h1>Hello, Friend!</h1>
                <p>Enter your personal details and start journey with us</p>
                <button class="ghost" id="signUp">Sign Up</button>
                <?php
                if (isset($error)) {
                    echo "<p class='alert alert-danger'>$error</p>";
                }
                ?>
            </div>
        </div>
    </div>
</div>
<!-- Font Awesome -->
<script src="https://kit.fontawesome.com/6aeeb2cedf.js" crossorigin="anonymous"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<!-- Custom JS -->
<script src="../JS/login.js"></script>
</body>
</html>
