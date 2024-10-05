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
                <title>Quizzy | Admin Dashboard </title>
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
                        <h3 class="mb-0 fw-bold text-truncate">
    <?php echo "$greeting, $username!"; ?>
</h3>
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
                                <a class="dropdown-item" href="dark-ecommerce-index.html#"><img src="assets/images/flags/spain_flag.jpg" alt="" height="15" class="me-2">Swahili</a>
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
                                                <small class="float-end text-muted ps-2">Hey!</small>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 bg-primary-subtle text-primary thumb-md rounded-circle">
                                                        <i class="iconoir-wolf fs-4"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2 text-truncate">
                                                        <h6 class="my-0 fw-normal text-dark fs-13">Your Unpaid funds are</h6>
                                                        <small class="text-muted mb-0"><?php echo number_format($balance, 2); ?></small>
                                                    </div><!--end media-body-->
                                                </div><!--end media-->
                                            </a><!--end-item-->
                                            <!-- item-->
                                            <a href="dark-ecommerce-index.html#" class="dropdown-item py-3">
                                                <small class="float-end text-muted ps-2">Hello</small>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 bg-primary-subtle text-primary thumb-md rounded-circle">
                                                        <i class="iconoir-apple-swift fs-4"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2 text-truncate">
                                                        <h6 class="my-0 fw-normal text-dark fs-13">You currently have</h6>
                                                        <small class="text-muted mb-0"><?php echo number_format($balance, 2); ?></small>
                                                    </div><!--end media-body-->
                                                </div><!--end media-->
                                            </a><!--end-item-->
                                            <!-- item-->
                                            <a href="dark-ecommerce-index.html#" class="dropdown-item py-3">
                                                <small class="float-end text-muted ps-2">Yeah, todays clicks are</small>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 bg-primary-subtle text-primary thumb-md rounded-circle">                                                    
                                                        <i class="iconoir-birthday-cake fs-4"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2 text-truncate">
                                                        <h6 class="my-0 fw-normal text-dark fs-13"><?php echo htmlspecialchars($username); ?>, continue watching ads</h6>
                                                        <small class="text-muted mb-0">If watched already, persist</small>
                                                    </div><!--end media-body-->
                                                </div><!--end media-->
                                            </a><!--end-item-->
                                            <!-- item-->
                                            <a href="dark-ecommerce-index.html#" class="dropdown-item py-3">
                                                <small class="float-end text-muted ps-2">You are doing it</small>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 bg-primary-subtle text-primary thumb-md rounded-circle">
                                                        <i class="iconoir-drone fs-4"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2 text-truncate">
                                                        <h6 class="my-0 fw-normal text-dark fs-13">Little by little, step by step</h6>
                                                        <small class="text-muted mb-0">Its guaranteed withdrawal upon hitting target</small>
                                                    </div><!--end media-body-->
                                                </div><!--end media-->
                                            </a><!--end-item-->
                                            <!-- item-->
                                            <a href="dark-ecommerce-index.html#" class="dropdown-item py-3">
                                                <small class="float-end text-muted ps-2">If you encounter any problem,feel free to reach us</small>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 bg-primary-subtle text-primary thumb-md rounded-circle">
                                                        <i class="iconoir-user fs-4"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2 text-truncate">
                                                        <h6 class="my-0 fw-normal text-dark fs-13">Payment will be in full</h6>
                                                        <small class="text-muted mb-0">Pesist, you can from <?php echo number_format($balance, 2); ?> and elevate to target</small>
                                                    </div><!--end media-body-->
                                                </div><!--end media-->
                                            </a><!--end-item-->
                                        </div>
                                        <div class="tab-pane fade" id="Projects" role="tabpanel" aria-labelledby="projects-tab" tabindex="0">
                                            <!-- item-->
                                            <a href="dark-ecommerce-index.html#" class="dropdown-item py-3">
                                                <small class="float-end text-muted ps-2">Amount guaranteed within 12 hours</small>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 bg-primary-subtle text-primary thumb-md rounded-circle">                                                    
                                                        <i class="iconoir-birthday-cake fs-4"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2 text-truncate">
                                                        <h6 class="my-0 fw-normal text-dark fs-13">Please ensure you comply to policy and rules</h6>
                                                        <small class="text-muted mb-0">Beleive and achieve. </small>
                                                    </div><!--end media-body-->
                                                </div><!--end media-->
                                            </a><!--end-item-->
                                            <!-- item-->
                                            <a href="dark-ecommerce-index.html#" class="dropdown-item py-3">
                                                <small class="float-end text-muted ps-2">Our team is ready to assist 24/7</small>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 bg-primary-subtle text-primary thumb-md rounded-circle">
                                                        <i class="iconoir-drone fs-4"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2 text-truncate">
                                                        <h6 class="my-0 fw-normal text-dark fs-13">Your order will be placed upon withdrawal</h6>
                                                        <small class="text-muted mb-0">Issues with payment be addressed after 24 hours of unpayed dues.</small>
                                                    </div><!--end media-body-->
                                                </div><!--end media-->
                                            </a><!--end-item-->
                                            <!-- item-->
                                            <a href="dark-ecommerce-index.html#" class="dropdown-item py-3">
                                                <small class="float-end text-muted ps-2">Thankyou for trusting Quizzy</small>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 bg-primary-subtle text-primary thumb-md rounded-circle">
                                                        <i class="iconoir-user fs-4"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2 text-truncate">
                                                        <h6 class="my-0 fw-normal text-dark fs-13">Thanks from Quizzy</h6>
                                                        <small class="text-muted mb-0">We value your loyalty</small>
                                                    </div><!--end media-body-->
                                                </div><!--end media-->
                                            </a><!--end-item-->
                                        </div>
                                        <div class="tab-pane fade" id="Teams" role="tabpanel" aria-labelledby="teams-tab" tabindex="0">
                                            <!-- item-->
                                            <a href="dark-ecommerce-index.html#" class="dropdown-item py-3">
                                                <small class="float-end text-muted ps-2">Keep Quizzing, payment is guaranteed</small>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 bg-primary-subtle text-primary thumb-md rounded-circle">
                                                        <i class="iconoir-drone fs-4"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2 text-truncate">
                                                        <h6 class="my-0 fw-normal text-dark fs-13">Technical support 24/7</h6>
                                                        <small class="text-muted mb-0">We are more than glad to assist you with any problems</small>
                                                    </div><!--end media-body-->
                                                </div><!--end media-->
                                            </a><!--end-item-->
                                            <!-- item-->
                                            <a href="dark-ecommerce-index.html#" class="dropdown-item py-3">
                                                <small class="float-end text-muted ps-2">Hotline info</small>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 bg-primary-subtle text-primary thumb-md rounded-circle">
                                                        <i class="iconoir-user fs-4"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2 text-truncate">
                                                        <h6 class="my-0 fw-normal text-dark fs-13">Call 0794335725</h6>
                                                        <small class="text-muted mb-0">Contact: whatsapp 0738870421 {Daytime}</small>
                                                    </div><!--end media-body-->
                                                </div><!--end media-->
                                            </a><!--end-item-->
                                        </div>
                                    </div>
                            
                                </div>
                                <!-- All-->
                                <a href="#" class="dropdown-item text-center text-dark fs-13 py-2">
                                    View All <i class="fi-arrow-right"></i>
                                </a>
                            </div>
                        </li>
    
                        <li class="dropdown topbar-item">
                            <a class="nav-link dropdown-toggle arrow-none nav-icon" data-bs-toggle="dropdown" href="dark-ecommerce-index.html#" role="button"
                                aria-haspopup="false" aria-expanded="false">
                                

                                <img src="assets/images/users/user2.png"  class="thumb-lg rounded-circle">
                            </a>
                            <div class="dropdown-menu dropdown-menu-end py-0">
                                <div class="d-flex align-items-center dropdown-item py-2 bg-secondary-subtle">
                                    <div class="flex-shrink-0">
                                        <img src="assets/images/users/user2.png"  class="thumb-md rounded-circle">
                                    </div>
                                    <div class="flex-grow-1 ms-2 text-truncate align-self-center">
                                        <h6 class="my-0 fw-medium text-dark fs-13"><?php echo htmlspecialchars($username); ?></h6>
                                        <small class="text-muted mb-0">Kshs <?php echo number_format($balance, 2); ?></small>
                                    </div><!--end media-body-->
                                </div>
                                <div class="dropdown-divider mt-0"></div>
                                <small class="text-muted px-2 pb-1 d-block">Account</small>
                                <a class="dropdown-item" href="pages-profile.html"><i class="las la-user fs-18 me-1 align-text-bottom"></i> Profile</a>
                                <a class="dropdown-item" href="pages-faq.html"><i class="las la-wallet fs-18 me-1 align-text-bottom"></i> Earning</a>
                                <small class="text-muted px-2 py-1 d-block">Settings</small>                        
                                <a class="dropdown-item" href="admin_login.php"><i class="las la-cog fs-18 me-1 align-text-bottom"></i>Admin</a>
                                <a class="dropdown-item" href="terms-and-conditions.php"><i class="las la-lock fs-18 me-1 align-text-bottom"></i> Withdraw</a>
                                <a class="dropdown-item" href="image_upload.php"><i class="las la-question-circle fs-18 me-1 align-text-bottom"></i> Change Photo</a>                       
                                <div class="dropdown-divider mb-0"></div>
                                <a class="dropdown-item text-danger" href="logout.php"><i class="las la-power-off fs-18 me-1 align-text-bottom"></i> Logout</a>
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
                                            <a class="nav-link" href="index.html">Admin Dashboard</a>
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="ecommerce-index.html">User Dashboard</a>
                                        </li><!--end nav-item-->
                                    </ul><!--end nav-->
                                </div><!--end startbarDashboards-->
                            </li><!--end nav-item-->
                            <li class="nav-item">
                                <a class="nav-link" href="dark-ecommerce-index.html#sidebarApplications" data-bs-toggle="collapse" role="button"
                                    aria-expanded="false" aria-controls="sidebarApplications">
                                    <i class="iconoir-view-grid menu-icon"></i>
                                    <span>About us</span>
                                </a>
                                <div class="collapse " id="sidebarApplications">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="customersupport.php"  role="button"
                                                aria-expanded="false" aria-controls="sidebarAnalytics">                                        
                                                <span>Team</span>
                                            </a>
                                          
                                        </li><!--end nav-item-->                                
                                        <li class="nav-item">
                                            <a class="nav-link" href="dark-ecommerce-index.html#sidebarProjects"  role="button"
                                                aria-expanded="false" aria-controls="sidebarProjects">                                        
                                                <span>Customer Support</span>
                                            </a>
                                           
                                        </li><!--end nav-item-->
                                        <li class="nav-item">
                                            <a class="nav-link" href="dark-ecommerce-index.html#sidebarEcommerce" data-bs-toggle="collapse"  role="button"
                                                aria-expanded="false" aria-controls="sidebarEcommerce">                                        
                                                <span>Hotline</span>
                                            </a>
                                           
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
                                <a class="nav-link" href="dark-ecommerce-index.html#sidebarAdvancedUI"  role="button"
                                    aria-expanded="false" aria-controls="sidebarAdvancedUI">
                                    <i class="iconoir-peace-hand menu-icon"></i>
                                    <span>Advanced UI</span><span class="badge rounded text-success bg-success-subtle ms-1">New</span>
                                </a>
                                
                            </li><!--end nav-item-->
                            <li class="nav-item">
                                <a class="nav-link" href="dark-ecommerce-index.html#sidebarForms"  role="button"
                                    aria-expanded="false" aria-controls="sidebarForms">
                                    <i class="iconoir-journal-page menu-icon"></i>
                                    <span>Forms</span>
                                </a>
                               
                            </li><!--end nav-item-->
                            <li class="nav-item">
                                <a class="nav-link" href="dark-ecommerce-index.html#sidebarCharts"  role="button"
                                    aria-expanded="false" aria-controls="sidebarCharts">
                                    <i class="iconoir-candlestick-chart menu-icon"></i>
                                    <span>Charts</span>
                                </a>
                               
                            </li><!--end nav-item-->
                            <li class="nav-item">
                                <a class="nav-link" href="dark-ecommerce-index.html#sidebarTables"  role="button"
                                    aria-expanded="false" aria-controls="sidebarTables">
                                    <i class="iconoir-table-rows menu-icon"></i>
                                    <span>Tables</span>
                                </a>
                                
                            </li><!--end nav-item-->
                            <li class="nav-item">
                                <a class="nav-link" href="dark-ecommerce-index.html#sidebarIcons"  role="button"
                                    aria-expanded="false" aria-controls="sidebarIcons">
                                    <i class="iconoir-trophy menu-icon"></i>
                                    <span>Icons</span>
                                </a>
                                
                            </li><!--end nav-item-->
                            <li class="nav-item">
                                <a class="nav-link" href="dark-ecommerce-index.html#sidebarMaps"  role="button"
                                    aria-expanded="false" aria-controls="sidebarMaps">
                                    <i class="iconoir-navigator-alt menu-icon"></i>
                                    <span>Maps</span>
                                </a>
                                
                            </li><!--end nav-item-->
                            <li class="nav-item">
                                <a class="nav-link" href="dark-ecommerce-index.html#sidebarEmailTemplates"  role="button"
                                    aria-expanded="false" aria-controls="sidebarEmailTemplates">
                                    <i class="iconoir-send-mail menu-icon"></i>
                                    <span>Email Templates</span>
                                </a>
                                
                            </li><!--end nav-item-->
                            <li class="menu-label mt-2">
                                <small class="label-border">
                                    <div class="border_left hidden-xs"></div>
                                    <div class="border_right"></div>
                                </small>
                                <span>Crafted</span>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="dark-ecommerce-index.html#sidebarPages"  role="button"
                                    aria-expanded="false" aria-controls="sidebarPages">
                                    <i class="iconoir-page-star menu-icon"></i>
                                    <span>Pages</span>
                                </a>
                                
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
                            <h5 class="mt-3">Peter_@Webtailors</h5>
                            <p class="mb-3 text-muted">Gainly is a high payout watch ad to earn platform</p>
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
                                                    <h5 class="mt-2 mb-0 fs-14">Account Balance</h5>
                                                </div><!--end col-->
                                                <div class="col align-self-center">
                                                    <div id="line-1" class="apex-charts float-end"></div> 
                                                </div><!--end col-->
                                            </div><!--end row-->
                                        </div><!--end card-body-->
                                        <div class="card-body"> 
                                            <div class="row d-flex justify-content-center ">
                                                <div class="col-12 col-md-6">
                                                    <h2 class="fs-22 mt-0 mb-1 fw-bold"><?php echo number_format($balance, 2); ?></h2>
                                                    <p class="mb-0 text-truncate text-muted"><span class="text-success"><i class="mdi mdi-trending-up"></i>10 </span>Daily maximum clicks</span></p>                                           
                                                </div><!--end col-->
                                                <div class="col-12 col-md-6 align-self-center text-start text-md-end">
                                                    <a href="stake.php"><button type="button" class="btn btn-primary btn-sm px-2 mt-2 mt-md-0 ">Go to Quiz</button></a> 
                                                    <br></br>
                                                    <a href="claim.php"><button type="button" class="btn btn-primary btn-sm px-2 mt-2 mt-md-0">Claim Deal</button></a>
 
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
                                                    <h5 class="mt-2 mb-0 fs-14">Sessions</h5>
                                                </div><!--end col-->
                                                <div class="col align-self-center ">
                                                    <div id="line-2" class="apex-charts float-end"></div> 
                                                </div><!--end col-->
                                            </div><!--end row-->
                                        </div><!--end card-body-->
                                        <div class="card-body"> 
                                            <div class="row d-flex justify-content-center ">
                                                <div class="col-12 col-md-6">
                                                    

                                                    <p class="mb-0 text-truncate text-muted"><span class="text-success"><i class="mdi mdi-trending-up"></i>Earn</span> <?php echo 'No funds found for this user.'?>
                                                    <p class="mb-0 text-truncate text-muted"><span class="text-success"><i class="mdi mdi-trending-up"></i>30</span> Total Sessions </p>                                           
                                         
                                                </div><!--end col-->
                                                <div class="col-12 col-md-6 align-self-center text-start text-md-end">
                                                  <a href="terms-and-conditions.php"><button type="button" class="btn btn-outline-secondary btn-sm px-2 mt-2 mt-md-0 ">Withdraw Funds</button></a>    
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
                                                            <span class="fs-18 fw-semibold"><?php echo number_format($balance, 2); ?></span>      
                                                            <h6 class="text-uppercase text-muted mt-2 m-0">Balance</h6>                
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
                                                            <span class="fs-18 fw-semibold">10</span>      
                                                            <h6 class="text-uppercase text-muted mt-2 m-0">Min Deposit</h6>                
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
                                                            <span class="fs-18 fw-semibold">50</span>      
                                                            <h6 class="text-uppercase text-muted mt-2 m-0">Min Withdraw</h6>                
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
                                                            <span class="fs-18 fw-semibold"><?php echo htmlspecialchars($username); ?>,</span>      
                                                            <h6 class="text-uppercase text-muted mt-2 m-0">You Got this</h6>                
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
                                            Crafted with <i class="iconoir-heart text-danger"></i> by Peter
                                            <span
                                                class="text-muted d-none d-sm-inline-block float-end">
                                                Made with
                                                <i class="iconoir-heart text-danger"></i>
                                                by Peter_Webtailors</span>
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