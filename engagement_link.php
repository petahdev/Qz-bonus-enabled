<?php
// Start session
session_start();

// Connect to the database
include 'connect.php';

// Get the user ID from the session
$userId = $_SESSION['user_id'];

// Check the last ad click time for this user
$query = "SELECT last_click_time FROM link_clicks WHERE user_id = ? ORDER BY last_click_time DESC LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($last_click_time);
$stmt->fetch();

$bgColor = '#333'; // Default color

if ($last_click_time) {
    $time_diff = time() - strtotime($last_click_time);
    if ($time_diff < 86400) { // 86400 seconds in 24 hours
        $bgColor = '#22c55e'; // Color for clicked ad
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gainly | Ad Dashboard</title>

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- App css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Your CSS styling */
        .row {
            display: flex;
            align-items: center;
            width: 98%;
            margin: 10px auto;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            color: #ffffff;
            text-decoration: none;
        }
        .row:hover {
            background-color: #444;
        }
        body {
            background-color: #202221;
            color: #ffffff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            padding: 10px;
        }
        .icon {
            width: fit-content;
            height: 64px;
            border-radius: 2px;
        }
        .description {
            flex-grow: 1;
        }
        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 10px;
            background: black;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 10;
        }
        .header img {
            height: 40px;
        }
        .header .company-name {
            color: #22c55e;
            font-size: 24px;
        }
        .hero {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: url('assets/images/users/bg.png') no-repeat fixed center center/cover;
            position: relative;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }
        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: #fff;
        }
        .hero-content h1 {
            font-size: 48px;
            margin: 0;
        }
        .hero-content p {
            font-size: 18px;
            margin-top: 10px;
        }
        .search-bar {
            margin-top: 20px;
        }
        .search-bar form {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .search-bar input[type="text"] {
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 25px 0 0 25px;
            width: 300px;
            outline: none;
        }
        .search-bar input[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 0 25px 25px 0;
            background-color: red;
            color: white;
            cursor: pointer;
        }
        .search-bar input[type="submit"]:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>

<header class="header">
    <img src="assets/images/users/GAINLY-LOGO.png" alt="Gainly Logo" class="logo">
    <div class="company-name" style="color:white;">#Gainlikeapro</div>
</header>

<section class="hero">
    <div class="hero-content">
        <h1>Welcome to Gainly</h1>
        <p>Hey! Watch Ads in the section below to earn</p>
        <p>#Gainlikeapro</p>
    </div>
</section>

<!-- Ad Section -->
<?php
// Connect to the database
include 'connect.php';

// Get the user ID from the session
$userId = $_SESSION['user_id'];

// Define ads
$ads = [
    ['title' => 'Watch Ad 1', 'link' => 'https://www.cpmrevenuegate.com/zqtxfubhks?key=1fda895b2547122784738b785efb0326'],
    ['title' => 'Watch Ad 2', 'link' => 'https://www.cpmrevenuegate.com/dfzsq5hu?key=7c395a94e6760486a46ce7c354e4c2d3'],
    ['title' => 'Watch Ad 3', 'link' => 'https://www.cpmrevenuegate.com/q2u7mgak6?key=e6fbed9a59e1654c0d97fa551c619318'],
    ['title' => 'Watch Ad 4', 'link' => 'https://www.cpmrevenuegate.com/dm9443gj?key=8430620e6ffda3777b68c5c03195d98b'],
    ['title' => 'Watch Ad 5', 'link' => 'https://www.cpmrevenuegate.com/tgxv0epv9?key=d979a8b7f98b9e47c7a02e351238a170'],
    ['title' => 'Watch Ad 6', 'link' => 'https://singingfiles.com/show.php?l=0&u=2240050&id=57902'],
    ['title' => 'Watch Ad 7', 'link' => 'https://singingfiles.com/show.php?l=0&u=2240050&id=63322'],
    ['title' => 'Watch Ad 8', 'link' => 'https://singingfiles.com/show.php?l=0&u=2240050&id=56781'],
    ['title' => 'Watch Ad 9', 'link' => 'https://singingfiles.com/show.php?l=0&u=2240050&id=61323'],
    ['title' => 'Watch Ad 10', 'link' => 'https://singingfiles.com/show.php?l=0&u=2240050&id=63321'],
    ['title' => 'Watch Ad 10', 'link' => 'https://singingfiles.com/show.php?l=0&u=2240050&id=61325'],
    ['title' => 'iPHONE ADD- Required{ engage with form, add email until the end to earn}', 'link' => 'https://singingfiles.com/show.php?l=0&u=2240050&id=56781'],
    ['title' => 'Watch Ad 10', 'link' => 'https://singingfiles.com/show.php?l=0&u=2240050&id=59796'],
    ['title' => 'Watch Ad 10', 'link' => 'https://tesla.com'],
    ['title' => 'Watch Ad 10', 'link' => 'https://twit.com'],
    ['title' => 'Watch Ad 10', 'link' => 'https://book.com'],
    ['title' => 'Watch Ad 10', 'link' => 'https://yoles.com'],
    ['title' => 'Watch Ad 10', 'link' => 'https://tub.com'],
    ['title' => 'Watch Ad 10', 'link' => 'https://meslau.com'],
    ['title' => 'Watch Ad 10', 'link' => 'https://yt.com'],
];

foreach ($ads as $ad) {
    // Check the last click time for each ad
    $ad_link = $ad['link'];
    $query = "SELECT last_click_time FROM link_clicks WHERE user_id = ? AND link_url = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $userId, $ad_link);
    $stmt->execute();
    $stmt->store_result();

    $bgColor = '#333'; // Default color

    if ($stmt->num_rows > 0) {
        // Fetch the last click time
        $stmt->bind_result($last_click_time);
        $stmt->fetch();

        // Check if 24 hours have passed since the last click
        $time_diff = time() - strtotime($last_click_time);
        if ($time_diff < 86400) { // 86400 seconds in 24 hours
            $bgColor = '#22c55e'; // Color for clicked ad
        }
    }
?>
    <a href="watch_ad.php?ad_link=<?php echo urlencode($ad['link']); ?>" class="row" style="display:flex; background-color: <?php echo $bgColor; ?>;">
        <div class="description"><?php echo htmlspecialchars($ad['title']); ?></div>
        <div class="icon"><i class="fas fa-play"></i></div>
    </a>
<?php
}
?>
<form style="width:100%; background-color:#22c55e; text-align:center; justify-content:center; padding:15px;" action="update_funds.php" method="POST">
    <input type="hidden" name="update_funds" value="1">
    <input style=" background-color:#22c55e; border:none; color:white; font-size:12px;" type="submit" value="Update Funds" class="button">
</form>
</body>
</html>
