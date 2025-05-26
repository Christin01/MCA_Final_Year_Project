<?php
session_start();
ob_start(); 

$conn = new mysqli("localhost", "root", "", "smart_agriculture");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["signup"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];

        $sql = "INSERT INTO users (email, password) VALUES ('$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            $success_message = "Signup successful! Please login.";
        } else {
            $error_message = "Error: " . $conn->error;
        }
    } elseif (isset($_POST["login"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];

        $result = $conn->query("SELECT * FROM users WHERE email='$email' AND password='$password'");
        if ($result->num_rows > 0) {
            $_SESSION["user"] = $email;
            header("Location: agro.php"); 
            exit();
        } else {
            $error_message = "Invalid email or password. Please try again or Signup";
        }
    }
}

ob_end_flush(); 
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Farmer Login & Signup Form</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap">
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        html,body{
            display: grid;
            height: 100%;
            width: 100%;
            place-items: center;
            background: -webkit-linear-gradient(left, #003366,#004080,#0059b3, #0073e6);
        }
        .wrapper{
            overflow: hidden;
            max-width: 390px;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 15px 20px rgba(0,0,0,0.1);
        }
        .wrapper .title-text{
            display: flex;
            width: 200%;
        }
        .wrapper .title{
            width: 50%;
            font-size: 35px;
            font-weight: 600;
            text-align: center;
            transition: all 0.6s;
        }
        .wrapper .slide-controls{
            position: relative;
            display: flex;
            height: 50px;
            width: 100%;
            overflow: hidden;
            margin: 30px 0 10px 0;
            justify-content: space-between;
            border: 1px solid lightgrey;
            border-radius: 15px;
        }
        .slide-controls .slide{
            height: 100%;
            width: 100%;
            color: #93a5b7;
            font-size: 18px;
            font-weight: 500;
            text-align: center;
            line-height: 48px;
            cursor: pointer;
            z-index: 1;
            transition: all 0.6s ease;
        }
        .slide-controls label.signup{
            color: #000;
        }
        .slide-controls .slider-tab{
            position: absolute;
            height: 100%;
            width: 50%;
            left: 0;
            border-radius: 15px;
            background: -webkit-linear-gradient(left,#003366,#004080,#0059b3, #0073e6);
            transition: all 0.6s;
        }
        input[type="radio"]{
            display: none;
        }
        #signup:checked ~ .slider-tab{
            left: 50%;
        }
        #signup:checked ~ label.signup{
            color: #fff;
            cursor: default;
            user-select: none;
        }
        #login:checked ~ label.signup{
            color: #000;
        }
        .wrapper .form-container{
            width: 100%;
            overflow: hidden;
        }
        .form-container .form-inner{
            display: flex;
            width: 200%;
        }
        .form-container .form-inner form{
            width: 50%;
            transition: all 0.6s;
        }
        .form-inner form .field{
            height: 50px;
            width: 100%;
            margin-top: 20px;
        }
        .form-inner form .field input{
            height: 100%;
            width: 100%;
            outline: none;
            padding-left: 15px;
            border-radius: 15px;
            border: 1px solid lightgrey;
            font-size: 17px;
            transition: all 0.3s ease;
        }
        form .btn{
            height: 50px;
            width: 100%;
            border-radius: 15px;
            position: relative;
            overflow: hidden;
        }
        form .btn .btn-layer{
            height: 100%;
            width: 300%;
            position: absolute;
            left: -100%;
            background: -webkit-linear-gradient(right,#003366,#004080,#0059b3, #0073e6);
            border-radius: 15px;
            transition: all 0.4s ease;
        }
        form .btn:hover .btn-layer{
            left: 0;
        }
        form .btn input[type="submit"]{
            height: 100%;
            width: 100%;
            z-index: 1;
            position: relative;
            background: none;
            border: none;
            color: #004080;
            font-size: 20px;
            font-weight: 500;
            cursor: pointer;
        }
        .main-heading {
             text-align: center;
             font-size: 28px;
             font-weight: 700;
             color: #003366;
             margin-bottom: 20px;
        }
        .wrapper {
             text-align: center;
         }
    </style>
</head>
<body>

    <div class="wrapper">
        <h2 class="main-heading">Farmers Agro Monitor</h2>
        <div class="title-text">
            <div class="title login">Login Form</div>
            <div class="title signup">Signup Form</div>
        </div>
<?php 
    if (!empty($success_message)) { 
        echo "<p style='color: green;'>$success_message</p>"; 
    }
    if (!empty($error_message)) { 
        echo "<p style='color: red;'>$error_message</p>"; 
    } 
?>
        <div class="form-container">
            <div class="slide-controls">
                <input type="radio" name="slide" id="login" checked>
                <input type="radio" name="slide" id="signup">
                <label for="login" class="slide login">Login</label>
                <label for="signup" class="slide signup">Signup</label>
                <div class="slider-tab"></div>
            </div>
            <div class="form-inner">
            <form action="login.php" method="POST" class="login" onsubmit="return validateEmail('loginEmail', 'loginError')">
        <div class="field">
            <input type="email" id="loginEmail" name="email" placeholder="Email Address" required>
            <span class="error" id="loginError"></span>
        </div>
        <div class="field">
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <div class="field btn">
            <input type="submit" name="login" value="Login">
        </div>
        <div class="signup-link">Not a member? <a href="#">Signup now</a></div>
    </form>

    <form action="login.php" method="POST" class="signup" onsubmit="return validateForm()">
    <div class="field">
        <input type="email" id="signupEmail" name="email" placeholder="Email Address" required>
        <span class="error" id="signupError"></span>
    </div>
    <div class="field">
        <input type="password" id="password" name="password" placeholder="Password" required>
    </div>
    <div class="field">
        <input type="password" id="confirmPassword" name="confirm_password" placeholder="Confirm password" required>
        <span class="error" id="passwordError"></span>
    </div>
    <div class="field btn">
        <input type="submit" name="signup" value="Signup">
    </div>
</form>

<script>
    function validateForm() {
        var email = document.getElementById("signupEmail").value;
        var emailError = document.getElementById("signupError");
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("confirmPassword").value;
        var passwordError = document.getElementById("passwordError");

        var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        // Validate email
        if (!emailPattern.test(email)) {
            emailError.innerText = "Invalid email format!";
            return false;
        } else {
            emailError.innerText = "";
        }

        // Validate password match
        if (password !== confirmPassword) {
            passwordError.innerText = "Passwords do not match!";
            return false;
        } else {
            passwordError.innerText = "";
        }

        return true;
    }
</script>

            </div>
        </div>
    </div>
    <script>
        const loginText = document.querySelector(".title-text .login");
        const loginForm = document.querySelector("form.login");
        const loginBtn = document.querySelector("label.login");
        const signupBtn = document.querySelector("label.signup");
        const signupLink = document.querySelector("form .signup-link a");
        signupBtn.onclick = () => {
            loginForm.style.marginLeft = "-50%";
            loginText.style.marginLeft = "-50%";
        };
        loginBtn.onclick = () => {
            loginForm.style.marginLeft = "0%";
            loginText.style.marginLeft = "0%";
        };
        signupLink.onclick = () => {
            signupBtn.click();
            return false;
        };
        function validateEmail(emailId, errorId) {
            var email = document.getElementById(emailId).value;
            var errorSpan = document.getElementById(errorId);
            var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            if (!emailPattern.test(email)) {
                errorSpan.innerText = "Invalid email format!";
                return false;
            } else {
                errorSpan.innerText = "";
                return true;
            }
        }
    </script>
    
</body>
</html>
