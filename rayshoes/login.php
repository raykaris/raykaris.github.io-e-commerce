<?php
session_start();
include 'connect.php'; 

// Check if the user is already logged in
if (isset($_SESSION['signIn'])) {
    header("Location: shop.php");
    exit();
}

// Handle login logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signIn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // validate the user credentials
    $stmt = $loginConn->prepare("SELECT Id FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found, set session variable
        $row = $result->fetch_assoc();
        $_SESSION['Id'] = $row['Id'];

        // Redirect to cart if specified in URL
        if (isset($_GET['redirect']) && $_GET['redirect'] == 'shop') {
            header("Location: shop.php");
            exit();
        } else {
            header("Location: index.html");
            exit();
        }
    } else {
        // Handle invalid credentials
        $error_message = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- bootstrap icons link --> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <link rel="stylesheet" href="assets/css/login.css">

</head>
<body>
    <div class="container-s" id="signup" style="display: none;">
        <h1 class="form-title">Register</h1>
        <form method="post" action="register.php">
            <div class="input-group">
                <i class="bi bi-person-fill"></i>
                <input type="text" name="fName" id="fName" placeholder="First Name" required>
                <label for="fName">First Name</label>
            </div>
            <div class="input-group">
                <i class="bi bi-person-fill"></i>
                <input type="text" name="lName" id="lName" placeholder="Last Name" required>
                <label for="lName">Last Name</label>
            </div>
            <div class="input-group">
                <i class="bi bi-envelope-fill"></i>
                <input type="email" name="email" id="email" placeholder="email" required>
                <label for="email">Email</label>
            </div>
            <div class="input-group">
                <i class="bi bi-lock-fill"></i>
                <input type="password" name="password" id="password" placeholder="password" required>
                <label for="password">Password</label>
            </div>
            <input type="submit" class="btn-sn" value="Sign Up" name="signUp">
        </form>
        <p class="or">
            Sign Up With
        </p>
        <div class="icons">
            <i class="bi bi-google"></i>
            <i class="bi bi-facebook"></i>
        </div>
        <div class="links">
            <p>Already have an account?</p>
            <button id="signInButton">Login</button>
        </div>
    </div>

    <div class="container-s" id="signIn">
        <h1 class="form-title">Login</h1>
        <form method="post" action="register.php">
            <div class="input-group">
                <i class="bi bi-envelope-fill"></i>
                <input type="email" name="email" id="email" placeholder="email" required>
                <label for="email">Email</label>
            </div>
            <div class="input-group">
                <i class="bi bi-lock-fill"></i>
                <input type="password" name="password" id="password" placeholder="password" required>
                <label for="password">Password</label>
            </div>
            <p class="recover">
                <a href="#">Recover password</a>
            </p>
            <input type="submit" onclick="alertFunction()" class="btn-sn" value="Login" name="signIn" target="_blank">
        </form>
        <p class="or">
            Sign Up With
        </p>
        <div class="icons">
            <i class="bi bi-google"></i>
            <i class="bi bi-facebook"></i>
        </div>
        <div class="links">
            <p>Don't have account yet?</p>
            <button id="signUpButton">Sign Up</button>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="assets/js/login.js"></script>
</body>
</html>