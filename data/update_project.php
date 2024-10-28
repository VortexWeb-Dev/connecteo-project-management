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
    $project_id = sanitizeInput($_POST['project_id']);

    // Gather and sanitize input
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


    // Validate required fields
    if (empty($project_name) || empty($project_description) || empty($start_date)) {
        header('Location: ../projects.php?error_description=' . urlencode('Please fill in all required fields.'));
        exit;
    }

    // Validate dates
    if (!validateDate($start_date) || !validateDate($end_date)) {
        header('Location: ../projects.php?error_description=' . urlencode('Please enter valid dates.'));
        exit;
    }

    // Validate project cost
    if ($project_cost < 0) {
        header('Location: ../projects.php?error_description=' . urlencode('Project cost cannot be negative.'));
        exit;
    }

    // Prepare parameters for the API call
    $params = [
        'GROUP_ID' => $project_id,
        'NAME' => $project_name,
        'DESCRIPTION' => $project_description,
        'VISIBLE' => $visible,
        'OPENED' => $opened,
        'INITIATE_PERMS' => $permission,
        'CLOSED' => $closed,
        'PROJECT' => $project_type,
        'PROJECT_DATE_START' => $start_date,
        'PROJECT_DATE_FINISH' => $end_date,
    ];

    // Call the API to update the project
    $result = CRest::call('sonet_group.update', $params);


    if (isset($result['error'])) {
        header('Location: ../projects.php?error_description=' . urlencode($result['error']));
        exit;
    }

    $conn = getDatabaseConnection();

    if ($conn->connect_error) {
        die("Connection failed: " . htmlspecialchars($conn->connect_error));
    }

    $query = "UPDATE projects SET status = ?, total_cost = ? WHERE project_id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("sdi", $project_status, $project_cost, $project_id);

    if ($stmt->execute()) {
        header("Location: ../projects.php?success=Project updated successfully");
        exit();
    } else {
        echo "Error: " . htmlspecialchars($stmt->error);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

}
