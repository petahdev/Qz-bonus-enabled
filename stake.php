<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to login page
    header("Location: index.php");
    exit();
}

// Connect to the database
include 'connect.php';

// Get the user ID from the session
$userId = $_SESSION['user_id'];

// Check if the username is set in the session
if (!isset($_SESSION['username'])) {
    // If username is not set, fetch it from the database
    $query = "SELECT username FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($username);
    $stmt->fetch();
    $stmt->close();

    // Store the fetched username in the session
    $_SESSION['username'] = $username;
} else {
    // Get the username from the session
    $username = $_SESSION['username'];
}

// Fetch the user's balance from the funds table
$query = "SELECT balance FROM funds WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($balance);
$stmt->fetch();
$stmt->close();

// Default balance if not set
if (!$balance) {
    $balance = '0.0000';
}

// Get the current hour for greeting message
$currentHour = (int)date('G');
if ($currentHour >= 6 && $currentHour < 12) {
    $greeting = 'Good Morning';
} elseif ($currentHour >= 12 && $currentHour < 18) {
    $greeting = 'Good Afternoon';
} else {
    $greeting = 'Good Evening';
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr" data-startbar="light" data-bs-theme="light">
<head>
    <meta charset="utf-8" />
    <title>Stake Input</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Stake Input Form" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    
    <style>
        body {
            background-color: #202221;
            color: #fff;
        }
        .card {
            background-color: #2c2f34;
            border: 1px solid #22c55e;
        }
        .auth-header-box {
            background-color: #000; 
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container-xxl">
        <div class="row vh-100 d-flex justify-content-center">
            <div class="col-12 align-self-center">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 mx-auto">
                            <div class="card">
                                <div class="card-body p-0 auth-header-box rounded-top">
                                    <div class="text-center p-3">
                                        <a href="index.php" class="logo logo-admin">
                                            <img src="assets/images/logo-sm.png" height="50" alt="logo" class="auth-logo">
                                        </a>
                                        <h4 class="mt-3 mb-1 fw-semibold text-white fs-18">Stake Your Amount</h4>
                                        <p class="text-muted fw-medium mb-0">Enter your stake below to participate.</p>
                                    </div>
                                </div>

                                <div class="card-body pt-0">
                                    <?php
                                    if (isset($_SESSION['success'])) {
                                        echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
                                        unset($_SESSION['success']);
                                    }
                                    if (isset($_SESSION['error'])) {
                                        echo '<div class="alert alert-error">' . $_SESSION['error'] . '</div>';
                                        unset($_SESSION['error']);
                                    }
                                    ?>
                                    <form action="deduct.php" method="POST">
                                        <div class="form-group mb-2">
                                            <label class="form-label" for="stake">Stake Amount (Ksh)</label>
                                            <input type="number" class="form-control" id="stake" name="stake_amount" min="10" required>
                                            <small class="form-text text-muted">Minimum stake amount is Ksh 10.</small>
                                        </div>
                                        <div class="d-grid mt-3">
                                            <button class="btn btn-primary" type="submit">Submit Stake</button>
                                        </div>
                                    </form>
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end card-body-->
            </div><!--end col-->
        </div><!--end row-->
    </div><!-- container -->
    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
