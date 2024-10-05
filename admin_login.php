<?php
// Start the session
session_start();

// Include the database connection
include 'connect.php';

// Initialize variables
$error = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email and password fields are not empty
    if (!empty($email) && !empty($password)) {
        // Prepare and execute the SQL query to fetch the user
        $query = "SELECT id, username, email, password, is_admin FROM users WHERE email = ?";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            // Check if the user exists
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $username, $db_email, $hashed_password, $is_admin);
                $stmt->fetch();

                // Verify the password
                if (password_verify($password, $hashed_password)) {
                    // Check if the user is an admin
                    if ($is_admin == 1) {
                        // Store user information in session
                        $_SESSION['user_id'] = $id;
                        $_SESSION['username'] = $username;
                        $_SESSION['is_admin'] = $is_admin;

                        // Redirect to the admin dashboard
                        header("Location: live.php");
                        exit();
                    } else {
                        $error = "You do not have admin privileges.";
                    }
                } else {
                    $error = "Invalid password.";
                }
            } else {
                $error = "User with this email does not exist.";
            }
        } else {
            $error = "Failed to execute query.";
        }
    } else {
        $error = "Please fill out all fields.";
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin Login</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/app.min.css">
</head>
<body>
    <div class="container">
        <div class="row vh-100 d-flex justify-content-center">
            <div class="col-lg-4 align-self-center">
                <div class="card">
                    <div class="card-body p-0 bg-black auth-header-box rounded-top">
                        <div class="text-center p-3">
                            <a href="index.php" class="logo logo-admin">
                                <img src="assets/images/logo-sm.png" height="50" alt="logo" class="auth-logo">
                            </a>
                            <h4 class="mt-3 mb-1 fw-semibold text-white fs-18">Admin Login</h4>
                        </div>
                    </div>

                    <div class="card-body pt-0">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <form method="POST" action="admin_login.php">
                            <div class="form-group mb-2">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required>
                            </div>
                            <div class="form-group mb-2">
                                <label for="userpassword">Password</label>
                                <input type="password" class="form-control" name="password" id="userpassword" placeholder="Enter password" required>
                            </div>
                            <input type="hidden" name="login" value="1">
                            <div class="form-group mb-0">
                                <button class="btn btn-primary w-100" type="submit">Log In</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
