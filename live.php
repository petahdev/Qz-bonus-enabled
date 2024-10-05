<?php
// Start the session
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to login page
    header("Location: index.php");
    exit();
}

// Connect to the database
include 'connect.php';

// Query to get all users' details and total clicks
$query = "
    SELECT u.id, u.username, u.email, u.mobilenumber AS mobile_number, u.password AS unhashed_password, 
           u.created_at, COALESCE(SUM(lc.click_count), 0) AS total_clicks
    FROM users u
    LEFT JOIN link_clicks lc ON u.id = lc.user_id
    GROUP BY u.id, u.username, u.email, u.mobilenumber, u.created_at, u.password
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #202221;
            margin: 0;
            padding: 20px;
            color:white;
        }
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color:#202221;
        }
        table, th, td {
            border: 1px solid #202221;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #22c55e;
            color:white;
        }
        tr:hover {
            background-color: #22c55e;
            color:#fff;
        }
    </style>
</head>
<body>

    <h1>Gainly Admin Dashboard - User Overview</h1>

    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Mobile Number</th>
                <th>Balance (Ksh)</th>
                <th>Created At</th>
                <th>Total Clicks</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    // Calculate balance as total clicks * 4 Ksh
                    $balance = $row['total_clicks'] * 4;

                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['username']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['mobile_number']}</td>
                        <td>" . number_format($balance, 2) . "</td>
                        <td>{$row['created_at']}</td>
                        <td>{$row['total_clicks']}</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No users found</td></tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>

<?php
// Close the connection
$conn->close();
?>
