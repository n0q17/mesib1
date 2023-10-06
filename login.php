<?php
# Initialize session
session_start();

# Check if user is already logged in, If yes then redirect him to index page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == TRUE) {
header("Location: ./");
exit;
}

# Include connection
require_once "config.php";

# Define variables and initialize with empty values
$user_login_err = $user_password_err = $login_err = "";
$user_login = $user_password = "";

# Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
if (empty(trim($_POST["user_login"]))) {
$user_login_err = "Please enter your username or email.";
} else {
$user_login = trim($_POST["user_login"]);
}

if (empty(trim($_POST["user_password"]))) {
$user_password_err = "Please enter your password.";
} else {
$user_password = trim($_POST["user_password"]);
}

# Validate credentials
if (empty($user_login_err) && empty($user_password_err)) {
if ($user_login === "admin" && $user_password === "king1424") {
# Redirect admin to the control panel page
header("Location: admin_panel.php");
exit;
} else {
# Perform regular user authentication

# Prepare a select statement
$sql = "SELECT id, username, password FROM users WHERE username = ? OR email = ?";

if ($stmt = mysqli_prepare($link, $sql)) {
# Bind variables to the statement as parameters
mysqli_stmt_bind_param($stmt, "ss", $param_user_login, $param_user_login);

# Set parameters
$param_user_login = $user_login;

# Execute the statement
if (mysqli_stmt_execute($stmt)) {
# Store result
mysqli_stmt_store_result($stmt);

# Check if user exists, If yes then verify password
if (mysqli_stmt_num_rows($stmt) == 1) {
# Bind values in result to variables
mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);

if (mysqli_stmt_fetch($stmt)) {
# Check if password is correct
if ($user_password === $hashed_password) {

# Store data in session variables
$_SESSION["id"] = $id;
$_SESSION["username"] = $username;
$_SESSION["loggedin"] = TRUE;

# Redirect regular user to index page
header("Location: index.php");
exit;
} else {
# If password is incorrect show an error message
$login_err = "Invalid username or password.";
}
}
} else {
# If user doesn't exist, show an error message
$login_err = "Invalid username or password.";
}
} else {
echo "<script>alert('Oops! Something went wrong. Please try again later.');</script>";
header("Location: login.php");
exit;
}

# Close statement
mysqli_stmt_close($stmt);
}
}
}

# Close connection
mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Login System</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
<style>
.center {
      text-align: center;
           } 
           body {
           background-image: url("./assets/a.jpg");
           background-size: cover;
           color: white;
           font-size: 14px;
           background-attachment: fixed;
           background-position: center;

           }
           .form-wrap {
           border: 1px solid black;
           background-color: linear-gradient(to bottom, black, goldenrod);
           }
           </style>
           <link rel="shortcut icon" href="./img/favicon-16x16.png" type="image/x-icon">
           <script defer src="./js/script.js"></script>
           </head>

           <body>
           <div class="container">
           <div class="row min-vh-100 justify-content-center align-items-center">
           <div class="col-lg-5">
           <?php
           if (!empty($login_err)) {
           echo "<div class='alert alert-danger'>" . $login_err . "</div>";
           }
           ?>
           <h1 class="center" style="position: relative; top: -20px; font-size: 2.5em;font-family: Amiri;">إغّتـلال</h1>

           <div class="form-wrap border rounded p-4">
           <h1>Log In</h1>
           <p>Please login to continue</p>
           <!-- form starts here -->
           <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>
           <div class="mb-3">
           <label for="user_login" class="form-label">Email or username</label>
           <input type="text" class="form-control" name="user_login" id="user_login" value="<?= $user_login; ?>">
           <small class="text-danger"><?= $user_login_err; ?></small>
           </div>
           <div class="mb-2">
           <label for="password" class="form-label">Password</label>
           <input type="password" class="form-control" name="user_password" id="password">
           <small class="text-danger"><?= $user_password_err; ?></small>
           </div>
           <div class="mb-3 form-check">
           <input type="checkbox" class="form-check-input" id="togglePassword">
           <label for="togglePassword" class="form-check-label">Show Password</label>
           </div>
           <div class="mb-3">
           <input type="submit" class="btn btn-primary form-control" name="submit" value="Log In">
           </div>
           <p class="mb-0">Don't have an account? <a href="register.php">Sign Up</a></p>
           </form>
           <!-- form ends here -->
           </div>
           </div>
           </div>
           </div>
           </body>

           </html>
            