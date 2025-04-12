<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$database = "bk_pfic_new";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => "Connection failed: " . $conn->connect_error]));
}

// Retrieve and sanitize the search parameter
$search = $conn->real_escape_string($_GET['search'] ?? '');

// Validate input
if (empty($search)) {
    die(json_encode(['error' => 'Search parameter is required']));
}

// Define the table name
$table = "hlcs"; // Replace with your table's name

// SQL query to search the table for the specified search term
$sql = "SELECT Gene, Pop, HGVS_NP, ACMG_Classification, Variant_one, Variant_two 
        FROM $table 
        WHERE Gene LIKE '%$search%'  
        OR Pop LIKE '%$search%' 
        OR Disease LIKE '%$search%' 
        OR ACMG_Classification LIKE '%$search%' 
        OR Variant_one LIKE '%$search%'
        OR Variant_two LIKE '%$search%'
        OR HGVS_NP LIKE '%$search%'";

// Execute the query
$result = $conn->query($sql);

// Check for query execution errors
if ($result === false) {
    die(json_encode(['error' => 'Error executing query: ' . $conn->error]));
}

// Fetch results
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Return the results in JSON format
echo json_encode(['data' => $data, 'total' => count($data)]);

// Close the database connection
$conn->close();
?>