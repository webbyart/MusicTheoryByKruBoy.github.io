<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

$servername = "https://demo.phpmyadmin.net/"; // เปลี่ยนเป็นเซิร์ฟเวอร์ฐานข้อมูลของคุณ
$username = "root"; // เปลี่ยนเป็นชื่อผู้ใช้ฐานข้อมูลของคุณ
$password = ""; // เปลี่ยนเป็นรหัสผ่านของคุณ
$dbname = "game_scores"; // ชื่อฐานข้อมูลที่คุณสร้าง

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
    $sql = "SELECT `name`, `score` FROM `scores` ORDER BY `score` DESC LIMIT 10";
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
