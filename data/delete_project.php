<?php
require_once(__DIR__ . '/../crest/crest.php');
require_once('../config/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = $_POST['project_id'];

    $result = CRest::call('sonet_group.delete', [
        'GROUP_ID' => $project_id,
    ]);

    if (isset($result['error'])) {
        header('Location: ../projects.php?error_description=' . urlencode($result['error']));
        exit;
    }

    if (isset($_POST['source'])) {
        header('Location: ../' . $_POST['source']);
        exit;
    }

    $conn = getDatabaseConnection();

    if ($conn->connect_error) {
        die("Connection failed: " . htmlspecialchars($conn->connect_error));
    }

    $delete_query = "DELETE FROM projects WHERE project_id = ?";
    $stmt = $conn->prepare($delete_query);

    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }

    $stmt->bind_param('i', $project_id);

    if ($stmt->execute()) {
        header('Location: ../projects.php?success=' . urlencode('Project deleted successfully.'));
        exit;
    } else {
        header('Location: ../projects.php?error_description=' . urlencode('Failed to delete project.'));
        exit;
    }

    $stmt->close();
    $conn->close();
}
