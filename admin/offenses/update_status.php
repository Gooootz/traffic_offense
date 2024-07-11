<?php
require_once('../config.php');
// Assuming you have a database connection established

// Check if the status is provided in the POST request
if(isset($_POST['status'])) {
    // Sanitize the input (assuming status is integer)
    $status = intval($_POST['status']);

    // Your SQL query to update the status
    // Assuming your table name is 'status' and the primary key is 'id'
    // Also assuming you have a variable $pdo representing your PDO connection
    $query = "UPDATE status SET status = :status WHERE id = :id"; // Modify according to your schema

    // Prepare and execute the query
    $statement = $pdo->prepare($query);
    // Assuming you have a primary key 'id' to identify the row to update
    $id = 1; // Change this to your actual id value
    $statement->execute(array(':status' => $status, ':id' => $id));

    // Check if the query was successful
    if($statement->rowCount() > 0) {
        // Return a success response
        $response = array('success' => true);
        echo json_encode($response);
        exit; // Stop further execution
    } else {
        // Return an error response
        $response = array('success' => false, 'message' => 'Failed to update status.');
        echo json_encode($response);
        exit; // Stop further execution
    }
} else {
    // Return an error response if status is not provided
    $response = array('success' => false, 'message' => 'Status not provided.');
    echo json_encode($response);
    exit; // Stop further execution
}
?>