<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: text/html; charset=UTF-8");

// Database credentials
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
// Set charset to UTF-8
$conn->set_charset("utf8mb4");
// Fetch scores
$sql = "SELECT `id`, `name`, `score`  FROM `scores` ORDER BY `score` DESC";
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

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MUSIC THEORY BY KRUBOY</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #7f91db;
            font-family: 'Arial', sans-serif;
            color: #333;
        }
        #container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 60px;
        }
        #menu {
            display: flex;
            flex-direction: column;
            align-items: stretch;
            margin-right: 20px;
            height: 500px; /* ความสูงของกล่องเมนู */
        }
        #menu button {
            background-color: #007bff; /* สีฟ้าสำหรับปุ่ม */
            border: 5px solid #fff; /* ขอบสีขาว */
            color: white;
            font-size: 40px;
            padding: 0; /* ลบ padding เพื่อตั้งขนาดปุ่มให้เหมาะสม */
            margin: 10px 0;
            border-radius: 20px;
            cursor: pointer;
            transition: transform 0.2s;
            width: 250px; /* กว้างเท่ากับความกว้างของกล่องเมนู */
            height: calc(100% / 3 - 20px); /* ความสูงของปุ่มแต่ละปุ่มพอดีกับกล่อง */
            box-sizing: border-box;
        }
        #menu button:hover {
            transform: scale(1.1);
            background-color: #0056b3;
        }
        #menu button:focus {
            outline: none;
        }
        #musicCanvas {
            border: 15px solid hsl(51, 100%, 50%);
            border-radius: 58px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            background-color: rgb(253, 253, 253); /* พื้นสีขาว */
            width: 1000px;
            height: 650px;
        }
        #scoreTable {
            border: 2px solid #007bff;
            border-radius: 15px;
            overflow: hidden;
            margin: 20px;
            background-color: #fff;
            width: 80%;
            max-height: 500px;
            overflow-y: auto;
        }
        #scoreTable table {
            width: 100%;
            border-collapse: collapse;
        }
        #scoreTable th, #scoreTable td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        #scoreTable th {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <div id="container">
        <div id="menu">
            <button onclick="switchMode('learn.html')">เรียน</button>
            <button onclick="switchMode('game.html')">เล่นเกมส์</button>
            <button onclick="switchMode('score.php')">คะแนน</button>
        </div>
        <div id="musicCanvas">
            <div id="scoreTable">
                <table>
                    <thead>
                        <tr>
                            <th>ลำดับที่</th>
                            <th>ชื่อผู้เล่น</th>
                            <th>คะแนน</th>
                        </tr>
                    </thead>
                    <tbody id="scoreTableBody">
                        <?php
                        foreach ($scores as $score) {
                            echo "<tr>";
                            echo "<td>{$score['id']}</td>";
                            echo "<td>{$score['name']}</td>";
                            echo "<td>{$score['score']}</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function switchMode(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
