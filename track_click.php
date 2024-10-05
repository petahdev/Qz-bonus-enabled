<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Connect to the database
include 'connect.php';

$user_id = $_SESSION['user_id'];

// Define the list of URLs
$urls = [
    'https://www.cpmrevenuegate.com/dm9443gj?key=8430620e6ffda3777b68c5c03195d98b',
    'https://www.cpmrevenuegate.com/dfzsq5hu?key=7c395a94e6760486a46ce7c354e4c2d3',
    'https://www.cpmrevenuegate.com/ch356sx0?key=2eb0b3eb90b3f001feeaf0d173302aee',
    'https://www.cpmrevenuegate.com/q2u7mgak6?key=e6fbed9a59e1654c0d97fa551c619318',
    'https://www.cpmrevenuegate.com/zqtxfubhks?key=1fda895b2547122784738b785efb0326',
    'https://www.cpmrevenuegate.com/jyqbwwk72?key=109b871d2af178bb4f9ee99efc5bd114', // Sample URL 2
    'https://www.cpmrevenuegate.com/kr4c532cf?key=7b43d4fe584e155d717da0c5491f4b08', // Sample URL 3
    'https://www.cpmrevenuegate.com/c358ih99wp?key=55005b9e8866c076dd22367ff9c0e4a2', // Sample URL 4
    'https://www.cpmrevenuegate.com/k4nustah9?key=b3dd296c563771d07f572803c4e7e590', // Sample URL 5
    'https://www.cpmrevenuegate.com/eiav1d4zu?key=a5168744e88ca8582848f671dfaf37d8', // Sample URL 6
    
    '' // Sample URL 10
];

// Retrieve the URL from the GET parameter
if (isset($_GET['link'])) {
    $link_url = $_GET['link'];

    // Check if the URL is in the list of defined URLs
    if (in_array($link_url, $urls)) {
        // Update click count in the link_clicks table
        $stmt = $conn->prepare("SELECT click_count FROM link_clicks WHERE user_id = ? AND link_url = ?");
        $stmt->bind_param("is", $user_id, $link_url);
        $stmt->execute();
        $stmt->bind_result($click_count);
        $stmt->fetch();
        $stmt->close();

        if ($click_count !== null) {
            // Increment the click count
            $new_click_count = $click_count + 1;
            
            // Update the click count in the database
            $stmt = $conn->prepare("UPDATE link_clicks SET click_count = ? WHERE user_id = ? AND link_url = ?");
            $stmt->bind_param("iis", $new_click_count, $user_id, $link_url);
            $stmt->execute();
            $stmt->close();
        } else {
            // Insert a new record if it doesn't exist
            $stmt = $conn->prepare("INSERT INTO link_clicks (user_id, link_url, click_count) VALUES (?, ?, 1)");
            $stmt->bind_param("is", $user_id, $link_url);
            $stmt->execute();
            $stmt->close();
        }

        // Update user's funds
        $amount_per_click = 40; // Amount in Kshs
        $amount_to_add = $amount_per_click;

        $stmt = $conn->prepare("UPDATE funds SET balance = balance + ? WHERE user_id = ?");
        $stmt->bind_param("di", $amount_to_add, $user_id);
        $stmt->execute();
        $stmt->close();

        // Redirect to the link
        header("Location: $link_url");
        exit();
    } else {
        // Handle the case where the URL is not in the list
        header("Location: error.php");
        exit();
    }
} else {
    // Handle the case where no link is provided
    header("Location: error.php");
    exit();
}
?>
