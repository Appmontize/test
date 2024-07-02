<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "balllu";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}else{
    echo "Connected successfully";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad_id = $_POST['ad_id'];
    $ip = $_POST['ip'];

    $stmt = $conn->prepare("SELECT COUNT(*) FROM prelanding_ips WHERE ip = ?");
    $stmt->bind_param("s", $ip);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        $stmt = $conn->prepare("INSERT INTO app_data (ad_id, ip, verified) VALUES (?, ?, ?)");
        $verified = 1;
        $stmt->bind_param("ssi", $ad_id, $ip, $verified);

        if ($stmt->execute()) {
            // Fire postback
            file_get_contents("http://www.google.com");
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }

        $stmt->close();
    } else {
        $stmt = $conn->prepare("INSERT INTO app_data (ad_id, ip, verified) VALUES (?, ?, ?)");
        $verified = 0;
        $stmt->bind_param("ssi", $ad_id, $ip, $verified);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }

        $stmt->close();
    }
}

$conn->close();
?>
