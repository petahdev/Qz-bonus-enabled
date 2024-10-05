<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Connect to the database
include 'connect.php';

// Get the user ID from the session
$userId = $_SESSION['user_id'];

// Fetch the user's username if it's not already stored in the session
if (!isset($_SESSION['username'])) {
    $query = "SELECT username FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($username);
    $stmt->fetch();
    $stmt->close();

    // Store the username in the session for later use
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

// Get the current hour
$currentHour = (int)date('G'); // 'G' gives the hour in 24-hour format without leading zeros

// Determine the appropriate greeting
if ($currentHour >= 6 && $currentHour < 12) {
    $greeting = 'Good Morning';
} elseif ($currentHour >= 12 && $currentHour < 18) {
    $greeting = 'Good Afternoon';
} else {
    $greeting = 'Good Evening';
}

// Close the database connection
$conn->close();
?>

<!-- HTML Section -->





<!DOCTYPE html>
<html lang="en" dir="ltr" data-startbar="light" data-bs-theme="light">
<head>
    <meta charset="utf-8" />
    <title>Gainly - Withdrawal Form</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Gainly - Withdrawal Form" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

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
            background-color: #000; /* Change to black */
            color: #fff;
        }
        .alert-success {
            color: #22c55e;
            background-color: #fff;
            border-color: #22c55e;
        }
        .alert-error {
            color: #e3342f;
            background-color: #fff;
            border-color: #e3342f;
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
                                        <a href="index.html" class="logo logo-admin">
                                            <img src="assets/images/logo-sm.png" height="50" alt="logo" class="auth-logo">
                                        </a>
                                        <h4 class="mt-3 mb-1 fw-semibold text-white fs-18">Time to Cash Out with Gainly!</h4>
                                        <p class="text-muted fw-medium mb-0">Withdraw your earnings below.</p>
                                    </div>
                                </div>

                                <div class="card-body pt-0">
                                  
                                    <form action="withdraw.php" method="POST">
                                        <div class="form-group mb-2">
                                            <label class="form-label" for="amount">Amount (Ksh)</label>
                                            <input type="number" class="form-control" id="amount" 
                                                   value="<?php echo number_format($balance, 2); ?>" 
                                                   name="withdraw_amount" min="20" required readonly>
                                            <small class="form-text text-muted">Minimum withdrawal amount is Ksh 50.</small>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label class="form-label" for="mobile">Mobile Number</label>
                                            <input type="text" class="form-control" id="mobile" name="mobile" required>
                                        </div>
                                        <div class="d-grid mt-3">
                                            <button class="btn btn-primary" type="submit">Withdraw</button>
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
