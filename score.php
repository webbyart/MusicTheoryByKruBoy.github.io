<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

$servername = "sql12.freesqldatabase.com";
$username = "sql12732167";
$password = "rj4s54kWSu";
$dbname = "sql12732167";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบประเภทคำขอ (GET หรือ POST)
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod == 'GET') {
    // คำขอ GET - ดึงข้อมูลคะแนน
    $sql = "SELECT `name`, `score` FROM `scores` ORDER BY `score`";
    $result = $conn->query($sql);

    // สร้าง array สำหรับเก็บข้อมูล
    $scores = array();

    // ตรวจสอบว่ามีข้อมูลหรือไม่
    if ($result->num_rows > 0) {
        // เก็บข้อมูลใน array
        while ($row = $result->fetch_assoc()) {
            $scores[] = $row;
        }
    }

    // ส่งข้อมูลเป็น JSON
    header('Content-Type: application/json');
    echo json_encode(array('scores' => $scores));
} elseif ($requestMethod == 'POST') {
    // คำขอ POST - เพิ่มข้อมูลคะแนน
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
    header('Content-Type: application/json');
    echo json_encode($response);
}

// ปิดการเชื่อมต่อ
$conn->close();
?>
