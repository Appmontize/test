
<?php
header("Access-Control-Allow-Origin: *"); // Allow requests from any origin (not recommended for production)

header("Content-Type: application/json");
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "balllu";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ip = $_POST['ip'];

    $stmt = $conn->prepare("INSERT INTO prelanding_ips (ip) VALUES (?)");
    $stmt->bind_param("s", $ip);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error storing IP']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?>
