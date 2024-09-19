<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

$servername = "sql12.freesqldatabase.com";
$username = "sql12732167";
$password = "rj4s54kWSu";
$dbname = "sql12732167";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check request method
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod == 'GET') {
    // GET request - Fetch scores
    $sql = "SELECT `name`, `score` FROM `scores` ORDER BY `score` DESC";
    $result = $conn->query($sql);

    // Create array to store data
    $scores = array();

    if ($result) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $scores[] = $row;
            }
        }
    } else {
        // Output SQL error
        echo json_encode(array('error' => $conn->error));
        exit;
    }

    // Send data as JSON
    echo json_encode(array('scores' => $scores));
} elseif ($requestMethod == 'POST') {
    // POST request - Insert score
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'];
    $score = $data['score'];

    $sql = "INSERT INTO `scores` (name, score) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $name, $score);

    $response = array();
    if ($stmt->execute()) {
        $response["status"] = "success";
    } else {
        $response["status"] = "error";
        $response["message"] = $stmt->error;
    }

    $stmt->close();
    echo json_encode($response);
}

// Close connection
$conn->close();
?>
