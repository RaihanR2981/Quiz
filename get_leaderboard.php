<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'mathquiz';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$level = $_GET['level'] ?? 'all';

if ($level === 'all') {
    $sql = "SELECT * FROM leaderboard ORDER BY total_score DESC LIMIT 10";
    $stmt = $conn->prepare($sql);
} else {
    $sql = "SELECT * FROM leaderboard WHERE level = ? ORDER BY total_score DESC LIMIT 10";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $level);
}

$stmt->execute();
$result = $stmt->get_result();
$leaderboard = [];

while ($row = $result->fetch_assoc()) {
    $leaderboard[] = $row;
}

echo json_encode($leaderboard);
$stmt->close();
$conn->close();
?>
