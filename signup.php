<!DOCTYPE html>
<html lang="en" dir="ltr" data-startbar="light" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <title>Quizzy - Sign Up</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Gainly - Sign Up Page" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- App css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div class="container-xxl">
        <div class="row vh-100 d-flex justify-content-center align-items-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body p-0 bg-black auth-header-box rounded-top">
                        <div class="text-center p-3">
                            <a href="index.php" class="logo logo-admin">
                                <img src="assets/images/logo-sm.png" height="50" alt="logo" class="auth-logo">
                            </a>
                            <h4 class="mt-3 mb-1 fw-semibold text-white fs-18">Create Your Account</h4>
                            <p class="text-muted fw-medium mb-0">Fill the form below to register.</p>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <form class="my-4" action="register.php" method="POST">
                            <div class="form-group mb-2">
                                <label class="form-label" for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
                            </div><!--end form-group-->

                            <div class="form-group mb-2">
                                <label class="form-label" for="useremail">Email</label>
                                <input type="email" class="form-control" id="useremail" name="useremail" placeholder="Enter email" required>
                            </div><!--end form-group-->

                            <div class="form-group mb-2">
                                <label class="form-label" for="userpassword">Password</label>
                                <input type="password" class="form-control" name="password" id="userpassword" placeholder="Enter password" required>
                            </div><!--end form-group-->

                            <div class="form-group mb-2">
                                <label class="form-label" for="confirmpassword">Confirm Password</label>
                                <input type="password" class="form-control" name="confirmpassword" id="confirmpassword" placeholder="Confirm password" required>
                            </div><!--end form-group-->

                            <div class="form-group mb-2">
                                <label class="form-label" for="mobilenumber">Mobile Number</label>
                                <input type="text" class="form-control" id="mobilenumber" name="mobilenumber" placeholder="Enter mobile number" required>
                            </div><!--end form-group-->

                            <input type="hidden" name="register" value="1">

                            <div class="form-group mb-0 row">
                                <div class="col-12">
                                    <div class="d-grid mt-3">
                                       <button class="btn btn-primary" type="submit">Register <i class="fas fa-user-plus ms-1"></i></button>
                                    </div>
                                </div><!--end col-->
                            </div> <!--end form-group-->
                        </form><!--end form-->
                        <div class="text-center mb-2">
                            <p class="text-muted">Already have an account? <a href="index.php" class="text-primary ms-2">Log In</a></p>
                        </div>
                    </div><!--end card-body-->
                </div><!--end card-->
            </div><!--end col-->
        </div><!--end row-->
    </div><!-- container -->
</body>
<!--end body-->
</html>
