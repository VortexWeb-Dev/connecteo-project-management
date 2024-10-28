<?php
require_once('../config/database.php');

$conn = getDatabaseConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'];
    $project_id = $_POST['project_id'];

    if($status !== 'INITIATION' && $status !== 'CLOSING' && $status !== 'PLANNING' && $status !== 'EXECUTION' && $status !== 'MONITORING AND CONTROL') {
        header("Location: ../project.php?id=$project_id");
        exit();
    }

    $query = "UPDATE projects SET status = ? WHERE project_id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("si", $status, $project_id);

    if ($stmt->execute()) {
        echo $stmt->affected_rows;
        header("Location: ../project.php?id=$project_id");
        exit();
    } else {
        echo "Error: " . htmlspecialchars($stmt->error);
    }

    $stmt->close();
    $conn->close();
}
