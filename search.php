<?php
// search.php

// Include database connection file
include 'connect.php';

// Check if the search query is set and not empty
if (isset($_GET['query']) && !empty($_GET['query'])) {
    // Sanitize the user input
    $searchQuery = htmlspecialchars($_GET['query']);

    // Prepare the SQL statement to search in the database
    // This assumes you have a table named 'products' with columns 'name' and 'description'
    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ?");
    
    // Add wildcards to the search term for partial matching
    $searchTerm = '%' . $searchQuery . '%';
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    
    // Execute the query
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();

    // Check if any rows were returned
    if ($result->num_rows > 0) {
        echo "<h2>Search Results for: " . $searchQuery . "</h2>";
        echo "<ul>";

        // Fetch and display each result
        while ($row = $result->fetch_assoc()) {
            echo "<li><strong>" . $row['name'] . ":</strong> " . $row['description'] . "</li>";
        }

        echo "</ul>";
    } else {
        // If no results are found
        echo "<h2>No results found for: " . $searchQuery . "</h2>";
    }

    // Close the statement
    $stmt->close();
} else {
    // If no query was submitted
    echo "<h2>Please enter a search term.</h2>";
}

// Close the database connection
$conn->close();
?>
