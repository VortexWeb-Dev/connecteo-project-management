<?php
require_once(__DIR__ . '/../crest/crest.php');
require_once('../config/database.php');

function sanitizeInput($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function validateDate($date)
{
    return (bool)strtotime($date);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_name = sanitizeInput($_POST['title']);
    $project_description = sanitizeInput($_POST['description']);
    $visible = isset($_POST['visible']) ? sanitizeInput($_POST['visible']) : 'Y';
    $opened = isset($_POST['opened']) ? sanitizeInput($_POST['opened']) : 'Y';
    $permission = isset($_POST['permission']) ? sanitizeInput($_POST['permission']) : 'A';
    $closed = isset($_POST['closed']) ? sanitizeInput($_POST['closed']) : 'N';
    $project_type = isset($_POST['project']) ? sanitizeInput($_POST['project']) : 'Y';
    $start_date = sanitizeInput($_POST['startDate']);
    $end_date = sanitizeInput($_POST['endDate']);
    $project_cost = isset($_POST['projectCost']) ? floatval(sanitizeInput($_POST['projectCost'])) : 0;
    $project_status = sanitizeInput($_POST['projectStatus']);

    if (empty($project_name) || empty($project_description) || empty($start_date)) {
        header('Location: ../projects.php?error_description=' . urlencode('Please fill in all required fields.'));
        exit;
    }

    if (!validateDate($start_date) || !validateDate($end_date)) {
        header('Location: ../projects.php?error_description=' . urlencode('Please enter valid dates.'));
        exit;
    }

    if ($project_cost < 0) {
        header('Location: ../projects.php?error_description=' . urlencode('Project cost cannot be negative.'));
        exit;
    }

    $params = [
        'NAME' => $project_name,
        'DESCRIPTION' => $project_description,
        'VISIBLE' => $visible,
        'OPENED' => $opened,
        'PERMISSION' => $permission,
        'CLOSED' => $closed,
        'PROJECT' => $project_type,
        'PROJECT_DATE_START' => $start_date,
        'PROJECT_DATE_FINISH' => $end_date,
    ];

    $result = CRest::call('sonet_group.create', $params);

    if (isset($result['error'])) {
        header('Location: ../projects.php?error_description=' . urlencode($result['error']));
        exit;
    }

    $project_id = $result['result'];

    $conn = getDatabaseConnection();

    if ($conn->connect_error) {
        die("Connection failed: " . htmlspecialchars($conn->connect_error));
    }

    $db_project_query = "INSERT INTO projects (project_id, status, total_cost) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($db_project_query);

    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }

    $stmt->bind_param('isd', $project_id, $project_status, $project_cost);

    if ($stmt->execute()) {
        header('Location: ../projects.php?success=' . urlencode('Project created successfully.'));
        exit;
    } else {
        header('Location: ../projects.php?error_description=' . urlencode('Failed to save project details.'));
        exit;
    }

    $stmt->close();
    $conn->close();
}
