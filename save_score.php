<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");
// Set timezone to Thailand
date_default_timezone_set('Asia/Bangkok');
// Database credentials
$servername = "sql12.freesqldatabase.com";
$username = "sql12732167";
$password = "rj4s54kWSu";
$dbname = "sql12732167";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Get the POST data
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['name']) && isset($data['score']) && is_numeric($data['score'])) {
    $name = $conn->real_escape_string($data['name']);
    $score = intval($data['score']);
    $created_at = date('Y-m-d H:i:s'); // Current timestamp

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO scores (name, score, created_at) VALUES (?, ?, ?)");
    if ($stmt === false) {
        http_response_code(500);
        echo json_encode(["error" => "Error preparing statement: " . $conn->error]);
        $conn->close();
        exit();
    }

    $stmt->bind_param("sis", $name, $score, $created_at);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["success" => "Score saved successfully"]);
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Error executing statement: " . $stmt->error]);
    }

    // Close the statement
    $stmt->close();
} else {
    http_response_code(400);
    echo json_encode(["error" => "Invalid input or missing fields"]);
}

// Close the connection
$conn->close();
?>
