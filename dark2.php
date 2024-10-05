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

// Fetch the total click count and calculate the total amount earned
$amount_per_click = 4; // Amount in Ksh per click

// Fetch the total click count for the user in the last 24 hours
$query = "SELECT COUNT(*) AS daily_clicks FROM link_clicks WHERE user_id = ? AND last_click_time >= NOW() - INTERVAL 24 HOUR";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($daily_clicks);
$stmt->fetch();
$stmt->close();

// Fetch the total click count for the user overall
$query = "SELECT SUM(click_count) AS total_clicks FROM link_clicks WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_clicks = $row['total_clicks'] ?? 0;

// Calculate the total amount earned
$total_amount_earned = $total_clicks * $amount_per_click;

// Close the connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en" dir="ltr" data-startbar="dark" data-bs-theme="dark">

    <head>
        

        <meta charset="utf-8" />
                <title>Rizz | Rizz - Admin & Dashboard Template</title>
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
                <meta content="" name="author" />
                <meta http-equiv="X-UA-Compatible" content="IE=edge" />

                <!-- App favicon -->
                <link rel="shortcut icon" href="assets/images/favicon.ico">

       
         <!-- App css -->
         <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
         <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
         <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />

    </head>

    
    <!-- Top Bar Start -->
    <body>
        <!-- Top Bar Start -->
        <div class="topbar d-print-none">
            <div class="container-xxl">
                <nav class="topbar-custom d-flex justify-content-between" id="topbar-custom">    
        

                    <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">                        
                        <li>
                            <button class="nav-link mobile-menu-btn nav-icon" id="togglemenu">
                                <i class="iconoir-menu-scale"></i>
                            </button>
                        </li> 
                        <li class="mx-3 welcome-text">
                            <h3 class="mb-0 fw-bold text-truncate">Good Morning, James!</h3>
                            <!-- <h6 class="mb-0 fw-normal text-muted text-truncate fs-14">Here's your overview this week.</h6> -->
                        </li>                   
                    </ul>
                    <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">
                        <li class="hide-phone app-search">
                            <form role="search" action="dark-ecommerce-index.html#" method="get">
                                <input type="search" name="search" class="form-control top-search mb-0" placeholder="Search here...">
                                <button type="submit"><i class="iconoir-search"></i></button>
                            </form>
                        </li>     
                        <li class="dropdown">
                            <a class="nav-link dropdown-toggle arrow-none nav-icon" data-bs-toggle="dropdown" href="dark-ecommerce-index.html#" role="button"
                            aria-haspopup="false" aria-expanded="false">
                            <img src="assets/images/flags/us_flag.jpg" alt="" class="thumb-sm rounded-circle">
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="dark-ecommerce-index.html#"><img src="assets/images/flags/us_flag.jpg" alt="" height="15" class="me-2">English</a>
                                <a class="dropdown-item" href="dark-ecommerce-index.html#"><img src="assets/images/flags/spain_flag.jpg" alt="" height="15" class="me-2">Spanish</a>
                                <a class="dropdown-item" href="dark-ecommerce-index.html#"><img src="assets/images/flags/germany_flag.jpg" alt="" height="15" class="me-2">German</a>
                                <a class="dropdown-item" href="dark-ecommerce-index.html#"><img src="assets/images/flags/french_flag.jpg" alt="" height="15" class="me-2">French</a>
                            </div>
                        </li><!--end topbar-language-->
        
                        <li class="topbar-item">
                            <a class="nav-link nav-icon" href="javascript:void(0);" id="light-dark-mode">
                                <i class="icofont-moon dark-mode"></i>
                                <i class="icofont-sun light-mode"></i>
                            </a>                    
                        </li>
    
                        <li class="dropdown topbar-item">
                            <a class="nav-link dropdown-toggle arrow-none nav-icon" data-bs-toggle="dropdown" href="dark-ecommerce-index.html#" role="button"
                                aria-haspopup="false" aria-expanded="false">
                                <i class="icofont-bell-alt"></i>
                                <span class="alert-badge"></span>
                            </a>
                            <div class="dropdown-menu stop dropdown-menu-end dropdown-lg py-0">
                        
                                <h5 class="dropdown-item-text m-0 py-3 d-flex justify-content-between align-items-center">
                                    Notifications <a href="dark-ecommerce-index.html#" class="badge text-body-tertiary badge-pill">
                                        <i class="iconoir-plus-circle fs-4"></i>
                                    </a>
                                </h5>
                                <ul class="nav nav-tabs nav-tabs-custom nav-success nav-justified mb-1" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link mx-0 active" data-bs-toggle="tab" href="dark-ecommerce-index.html#All" role="tab" aria-selected="true">
                                            All <span class="badge bg-primary-subtle text-primary badge-pill ms-1">24</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link mx-0" data-bs-toggle="tab" href="dark-ecommerce-index.html#Projects" role="tab" aria-selected="false" tabindex="-1">
                                            Projects
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link mx-0" data-bs-toggle="tab" href="dark-ecommerce-index.html#Teams" role="tab" aria-selected="false" tabindex="-1">
                                            Team
                                        </a>
                                    </li>
                                </ul>
                                <div class="ms-0" style="max-height:230px;" data-simplebar>
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="All" role="tabpanel" aria-labelledby="all-tab" tabindex="0">
                                            <!-- item-->
                                            <a href="dark-ecommerce-index.html#" class="dropdown-item py-3">
                                                <small class="float-end text-muted ps-2">2 min ago</small>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 bg-primary-subtle text-primary thumb-md rounded-circle">
                                                        <i class="iconoir-wolf fs-4"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2 text-truncate">
                                                        <h6 class="my-0 fw-normal text-dark fs-13">Your order is placed</h6>
                                                        <small class="text-muted mb-0">Dummy text of the printing and industry.</small>
                                                    </div><!--end media-body-->
                                                </div><!--end media-->
                                            </a><!--end-item-->
                                            <!-- item-->
                                            <a href="dark-ecommerce-index.html#" class="dropdown-item py-3">
                                                <small class="float-end text-muted ps-2">10 min ago</small>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 bg-primary-subtle text-primary thumb-md rounded-circle">
                                                        <i class="iconoir-apple-swift fs-4"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2 text-truncate">
                                                        <h6 class="my-0 fw-normal text-dark fs-13">Meeting with designers</h6>
                                                        <small class="text-muted mb-0">It is a long established fact that a reader.</small>
                                                    </div><!--end media-body-->
                                                </div><!--end media-->
                                            </a><!--end-item-->
                                            <!-- item-->
                                            <a href="dark-ecommerce-index.html#" class="dropdown-item py-3">
                                                <small class="float-end text-muted ps-2">40 min ago</small>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 bg-primary-subtle text-primary thumb-md rounded-circle">                                                    
                                                        <i class="iconoir-birthday-cake fs-4"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2 text-truncate">
                                                        <h6 class="my-0 fw-normal text-dark fs-13">UX 3 Task complete.</h6>
                                                        <small class="text-muted mb-0">Dummy text of the printing.</small>
                                                    </div><!--end media-body-->
                                                </div><!--end media-->
                                            </a><!--end-item-->
                                            <!-- item-->
                                            <a href="dark-ecommerce-index.html#" class="dropdown-item py-3">
                                                <small class="float-end text-muted ps-2">1 hr ago</small>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 bg-primary-subtle text-primary thumb-md rounded-circle">
                                                        <i class="iconoir-drone fs-4"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2 text-truncate">
                                                        <h6 class="my-0 fw-normal text-dark fs-13">Your order is placed</h6>
                                                        <small class="text-muted mb-0">It is a long established fact that a reader.</small>
                                                    </div><!--end media-body-->
                                                </div><!--end media-->
                                            </a><!--end-item-->
                                            <!-- item-->
                                            <a href="dark-ecommerce-index.html#" class="dropdown-item py-3">
                                                <small class="float-end text-muted ps-2">2 hrs ago</small>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 bg-primary-subtle text-primary thumb-md rounded-circle">
                                                        <i class="iconoir-user fs-4"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2 text-truncate">
                                                        <h6 class="my-0 fw-normal text-dark fs-13">Payment Successfull</h6>
                                                        <small class="text-muted mb-0">Dummy text of the printing.</small>
                                                    </div><!--end media-body-->
                                                </div><!--end media-->
                                            </a><!--end-item-->
                                        </div>
                                        <div class="tab-pane fade" id="Projects" role="tabpanel" aria-labelledby="projects-tab" tabindex="0">
                                            <!-- item-->
                                            <a href="dark-ecommerce-index.html#" class="dropdown-item py-3">
                                                <small class="float-end text-muted ps-2">40 min ago</small>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 bg-primary-subtle text-primary thumb-md rounded-circle">                                                    
                                                        <i class="iconoir-birthday-cake fs-4"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2 text-truncate">
                                                        <h6 class="my-0 fw-normal text-dark fs-13">UX 3 Task complete.</h6>
                                                        <small class="text-muted mb-0">Dummy text of the printing.</small>
                                                    </div><!--end media-body-->
                                                </div><!--end media-->
                                            </a><!--end-item-->
                                            <!-- item-->
                                            <a href="dark-ecommerce-index.html#" class="dropdown-item py-3">
                                                <small class="float-end text-muted ps-2">1 hr ago</small>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 bg-primary-subtle text-primary thumb-md rounded-circle">
                                                        <i class="iconoir-drone fs-4"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2 text-truncate">
                                                        <h6 class="my-0 fw-normal text-dark fs-13">Your order is placed</h6>
                                                        <small class="text-muted mb-0">It is a long established fact that a reader.</small>
                                                    </div><!--end media-body-->
                                                </div><!--end media-->
                                            </a><!--end-item-->
                                            <!-- item-->
                                            <a href="dark-ecommerce-index.html#" class="dropdown-item py-3">
                                                <small class="float-end text-muted ps-2">2 hrs ago</small>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 bg-primary-subtle text-primary thumb-md rounded-circle">
                                                        <i class="iconoir-user fs-4"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2 text-truncate">
                                                        <h6 class="my-0 fw-normal text-dark fs-13">Payment Successfull</h6>
                                                        <small class="text-muted mb-0">Dummy text of the printing.</small>
                                                    </div><!--end media-body-->
                                                </div><!--end media-->
                                            </a><!--end-item-->
                                        </div>
                                        <div class="tab-pane fade" id="Teams" role="tabpanel" aria-labelledby="teams-tab" tabindex="0">
                                            <!-- item-->
                                            <a href="dark-ecommerce-index.html#" class="dropdown-item py-3">
                                                <small class="float-end text-muted ps-2">1 hr ago</small>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 bg-primary-subtle text-primary thumb-md rounded-circle">
                                                        <i class="iconoir-drone fs-4"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2 text-truncate">
                                                        <h6 class="my-0 fw-normal text-dark fs-13">Your order is placed</h6>
                                                        <small class="text-muted mb-0">It is a long established fact that a reader.</small>
                                                    </div><!--end media-body-->
                                                </div><!--end media-->
                                            </a><!--end-item-->
                                            <!-- item-->
                                            <a href="dark-ecommerce-index.html#" class="dropdown-item py-3">
                                                <small class="float-end text-muted ps-2">2 hrs ago</small>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 bg-primary-subtle text-primary thumb-md rounded-circle">
                                                        <i class="iconoir-user fs-4"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2 text-truncate">
                                                        <h6 class="my-0 fw-normal text-dark fs-13">Payment Successfull</h6>
                                                        <small class="text-muted mb-0">Dummy text of the printing.</small>
                                                    </div><!--end media-body-->
                                                </div><!--end media-->
                                            </a><!--end-item-->
                                        </div>
                                    </div>
                            
                                </div>
                                <!-- All-->
                                <a href="pages-notifications.html" class="dropdown-item text-center text-dark fs-13 py-2">
                                    View All <i class="fi-arrow-right"></i>
                                </a>
                            </div>
                        </li>
    
                        <li class="dropdown topbar-item">
                            <a class="nav-link dropdown-toggle arrow-none nav-icon" data-bs-toggle="dropdown" href="dark-ecommerce-index.html#" role="button"
                                aria-haspopup="false" aria-expanded="false">
                                <img src="assets/images/users/avatar-1.jpg" alt="" class="thumb-lg rounded-circle">
                            </a>
                            <div class="dropdown-menu dropdown-menu-end py-0">
                                <div class="d-flex align-items-center dropdown-item py-2 bg-secondary-subtle">
                                    <div class="flex-shrink-0">
                                        <img src="assets/images/users/avatar-1.jpg" alt="" class="thumb-md rounded-circle">
                                    </div>
                                    <div class="flex-grow-1 ms-2 text-truncate align-self-center">
                                        <h6 class="my-0 fw-medium text-dark fs-13">William Martin</h6>
                                        <small class="text-muted mb-0">Front End Developer</small>
                                    </div><!--end media-body-->
                                </div>
                                <div class="dropdown-divider mt-0"></div>
                                <small class="text-muted px-2 pb-1 d-block">Account</small>
                                <a class="dropdown-item" href="pages-profile.html"><i class="las la-user fs-18 me-1 align-text-bottom"></i> Profile</a>
                                <a class="dropdown-item" href="pages-faq.html"><i class="las la-wallet fs-18 me-1 align-text-bottom"></i> Earning</a>
                                <small class="text-muted px-2 py-1 d-block">Settings</small>                        
                                <a class="dropdown-item" href="pages-profile.html"><i class="las la-cog fs-18 me-1 align-text-bottom"></i>Account Settings</a>
                                <a class="dropdown-item" href="pages-profile.html"><i class="las la-lock fs-18 me-1 align-text-bottom"></i> Security</a>
                                <a class="dropdown-item" href="pages-faq.html"><i class="las la-question-circle fs-18 me-1 align-text-bottom"></i> Help Center</a>                       
                                <div class="dropdown-divider mb-0"></div>
                                <a class="dropdown-item text-danger" href="auth-login.html"><i class="las la-power-off fs-18 me-1 align-text-bottom"></i> Logout</a>
                            </div>
                        </li>
                    </ul><!--end topbar-nav-->
                </nav>
                <!-- end navbar-->
            </div>
        </div>
        <!-- Top Bar End -->
        <!-- leftbar-tab-menu -->
        <div class="startbar d-print-none">
            <!--start brand-->
            <div class="brand">
                <a href="index.html" class="logo">
                    <span>
                        <img src="assets/images/logo-sm.png" alt="logo-small" class="logo-sm">
                    </span>
                    <span class="">
                        <img src="assets/images/logo-light.png" alt="logo-large" class="logo-lg logo-light">
                        <img src="assets/images/logo-dark.png" alt="logo-large" class="logo-lg logo-dark">
                    </span>
                </a>
            </div>
            <!--end brand-->
            <!--start startbar-menu-->
            <div class="startbar-menu" >
                <div class="startbar-collapse" id="startbarCollapse" data-simplebar>
                    <div class="d-flex align-items-start flex-column w-100">
                        <!-- Navigation -->
                        <ul class="navbar-nav mb-auto w-100">
                            <li class="menu-label pt-0 mt-0">
                                <!-- <small class="label-border">
                                    <div class="border_left hidden-xs"></div>
                                    <div class="border_right"></div>
                                </small> -->
                                <span>Main Menu</span>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="dark-ecommerce-index.html#sidebarDashboards" data-bs-toggle="collapse" role="button"
                                    aria-expanded="false" aria-controls="sidebarDashboards">
                                    <i class="iconoir-home-simple menu-icon"></i>
                                    <span>Dashboards</span>
                                </a>
                                <div class="collapse " id="sidebarDashboards">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="index.html">Analytics</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="ecommerce-index.html">Ecommerce</a>
                                        </li><!--end nav-item-->
                                    </ul><!--end nav-->
                                </div><!--end startbarDashboards-->
                            </li><!--end nav-item-->
                            <li class="nav-item">
                                <a class="nav-link" href="dark-ecommerce-index.html#sidebarApplications" data-bs-toggle="collapse" role="button"
                                    aria-expanded="false" aria-controls="sidebarApplications">
                                    <i class="iconoir-view-grid menu-icon"></i>
                                    <span>Applications</span>
                                </a>
                                <div class="collapse " id="sidebarApplications">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="dark-ecommerce-index.html#sidebarAnalytics" data-bs-toggle="collapse" role="button"
                                                aria-expanded="false" aria-controls="sidebarAnalytics">                                        
                                                <span>Analytics</span>
                                            </a>
                                            <div class="collapse " id="sidebarAnalytics">
                                                <ul class="nav flex-column">
                                                    <li class="nav-item">
                                                        <a href="analytics-customers.html" class="nav-link ">Customers</a>
                                                    </li><!--end nav-item-->
                                                    <li class="nav-item">
                                                        <a href="analytics-reports.html" class="nav-link ">Reports</a>
                                                    </li><!--end nav-item-->
                                                </ul><!--end nav-->
                                            </div>
                                        </li><!--end nav-item-->                                
                                        <li class="nav-item">
                                            <a class="nav-link" href="dark-ecommerce-index.html#sidebarProjects" data-bs-toggle="collapse" role="button"
                                                aria-expanded="false" aria-controls="sidebarProjects">                                        
                                                <span>Projects</span>
                                            </a>
                                            <div class="collapse " id="sidebarProjects">
                                                <ul class="nav flex-column">
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="projects-clients.html">Clients</a>
                                                    </li><!--end nav-item-->
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="projects-team.html">Team</a>
                                                    </li><!--end nav-item-->
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="projects-project.html">Project</a>
                                                    </li><!--end nav-item-->
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="projects-task.html">Task</a>
                                                    </li><!--end nav-item-->
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="projects-kanban-board.html">Kanban Board</a>
                                                    </li><!--end nav-item-->
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="projects-chat.html">Chat</a>
                                                    </li><!--end nav-item-->
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="projects-users.html">Users</a>
                                                    </li><!--end nav-item-->
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="projects-create.html">Project Create</a>
                                                    </li><!--end nav-item--> 
                                                </ul><!--end nav-->
                                            </div>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="dark-ecommerce-index.html#sidebarEcommerce" data-bs-toggle="collapse" role="button"
                                                aria-expanded="false" aria-controls="sidebarEcommerce">                                        
                                                <span>Ecommerce</span>
                                            </a>
                                            <div class="collapse " id="sidebarEcommerce">
                                                <ul class="nav flex-column">
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="ecommerce-products.html">Products</a>
                                                    </li><!--end nav-item-->
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="ecommerce-customers.html">Customers</a>
                                                    </li><!--end nav-item-->
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="ecommerce-customer-details.html">Customer Details</a>
                                                    </li><!--end nav-item-->
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="ecommerce-orders.html">Orders</a>
                                                    </li><!--end nav-item-->
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="ecommerce-order-details.html">Order Details</a>
                                                    </li><!--end nav-item-->
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="ecommerce-refunds.html">Refunds</a>
                                                    </li><!--end nav-item-->
                                                </ul><!--end nav-->
                                            </div>
                                        </li><!--end nav-item-->
                                 
                                        <li class="nav-item">
                                            <a class="nav-link" href="apps-chat.html">Chat</a>
                                        </li><!--end nav-item--> 
                                        <li class="nav-item">
                                            <a class="nav-link" href="apps-contact-list.html">Contact List</a>
                                        </li><!--end nav-item--> 
                                        <li class="nav-item">
                                            <a class="nav-link" href="apps-calendar.html">Calendar</a>
                                        </li><!--end nav-item-->  
                                        <li class="nav-item">
                                            <a class="nav-link" href="apps-invoice.html">Invoice</a>
                                        </li><!--end nav-item-->                                
                                    </ul><!--end nav-->
                                </div><!--end startbarApplications-->
                            </li><!--end nav-item-->
                            <li class="menu-label mt-2">
                                <small class="label-border">
                                    <div class="border_left hidden-xs"></div>
                                    <div class="border_right"></div>
                                </small>
                                <span>Components</span>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="dark-ecommerce-index.html#sidebarElements" data-bs-toggle="collapse" role="button"
                                    aria-expanded="false" aria-controls="sidebarElements">
                                    <i class="iconoir-compact-disc menu-icon"></i>
                                    <span>UI Elements</span>
                                </a>
                                <div class="collapse " id="sidebarElements">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="ui-alerts.html">Alerts</a>
                                        </li><!--end nav-item--> 
                                        <li class="nav-item">
                                            <a class="nav-link" href="ui-avatar.html">Avatar</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="ui-buttons.html">Buttons</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="ui-badges.html">Badges</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="ui-cards.html">Cards</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="ui-carousels.html">Carousels</a>
                                        </li><!--end nav-item-->                                
                                        <li class="nav-item">
                                            <a class="nav-link" href="ui-dropdowns.html">Dropdowns</a>
                                        </li><!--end nav-item-->                                   
                                        <li class="nav-item">
                                            <a class="nav-link" href="ui-grids.html">Grids</a>
                                        </li><!--end nav-item-->                                
                                        <li class="nav-item">
                                            <a class="nav-link" href="ui-images.html">Images</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="ui-list.html">List</a>
                                        </li><!--end nav-item-->                                   
                                        <li class="nav-item">
                                            <a class="nav-link" href="ui-modals.html">Modals</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="ui-navs.html">Navs</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="ui-navbar.html">Navbar</a>
                                        </li><!--end nav-item--> 
                                        <li class="nav-item">
                                            <a class="nav-link" href="ui-paginations.html">Paginations</a>
                                        </li><!--end nav-item-->   
                                        <li class="nav-item">
                                            <a class="nav-link" href="ui-popover-tooltips.html">Popover & Tooltips</a>
                                        </li><!--end nav-item-->                                
                                        <li class="nav-item">
                                            <a class="nav-link" href="ui-progress.html">Progress</a>
                                        </li><!--end nav-item-->                                
                                        <li class="nav-item">
                                            <a class="nav-link" href="ui-spinners.html">Spinners</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="ui-tabs-accordions.html">Tabs & Accordions</a>
                                        </li><!--end nav-item-->                               
                                        <li class="nav-item">
                                            <a class="nav-link" href="ui-typography.html">Typography</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="ui-videos.html">Videos</a>
                                        </li><!--end nav-item--> 
                                    </ul><!--end nav-->
                                </div><!--end startbarElements-->
                            </li><!--end nav-item-->
                            <li class="nav-item">
                                <a class="nav-link" href="dark-ecommerce-index.html#sidebarAdvancedUI" data-bs-toggle="collapse" role="button"
                                    aria-expanded="false" aria-controls="sidebarAdvancedUI">
                                    <i class="iconoir-peace-hand menu-icon"></i>
                                    <span>Advanced UI</span><span class="badge rounded text-success bg-success-subtle ms-1">New</span>
                                </a>
                                <div class="collapse " id="sidebarAdvancedUI">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="advanced-animation.html">Animation</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="advanced-clipboard.html">Clip Board</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="advanced-dragula.html">Dragula</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="advanced-files.html">File Manager</a>
                                        </li><!--end nav-item--> 
                                        <li class="nav-item">
                                            <a class="nav-link" href="advanced-highlight.html">Highlight</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="advanced-rangeslider.html">Range Slider</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="advanced-ratings.html">Ratings</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="advanced-ribbons.html">Ribbons</a>
                                        </li><!--end nav-item-->                                  
                                        <li class="nav-item">
                                            <a class="nav-link" href="advanced-sweetalerts.html">Sweet Alerts</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="advanced-toasts.html">Toasts</a>
                                        </li><!--end nav-item-->
                                    </ul><!--end nav-->
                                </div><!--end startbarAdvancedUI-->
                            </li><!--end nav-item-->
                            <li class="nav-item">
                                <a class="nav-link" href="dark-ecommerce-index.html#sidebarForms" data-bs-toggle="collapse" role="button"
                                    aria-expanded="false" aria-controls="sidebarForms">
                                    <i class="iconoir-journal-page menu-icon"></i>
                                    <span>Forms</span>
                                </a>
                                <div class="collapse " id="sidebarForms">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="forms-elements.html">Basic Elements</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="forms-advanced.html">Advance Elements</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="forms-validation.html">Validation</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="forms-wizard.html">Wizard</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="forms-editors.html">Editors</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="forms-uploads.html">File Upload</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="forms-img-crop.html">Image Crop</a>
                                        </li><!--end nav-item-->
                                    </ul><!--end nav-->
                                </div><!--end startbarForms-->
                            </li><!--end nav-item-->
                            <li class="nav-item">
                                <a class="nav-link" href="dark-ecommerce-index.html#sidebarCharts" data-bs-toggle="collapse" role="button"
                                    aria-expanded="false" aria-controls="sidebarCharts">
                                    <i class="iconoir-candlestick-chart menu-icon"></i>
                                    <span>Charts</span>
                                </a>
                                <div class="collapse " id="sidebarCharts">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="charts-apex.html">Apex</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="charts-justgage.html">JustGage</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="charts-chartjs.html">Chartjs</a>
                                        </li><!--end nav-item--> 
                                        <li class="nav-item">
                                            <a class="nav-link" href="charts-toast-ui.html">Toast</a>
                                        </li><!--end nav-item--> 
                                    </ul><!--end nav-->
                                </div><!--end startbarCharts-->
                            </li><!--end nav-item-->
                            <li class="nav-item">
                                <a class="nav-link" href="dark-ecommerce-index.html#sidebarTables" data-bs-toggle="collapse" role="button"
                                    aria-expanded="false" aria-controls="sidebarTables">
                                    <i class="iconoir-table-rows menu-icon"></i>
                                    <span>Tables</span>
                                </a>
                                <div class="collapse " id="sidebarTables">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="tables-basic.html">Basic</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="tables-datatable.html">Datatables</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="tables-editable.html">Editable</a>
                                        </li><!--end nav-item--> 
                                    </ul><!--end nav-->
                                </div><!--end startbarTables-->
                            </li><!--end nav-item-->
                            <li class="nav-item">
                                <a class="nav-link" href="dark-ecommerce-index.html#sidebarIcons" data-bs-toggle="collapse" role="button"
                                    aria-expanded="false" aria-controls="sidebarIcons">
                                    <i class="iconoir-trophy menu-icon"></i>
                                    <span>Icons</span>
                                </a>
                                <div class="collapse " id="sidebarIcons">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="icons-fontawesome.html">Font Awesome</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="icons-lineawesome.html">Line Awesome</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="icons-icofont.html">Icofont</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="icons-iconoir.html">Iconoir</a>
                                        </li><!--end nav-item-->
                                    </ul><!--end nav-->
                                </div><!--end startbarIcons-->
                            </li><!--end nav-item-->
                            <li class="nav-item">
                                <a class="nav-link" href="dark-ecommerce-index.html#sidebarMaps" data-bs-toggle="collapse" role="button"
                                    aria-expanded="false" aria-controls="sidebarMaps">
                                    <i class="iconoir-navigator-alt menu-icon"></i>
                                    <span>Maps</span>
                                </a>
                                <div class="collapse " id="sidebarMaps">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="maps-google.html">Google Maps</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="maps-leaflet.html">Leaflet Maps</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="maps-vector.html">Vector Maps</a>
                                        </li><!--end nav-item--> 
                                    </ul><!--end nav-->
                                </div><!--end startbarMaps-->
                            </li><!--end nav-item-->
                            <li class="nav-item">
                                <a class="nav-link" href="dark-ecommerce-index.html#sidebarEmailTemplates" data-bs-toggle="collapse" role="button"
                                    aria-expanded="false" aria-controls="sidebarEmailTemplates">
                                    <i class="iconoir-send-mail menu-icon"></i>
                                    <span>Email Templates</span>
                                </a>
                                <div class="collapse " id="sidebarEmailTemplates">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="email-templates-basic.html">Basic Action Email</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="email-templates-alert.html">Alert Email</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="email-templates-billing.html">Billing Email</a>
                                        </li><!--end nav-item-->  
                                    </ul><!--end nav-->
                                </div><!--end startbarEmailTemplates-->
                            </li><!--end nav-item-->
                            <li class="menu-label mt-2">
                                <small class="label-border">
                                    <div class="border_left hidden-xs"></div>
                                    <div class="border_right"></div>
                                </small>
                                <span>Crafted</span>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="dark-ecommerce-index.html#sidebarPages" data-bs-toggle="collapse" role="button"
                                    aria-expanded="false" aria-controls="sidebarPages">
                                    <i class="iconoir-page-star menu-icon"></i>
                                    <span>Pages</span>
                                </a>
                                <div class="collapse " id="sidebarPages">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="pages-profile.html">Profile</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="pages-notifications.html">Notifications</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="pages-timeline.html">Timeline</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="pages-treeview.html">Treeview</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="pages-starter.html">Starter Page</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="pages-pricing.html">Pricing</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="pages-blogs.html">Blogs</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="pages-faq.html">FAQs</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="pages-gallery.html">Gallery</a>
                                        </li><!--end nav-item-->  
                                    </ul><!--end nav-->
                                </div><!--end startbarPages-->
                            </li><!--end nav-item-->
                            <li class="nav-item">
                                <a class="nav-link" href="dark-ecommerce-index.html#sidebarAuthentication" data-bs-toggle="collapse" role="button"
                                    aria-expanded="false" aria-controls="sidebarAuthentication">
                                    <i class="iconoir-fingerprint-lock-circle menu-icon"></i>
                                    <span>Authentication</span>
                                </a>
                                <div class="collapse " id="sidebarAuthentication">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="auth-login.html">Log in</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="auth-register.html">Register</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="https://mannatthemes.com/rizz/default/auth-recover-pw.html">Re-Password</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="auth-lock-screen.html">Lock Screen</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="auth-maintenance.html">Maintenance</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="auth-404.html">Error 404</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="auth-500.html">Error 500</a>
                                        </li><!--end nav-item-->
                                    </ul><!--end nav-->
                                </div><!--end startbarAuthentication-->
                            </li><!--end nav-item-->
                        </ul><!--end navbar-nav--->
                        <div class="update-msg text-center"> 
                            <div class="d-flex justify-content-center align-items-center thumb-lg update-icon-box  rounded-circle mx-auto">
                                <i class="iconoir-peace-hand h3 align-self-center mb-0 text-primary"></i>
                            </div>                   
                            <h5 class="mt-3">Mannat Themes</h5>
                            <p class="mb-3 text-muted">Rizz is a high quality web applications.</p>
                            <a href="javascript: void(0);" class="btn text-primary shadow-sm rounded-pill">Upgrade your plan</a>
                        </div>
                    </div>
                </div><!--end startbar-collapse-->
            </div><!--end startbar-menu-->    
        </div><!--end startbar-->
        <div class="startbar-overlay d-print-none"></div>
        <!-- end leftbar-tab-menu-->


        <div class="page-wrapper">

            <!-- Page Content-->
            <div class="page-content">
                <div class="container-xxl">
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-xl-4">
                            <div class="row">
                                <div class="col-md-12 col-lg-6 col-xl-12">
                                    <div class="card">
                                        <div class="card-body border-dashed-bottom pb-3">
                                            <div class="row d-flex justify-content-between">
                                                <div class="col-auto">
                                                    <div class="d-flex justify-content-center align-items-center thumb-xl border border-secondary rounded-circle">
                                                        <i class="icofont-money-bag h1 align-self-center mb-0 text-secondary"></i>
                                                    </div> 
                                                    <h5 class="mt-2 mb-0 fs-14">Total Revenue</h5>
                                                </div><!--end col-->
                                                <div class="col align-self-center">
                                                    <div id="line-1" class="apex-charts float-end"></div> 
                                                </div><!--end col-->
                                            </div><!--end row-->
                                        </div><!--end card-body-->
                                        <div class="card-body"> 
                                            <div class="row d-flex justify-content-center ">
                                                <div class="col-12 col-md-6">
                                                    <h2 class="fs-22 mt-0 mb-1 fw-bold"><?php echo number_format($total_amount_earned, 2); ?></h2>
                                                    <p class="mb-0 text-truncate text-muted"><span class="text-success"><i class="mdi mdi-trending-up"></i>8.5%</span> New Sessions Today</p>                                           
                                                </div><!--end col-->
                                                <div class="col-12 col-md-6 align-self-center text-start text-md-end">
                                                    <button type="button" class="btn btn-primary btn-sm px-2 mt-2 mt-md-0 ">View Report</button>  
                                                </div><!--end col-->
                                            </div><!--end row-->  
                                        </div><!--end card-body--> 
                                    </div><!--end card-->
                                </div><!--end col-->
                                <div class="col-md-12 col-lg-6 col-xl-12">
                                    <div class="card">
                                        <div class="card-body border-dashed-bottom pb-3">
                                            <div class="row d-flex justify-content-between">
                                                <div class="col-auto">
                                                    <div class="d-flex justify-content-center align-items-center thumb-xl border border-secondary rounded-circle">
                                                        <i class="icofont-opencart h1 align-self-center mb-0 text-secondary"></i>
                                                    </div> 
                                                    <h5 class="mt-2 mb-0 fs-14">New Order</h5>
                                                </div><!--end col-->
                                                <div class="col align-self-center ">
                                                    <div id="line-2" class="apex-charts float-end"></div> 
                                                </div><!--end col-->
                                            </div><!--end row-->
                                        </div><!--end card-body-->
                                        <div class="card-body"> 
                                            <div class="row d-flex justify-content-center ">
                                                <div class="col-12 col-md-6">
                                                    <h2 class="fs-22 mt-0 mb-1 fw-bold"><?php echo $daily_clicks; ?></h2>
                                                    <h2 class="fs-22 mt-0 mb-1 fw-bold"><?php echo $total_clicks; ?></h2>
                                                    <p class="mb-0 text-truncate text-muted"><span class="text-success"><i class="mdi mdi-trending-up"></i>8.5%</span> New Sessions Today</p>                                           
                                                </div><!--end col-->
                                                <div class="col-12 col-md-6 align-self-center text-start text-md-end">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm px-2 mt-2 mt-md-0 ">View  Report</button>  
                                                </div><!--end col-->
                                            </div><!--end row-->  
                                        </div><!--end card-body--> 
                                    </div><!--end card-->
                                </div><!--end col-->
                            </div> <!--end row--> 
                        </div> <!--end col--> 
                        <div class="col-md-12 col-lg-12 col-xl-8">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col">                      
                                            <h4 class="card-title">Monthly Avg. Income</h4>                      
                                        </div><!--end col-->
                                        <div class="col-auto"> 
                                            <div class="dropdown">
                                                <a href="dark-ecommerce-index.html#" class="btn bt btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="icofont-calendar fs-5 me-1"></i> This Year<i class="las la-angle-down ms-1"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="dark-ecommerce-index.html#">Today</a>
                                                    <a class="dropdown-item" href="dark-ecommerce-index.html#">Last Week</a>
                                                    <a class="dropdown-item" href="dark-ecommerce-index.html#">Last Month</a>
                                                    <a class="dropdown-item" href="dark-ecommerce-index.html#">This Year</a>
                                                </div>
                                            </div>               
                                        </div><!--end col-->
                                    </div>  <!--end row-->                                  
                                </div><!--end card-header-->
                                <div class="card-body pt-0">
                                    <div id="monthly_income" class="apex-charts"></div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-3"> 
                                            <div class="card shadow-none border mb-3 mb-lg-0">
                                                <div class="card-body">
                                                    <div class="row align-items-center">
                                                        <div class="col text-center">                                                                        
                                                            <span class="fs-18 fw-semibold"><?php echo number_format($total_amount_earned, 2); ?></span>      
                                                            <h6 class="text-uppercase text-muted mt-2 m-0">Today's Revenue</h6>                
                                                        </div><!--end col-->
                                                    </div> <!-- end row -->
                                                </div><!--end card-body-->
                                            </div> <!--end card-body-->                     
                                        </div><!--end col-->
                                        <div class="col-md-6 col-lg-3"> 
                                            <div class="card shadow-none border mb-3 mb-lg-0">
                                                <div class="card-body">
                                                    <div class="row align-items-center">
                                                        <div class="col text-center">                                                                        
                                                            <span class="fs-18 fw-semibold"><?php echo $daily_clicks; ?></span>      
                                                            <h6 class="text-uppercase text-muted mt-2 m-0">Conversion Rate</h6>                
                                                        </div><!--end col-->
                                                    </div> <!-- end row -->
                                                </div><!--end card-body-->
                                            </div> <!--end card-body-->                     
                                        </div><!--end col-->
                                        
                                        <div class="col-md-6 col-lg-3"> 
                                            <div class="card shadow-none border mb-3 mb-lg-0">
                                                <div class="card-body">
                                                    <div class="row align-items-center">
                                                        <div class="col text-center">                                                                        
                                                            <span class="fs-18 fw-semibold"><?php echo number_format($total_amount_earned, 4); ?></span>      
                                                            <h6 class="text-uppercase text-muted mt-2 m-0">Total Expenses</h6>                
                                                        </div><!--end col-->
                                                    </div> <!-- end row -->
                                                </div><!--end card-body-->
                                            </div> <!--end card-->                     
                                        </div><!--end col-->  
                                        <div class="col-md-6 col-lg-3"> 
                                            <div class="card shadow-none border mb-3 mb-lg-0">
                                                <div class="card-body">
                                                    <div class="row align-items-center">
                                                        <div class="col text-center">                                                                        
                                                            <span class="fs-18 fw-semibold"><?php echo $total_clicks; ?></span>      
                                                            <h6 class="text-uppercase text-muted mt-2 m-0">Avg. Value</h6>                
                                                        </div><!--end col-->
                                                    </div> <!-- end row -->
                                                </div><!--end card-body-->
                                            </div> <!--end card-body-->                     
                                        </div><!--end col-->                              
                                    </div><!--end row-->
                                </div><!--end card-body--> 
                            </div><!--end card-->                             
                        </div> <!--end col-->           
                    </div><!--end row-->
                    <div class="row justify-content-center">
                        <div class="col-md-6 col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col">                      
                                            <h4 class="card-title">Popular Products</h4>                      
                                        </div><!--end col-->
                                        <div class="col-auto"> 
                                            <div class="dropdown">
                                                <a href="dark-ecommerce-index.html#" class="btn bt btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="icofont-calendar fs-5 me-1"></i> This Year<i class="las la-angle-down ms-1"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="dark-ecommerce-index.html#">Today</a>
                                                    <a class="dropdown-item" href="dark-ecommerce-index.html#">Last Week</a>
                                                    <a class="dropdown-item" href="dark-ecommerce-index.html#">Last Month</a>
                                                    <a class="dropdown-item" href="dark-ecommerce-index.html#">This Year</a>
                                                </div>
                                            </div>               
                                        </div><!--end col-->
                                    </div>  <!--end row-->                                  
                                </div><!--end card-header-->
                                <div class="card-body pt-0">
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="border-top-0">Product</th>
                                                    <th class="border-top-0">Price</th>
                                                    <th class="border-top-0">Sell</th>
                                                    <th class="border-top-0">Status</th>
                                                    <th class="border-top-0">Action</th>
                                                </tr><!--end tr-->
                                            </thead>
                                            <tbody>
                                                <tr>                                                        
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="assets/images/products/01.png" height="40" class="me-3 align-self-center rounded" alt="...">
                                                            <div class="flex-grow-1 text-truncate"> 
                                                                <h6 class="m-0">History Book</h6>
                                                                <a href="dark-ecommerce-index.html#" class="fs-12 text-primary">ID: A3652</a>                                                                                           
                                                            </div><!--end media body-->
                                                        </div>
                                                    </td>
                                                    <td>$50 <del class="text-muted fs-10">$70</del></td>                                   
                                                    <td>450 <small class="text-muted">(550)</small></td>
                                                    <td><span class="badge bg-primary-subtle text-primary px-2">Stock</span></td>
                                                    <td>                                                       
                                                        <a href="dark-ecommerce-index.html#"><i class="las la-pen text-secondary fs-18"></i></a>
                                                        <a href="dark-ecommerce-index.html#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                                    </td>
                                                </tr><!--end tr-->     
                                                <tr>                                                        
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="assets/images/products/02.png" height="40" class="me-3 align-self-center rounded" alt="...">
                                                            <div class="flex-grow-1 text-truncate"> 
                                                                <h6 class="m-0">Colorful Pots</h6>
                                                                <a href="dark-ecommerce-index.html#" class="fs-12 text-primary">ID: A5002</a>                                                                                          
                                                            </div><!--end media body-->
                                                        </div>
                                                    </td>
                                                    <td>$99 <del class="text-muted fs-10">$150</del></td>                                   
                                                    <td>750 <small class="text-muted">(00)</small></td>
                                                    <td><span class="badge bg-danger-subtle text-danger px-2">Out of Stock</span></td>
                                                    <td>                                                       
                                                        <a href="dark-ecommerce-index.html#"><i class="las la-pen text-secondary fs-18"></i></a>
                                                        <a href="dark-ecommerce-index.html#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                                    </td>
                                                </tr><!--end tr-->    
                                                <tr>                                                        
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="assets/images/products/04.png" height="40" class="me-3 align-self-center rounded" alt="...">
                                                            <div class="flex-grow-1 text-truncate"> 
                                                                <h6 class="m-0">Pearl Bracelet</h6>
                                                                <a href="dark-ecommerce-index.html#" class="fs-12 text-primary">ID: A6598</a>                                                                                           
                                                            </div><!--end media body-->
                                                        </div>
                                                    </td>
                                                    <td>$199 <del class="text-muted fs-10">$250</del></td>                                   
                                                    <td>280 <small class="text-muted">(220)</small></td>
                                                    <td><span class="badge bg-primary-subtle text-primary px-2">Stock</span></td>
                                                    <td>                                                       
                                                        <a href="dark-ecommerce-index.html#"><i class="las la-pen text-secondary fs-18"></i></a>
                                                        <a href="dark-ecommerce-index.html#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                                    </td>
                                                </tr><!--end tr-->    
                                                <tr>                                                        
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="assets/images/products/06.png" height="40" class="me-3 align-self-center rounded" alt="...">
                                                            <div class="flex-grow-1 text-truncate"> 
                                                                <h6 class="m-0">Dancing Man</h6>
                                                                <a href="dark-ecommerce-index.html#" class="fs-12 text-primary">ID: A9547</a>                                                                                            
                                                            </div><!--end media body-->
                                                        </div>
                                                    </td>
                                                    <td>$40 <del class="text-muted fs-10">$49</del></td>                                   
                                                    <td>500 <small class="text-muted">(1000)</small></td>
                                                    <td><span class="badge bg-danger-subtle text-danger px-2">Out of Stock</span></td>
                                                    <td>                                                       
                                                        <a href="dark-ecommerce-index.html#"><i class="las la-pen text-secondary fs-18"></i></a>
                                                        <a href="dark-ecommerce-index.html#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                                    </td>
                                                </tr><!--end tr-->    
                                                <tr>                                                        
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="assets/images/products/05.png" height="40" class="me-3 align-self-center rounded" alt="...">
                                                            <div class="flex-grow-1 text-truncate"> 
                                                                <h6 class="m-0">Fire Lamp</h6>
                                                                <a href="dark-ecommerce-index.html#" class="fs-12 text-primary">ID: A2047</a>                                                                                            
                                                            </div><!--end media body-->
                                                        </div>
                                                    </td>
                                                    <td>$80 <del class="text-muted fs-10">$59</del></td>                                   
                                                    <td>800 <small class="text-muted">(2000)</small></td>
                                                    <td><span class="badge bg-danger-subtle text-danger px-2">Out of Stock</span></td>
                                                    <td>                                                       
                                                        <a href="dark-ecommerce-index.html#"><i class="las la-pen text-secondary fs-18"></i></a>
                                                        <a href="dark-ecommerce-index.html#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                                    </td>
                                                </tr><!--end tr-->                           
                                            </tbody>
                                        </table> <!--end table-->                                               
                                    </div><!--end /div-->
                                </div><!--end card-body--> 
                            </div><!--end card--> 
                        </div> <!--end col-->
                        
                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col">                      
                                            <h4 class="card-title">Customers</h4>                      
                                        </div><!--end col-->
                                        <div class="col-auto">                                    
                                            <div class="img-group d-flex">
                                                <a class="user-avatar position-relative d-inline-block" href="dark-ecommerce-index.html#">
                                                    <img src="assets/images/users/avatar-1.jpg" alt="avatar" class="thumb-md shadow-sm rounded-circle">
                                                </a>
                                                <a class="user-avatar position-relative d-inline-block ms-n2" href="dark-ecommerce-index.html#">
                                                    <img src="assets/images/users/avatar-2.jpg" alt="avatar" class="thumb-md shadow-sm rounded-circle">
                                                </a>
                                                <a class="user-avatar position-relative d-inline-block ms-n2" href="dark-ecommerce-index.html#">
                                                    <img src="assets/images/users/avatar-4.jpg" alt="avatar" class="thumb-md shadow-sm rounded-circle">
                                                </a>
                                                <a class="user-avatar position-relative d-inline-block ms-n2" href="dark-ecommerce-index.html#">
                                                    <img src="assets/images/users/avatar-3.jpg" alt="avatar" class="thumb-md shadow-sm rounded-circle">
                                                </a>
                                                <a href="dark-ecommerce-index.html" class="user-avatar position-relative d-inline-block ms-1">
                                                    <span class="thumb-md shadow-sm justify-content-center d-flex align-items-center bg-info-subtle rounded-circle fw-semibold fs-6">+6</span>
                                                </a>                    
                                            </div>                 
                                        </div><!--end col-->
                                    </div>  <!--end row-->                                  
                                </div>
                                <div class="card-body pt-0">
                                    <div id="customers" class="apex-charts"></div> 
                                    <div class="bg-light py-3 px-2 mb-0 mt-3 text-center rounded">                                                                                       
                                        <h6 class="mb-0"><i class="icofont-calendar fs-5 me-1"></i>  01 January 2024 to 31 December 2024</h6>                                                                       
                                    </div> 
                                    <p class="text-secondary text-truncate mb-0 rounded mt-2 text-center">To review the new order by Jems. <a href="dark-ecommerce-index.html#" class="text-primary text-decoration-underline">detail</a> </p>   
                                </div><!--end card-body--> 
                            </div><!--end card--> 
                        </div> <!--end col-->                                
                    </div><!--end row-->
                    <div class="row justify-content-center">
                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col">                      
                                            <h4 class="card-title">Top Selling by Country</h4>                      
                                        </div><!--end col-->
                                        <div class="col-auto"> 
                                            <div class="dropdown">
                                                <a href="dark-ecommerce-index.html#" class="btn bt btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="icofont-calendar fs-5 me-1"></i> This Month<i class="las la-angle-down ms-1"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="dark-ecommerce-index.html#">Today</a>
                                                    <a class="dropdown-item" href="dark-ecommerce-index.html#">Last Week</a>
                                                    <a class="dropdown-item" href="dark-ecommerce-index.html#">Last Month</a>
                                                    <a class="dropdown-item" href="dark-ecommerce-index.html#">This Year</a>
                                                </div>
                                            </div>               
                                        </div><!--end col-->
                                    </div>  <!--end row-->                                  
                                </div><!--end card-header-->
                                <div class="card-body pt-0">
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <tbody>
                                                <tr class="">                                                        
                                                    <td class="px-0">
                                                        <div class="d-flex align-items-center">
                                                            <img src="assets/images/flags/us_flag.jpg" class="me-2 align-self-center thumb-md rounded-circle" alt="...">
                                                            <div class="flex-grow-1 text-truncate"> 
                                                                <h6 class="m-0 text-truncate">USA</h6>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="progress bg-primary-subtle w-100" style="height:5px;" role="progressbar" aria-label="Success example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                                                        <div class="progress-bar bg-primary" style="width: 85%"></div>
                                                                    </div> 
                                                                    <small class="flex-shrink-1 ms-1">85%</small>
                                                                </div>                                                                                    
                                                            </div><!--end media body-->
                                                        </div><!--end media-->
                                                    </td>
                                                    <td  class="px-0 text-end"><span class="text-body ps-2 align-self-center text-end">$5860.00</span></td>  
                                                </tr><!--end tr-->  
                                                <tr class="">                                                        
                                                    <td class="px-0">
                                                        <div class="d-flex align-items-center">
                                                            <img src="assets/images/flags/spain_flag.jpg" class="me-2 align-self-center thumb-md rounded-circle" alt="...">
                                                            <div class="flex-grow-1 text-truncate"> 
                                                                <h6 class="m-0 text-truncate">Spain</h6>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="progress bg-primary-subtle w-100" style="height:5px;" role="progressbar" aria-label="Success example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                                                        <div class="progress-bar bg-primary" style="width: 78%"></div>
                                                                    </div> 
                                                                    <small class="flex-shrink-1 ms-1">78%</small>
                                                                </div>                                                                                    
                                                            </div><!--end media body-->
                                                        </div><!--end media-->
                                                    </td>
                                                    <td  class="px-0 text-end"><span class="text-body ps-2 align-self-center text-end">$5422.00</span></td>  
                                                </tr><!--end tr--> 
                                                <tr class="">                                                        
                                                    <td class="px-0">
                                                        <div class="d-flex align-items-center">
                                                            <img src="assets/images/flags/french_flag.jpg" class="me-2 align-self-center thumb-md rounded-circle" alt="...">
                                                            <div class="flex-grow-1 text-truncate"> 
                                                                <h6 class="m-0 text-truncate">French</h6>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="progress bg-primary-subtle w-100" style="height:5px;" role="progressbar" aria-label="Success example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                                                        <div class="progress-bar bg-primary" style="width: 71%"></div>
                                                                    </div> 
                                                                    <small class="flex-shrink-1 ms-1">71%</small>
                                                                </div>                                                                                    
                                                            </div><!--end media body-->
                                                        </div><!--end media-->
                                                    </td>
                                                    <td  class="px-0 text-end"><span class="text-body ps-2 align-self-center text-end">$4587.00</span></td>  
                                                </tr><!--end tr--> 
                                                <tr class="">                                                        
                                                    <td class="px-0">
                                                        <div class="d-flex align-items-center">
                                                            <img src="assets/images/flags/germany_flag.jpg" class="me-2 align-self-center thumb-md rounded-circle" alt="...">
                                                            <div class="flex-grow-1 text-truncate"> 
                                                                <h6 class="m-0 text-truncate">Germany</h6>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="progress bg-primary-subtle w-100" style="height:5px;" role="progressbar" aria-label="Success example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                                                        <div class="progress-bar bg-primary" style="width: 65%"></div>
                                                                    </div> 
                                                                    <small class="flex-shrink-1 ms-1">65%</small>
                                                                </div>                                                                                    
                                                            </div><!--end media body-->
                                                        </div><!--end media-->
                                                    </td>
                                                    <td  class="px-0 text-end"><span class="text-body ps-2 align-self-center text-end">$3655.00</span></td>  
                                                </tr><!--end tr--> 
                                                <tr class="">                                                        
                                                    <td class="px-0">
                                                        <div class="d-flex align-items-center">
                                                            <img src="assets/images/flags/baha_flag.jpg" class="me-2 align-self-center thumb-md rounded-circle" alt="...">
                                                            <div class="flex-grow-1 text-truncate"> 
                                                                <h6 class="m-0 text-truncate">Bahamas</h6>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="progress bg-primary-subtle w-100" style="height:5px;" role="progressbar" aria-label="Success example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                                                        <div class="progress-bar bg-primary" style="width: 48%"></div>
                                                                    </div> 
                                                                    <small class="flex-shrink-1 ms-1">48%</small>
                                                                </div>                                                                                    
                                                            </div><!--end media body-->
                                                        </div><!--end media-->
                                                    </td>
                                                    <td  class="px-0 text-end"><span class="text-body ps-2 align-self-center text-end">$3325.00</span></td>  
                                                </tr><!--end tr-->       
                                            </tbody>
                                        </table> <!--end table-->                                               
                                    </div><!--end /div-->                           
                                </div><!--end card-body--> 
                            </div><!--end card--> 
                        </div> <!--end col--> 
                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col">                      
                                            <h4 class="card-title">Categories Data</h4>                      
                                        </div><!--end col-->
                                        <div class="col-auto">                      
                                            <div class="text-center">                                                                        
                                                <h6 class="text-uppercase text-muted mt-1 m-0"><span class="fs-16 fw-semibold">24+</span> Categories</h6>                
                                            </div>
                                        </div><!--end col-->
                                    </div>  <!--end row-->                                  
                                </div>
                                <div class="card-body pt-0">                                    
                                    <div id="categories" class="apex-charts mt-n2"></div>
                                    <div class="text-center">
                                        <button type="button" class="btn btn-primary mx-auto">More Detail <i class="fa-solid fa-arrow-right-long"></i> </button>
                                    </div>
                                </div><!--end card-body--> 
                            </div><!--end card--> 
                        </div> <!--end col-->  
                        
                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col">                      
                                            <h4 class="card-title">Recents Order</h4>                      
                                        </div><!--end col-->
                                        <div class="col-auto"> 
                                            <div class="dropdown">
                                                <a href="dark-ecommerce-index.html#" class="btn bt btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="icofont-calendar fs-5 me-1"></i> This Month<i class="las la-angle-down ms-1"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="dark-ecommerce-index.html#">Today</a>
                                                    <a class="dropdown-item" href="dark-ecommerce-index.html#">Last Week</a>
                                                    <a class="dropdown-item" href="dark-ecommerce-index.html#">Last Month</a>
                                                    <a class="dropdown-item" href="dark-ecommerce-index.html#">This Year</a>
                                                </div>
                                            </div>               
                                        </div><!--end col-->
                                    </div>  <!--end row-->                                  
                                </div><!--end card-header-->
                                <div class="card-body pt-0">
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <tbody>
                                                <tr class="">                                                        
                                                    <td class="px-0">
                                                        <div class="d-flex align-items-center">
                                                            <img src="assets/images/users/avatar-1.jpg" height="36" class="me-2 align-self-center rounded" alt="...">
                                                            <div class="flex-grow-1 text-truncate"> 
                                                                <h6 class="m-0 text-truncate">Scott Holland</h6>
                                                                <a href="dark-ecommerce-index.html#" class="font-12 text-muted text-decoration-underline">#3652</a>                                                                                           
                                                            </div><!--end media body-->
                                                        </div><!--end media-->
                                                    </td>
                                                    <td  class="px-0 text-end"><span class="text-primary ps-2 align-self-center text-end">$3325.00</span></td>  
                                                </tr><!--end tr-->  
                                                <tr class="">                                                        
                                                    <td class="px-0">
                                                        <div class="d-flex align-items-center">
                                                            <img src="assets/images/users/avatar-2.jpg" height="36" class="me-2 align-self-center rounded" alt="...">
                                                            <div class="flex-grow-1 text-truncate"> 
                                                                <h6 class="m-0 text-truncate">Karen Savage</h6>
                                                                <a href="dark-ecommerce-index.html#" class="font-12 text-muted text-decoration-underline">#4789</a>                                                                                           
                                                            </div><!--end media body-->
                                                        </div><!--end media-->
                                                    </td>
                                                    <td  class="px-0 text-end"><span class="text-primary ps-2 align-self-center text-end">$2548.00</span></td>  
                                                </tr><!--end tr-->  
                                                <tr class="">                                                        
                                                    <td class="px-0">
                                                        <div class="d-flex align-items-center">
                                                            <img src="assets/images/users/avatar-3.jpg" height="36" class="me-2 align-self-center rounded" alt="...">
                                                            <div class="flex-grow-1 text-truncate"> 
                                                                <h6 class="m-0 text-truncate">Steven Sharp </h6>
                                                                <a href="dark-ecommerce-index.html#" class="font-12 text-muted text-decoration-underline">#4521</a>                                                                                           
                                                            </div><!--end media body-->
                                                        </div><!--end media-->
                                                    </td>
                                                    <td  class="px-0 text-end"><span class="text-primary ps-2 align-self-center text-end">$2985.00</span></td>  
                                                </tr><!--end tr-->  
                                                <tr class="">                                                        
                                                    <td class="px-0">
                                                        <div class="d-flex align-items-center">
                                                            <img src="assets/images/users/avatar-4.jpg" height="36" class="me-2 align-self-center rounded" alt="...">
                                                            <div class="flex-grow-1 text-truncate"> 
                                                                <h6 class="m-0 text-truncate">Teresa Himes  </h6>
                                                                <a href="dark-ecommerce-index.html#" class="font-12 text-muted text-decoration-underline">#3269</a>                                                                                           
                                                            </div><!--end media body-->
                                                        </div><!--end media-->
                                                    </td>
                                                    <td  class="px-0 text-end"><span class="text-primary ps-2 align-self-center text-end">$1845.00</span></td>  
                                                </tr><!--end tr-->  
                                                <tr>                                                        
                                                    <td class="px-0">
                                                        <div class="d-flex align-items-center">
                                                            <img src="assets/images/users/avatar-5.jpg" height="36" class="me-2 align-self-center rounded" alt="...">
                                                            <div class="flex-grow-1 text-truncate"> 
                                                                <h6 class="m-0 text-truncate">Ralph Denton</h6>
                                                                <a href="dark-ecommerce-index.html#" class="font-12 text-muted text-decoration-underline">#4521</a>                                                                                           
                                                            </div><!--end media body-->
                                                        </div><!--end media-->
                                                    </td>
                                                    <td  class="px-0 text-end"><span class="text-primary ps-2 align-self-center text-end">$1422.00</span></td>  
                                                </tr><!--end tr-->        
                                            </tbody>
                                        </table> <!--end table-->                                               
                                    </div><!--end /div-->                           
                                </div><!--end card-body--> 
                            </div><!--end card--> 
                        </div> <!--end col-->                             
                    </div><!--end row-->
                    
                </div><!-- container -->
                
                <!--Start Rightbar-->
                <!--Start Rightbar/offcanvas-->
                <div class="offcanvas offcanvas-end" tabindex="-1" id="Appearance" aria-labelledby="AppearanceLabel">
                    <div class="offcanvas-header border-bottom justify-content-between">
                      <h5 class="m-0 font-14" id="AppearanceLabel">Appearance</h5>
                      <button type="button" class="btn-close text-reset p-0 m-0 align-self-center" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">  
                        <h6>Account Settings</h6>
                        <div class="p-2 text-start mt-3">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="settings-switch1">
                                <label class="form-check-label" for="settings-switch1">Auto updates</label>
                            </div><!--end form-switch-->
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="settings-switch2" checked>
                                <label class="form-check-label" for="settings-switch2">Location Permission</label>
                            </div><!--end form-switch-->
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="settings-switch3">
                                <label class="form-check-label" for="settings-switch3">Show offline Contacts</label>
                            </div><!--end form-switch-->
                        </div><!--end /div-->
                        <h6>General Settings</h6>
                        <div class="p-2 text-start mt-3">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="settings-switch4">
                                <label class="form-check-label" for="settings-switch4">Show me Online</label>
                            </div><!--end form-switch-->
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="settings-switch5" checked>
                                <label class="form-check-label" for="settings-switch5">Status visible to all</label>
                            </div><!--end form-switch-->
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="settings-switch6">
                                <label class="form-check-label" for="settings-switch6">Notifications Popup</label>
                            </div><!--end form-switch-->
                        </div><!--end /div-->               
                    </div><!--end offcanvas-body-->
                </div>
                <!--end Rightbar/offcanvas-->
                <!--end Rightbar-->
                <!--Start Footer-->
                
                <footer class="footer text-center text-sm-start d-print-none">
                    <div class="container-xxl">
                        <div class="row">
                            <div class="col-12">
                                <div class="card mb-0 rounded-bottom-0">
                                    <div class="card-body">
                                        <p class="text-muted mb-0">
                                            ©
                                            <script> document.write(new Date().getFullYear()) </script>
                                            Rizz
                                            <span
                                                class="text-muted d-none d-sm-inline-block float-end">
                                                Crafted with
                                                <i class="iconoir-heart text-danger"></i>
                                                by Mannatthemes</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
                
                <!--end footer-->
            </div>
            <!-- end page content -->
        </div>
        <!-- end page-wrapper -->

        <!-- Javascript  -->  
        <!-- vendor js -->
        
        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/libs/simplebar/simplebar.min.js"></script>
        <script src="assets/libs/apexcharts/apexcharts.min.js"></script>
        <script src="assets/js/pages/ecommerce-index.init.js"></script>
        <script src="assets/js/app.js"></script>
    </body>
    <!--end body-->
</html>