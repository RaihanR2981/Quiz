<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'mathquiz';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);
$full_name = $data['fullName'];
$username = $data['username'];
$level = $data['level'];
$score = $data['score'];

$sql = "INSERT INTO leaderboard (full_name, username, level, total_score, best_score, quiz_count)
        VALUES (?, ?, ?, ?, ?, 1)
        ON DUPLICATE KEY UPDATE 
        total_score = total_score + VALUES(total_score),
        quiz_count = quiz_count + 1,
        best_score = IF(VALUES(best_score) > best_score, VALUES(best_score), best_score)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssii", $full_name, $username, $level, $score, $score);
$stmt->execute();
$stmt->close();
$conn->close();

echo json_encode(["success" => true]);
?>
