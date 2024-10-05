<?php
// Connect to the database
include 'connect.php';

// Array to hold the dashboard data
$dashboardData = [];

// Fetch total clicks
$query = "SELECT SUM(click_count) AS total_clicks FROM link_clicks";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$dashboardData['total_clicks'] = $row['total_clicks'] ?? 0;

// Fetch total balance
$query = "SELECT SUM(balance) AS total_balance FROM funds";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$dashboardData['total_balance'] = number_format($row['total_balance'] ?? 0, 2);

// Fetch total number of users
$query = "SELECT COUNT(id) AS total_users FROM users";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$dashboardData['total_users'] = $row['total_users'] ?? 0;

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($dashboardData);

// Close the connection
$conn->close();
?>
