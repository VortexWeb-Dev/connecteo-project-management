<?php
require_once('../config/database.php');

include('../utils/index.php');
include('fetch_projects.php');
include('fetch_tasks.php');
include('fetch_risk_managements.php');
include('fetch_quality_managements.php');

$conn = getDatabaseConnection();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$projects_budget = "SELECT SUM(total_cost) as projects_budget FROM projects";
$stmt = $conn->prepare($projects_budget);

if ($stmt === false) {
    die("Prepare failed: " . htmlspecialchars($conn->error));
}

if (!$stmt->execute()) {
    die("Execute failed: " . htmlspecialchars($stmt->error));
}

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalProjectsBudget = (float)$row['projects_budget']; 
} else {
    echo "No projects found.";
}

$stmt->close();


$overdueTasks = fetchOverdueTasksCount();
$inProgressTasks = fetchInProgressTasksCount();
$activeProjectsCount = fetchActiveProjectsCount();
$inactiveProjectsCount = fetchInactiveProjectsCount();
$resolvedRisks = fetchResolvedRisksCount();
$inCorrectionQualities = fetchInCorrectionQualitiesCount();

$totalBudget = array_sum(array_column($projects, 'budget'));
$totalExpenses = array_sum(array_column($projects, 'expenses'));

$risksData = [
    'total' => $totalRisks,
    'technical' => fetchTechnicalRisksCount(),
    'financial' => fetchFinancialRisksCount(),
    'operational' => fetchOperationalRisksCount()
];
$qualitiesData = [
    'total' => $totalQualities,
    'complaint' => fetchComplaintQualitiesCount(),
    'nonComplaint' => fetchNonComplaintQualitiesCount(),
    'inCorrection' => fetchInCorrectionQualitiesCount()
];

foreach ($dashboardRisks as &$risk) {
    $risk['statusClass'] = getRiskStatusBadgeClass($risk['ufCrm15RiskStatus']);
    $risk['statusText'] = getRiskStatusText($risk['ufCrm15RiskStatus']);
    $risk['categoryClass'] = getRiskCategoryBadgeClass($risk['ufCrm15Category']);
    $risk['categoryText'] = getRiskCategoryText($risk['ufCrm15Category']);
}



echo json_encode([
    'totalProjects' => $totalProjects,
    'totalTasks' => $totalTasks,
    'totalRisks' => $totalRisks,
    'totalQualities' => $totalQualities,
    'overdueTasks' => $overdueTasks,
    'inProgressTasks' => $inProgressTasks,
    'activeProjectsCount' => $activeProjectsCount,
    'inactiveProjectsCount' => $inactiveProjectsCount,
    'resolvedRisks' => $resolvedRisks,
    'inCorrectionQualities' => $inCorrectionQualities,
    'risksData' => $risksData,
    'qualitiesData' => $qualitiesData,
    'dashboardRisks' => $dashboardRisks,
    'dashboardQualities' => $dashboardQualities,
    'totalProjectsBudget' => $totalProjectsBudget,
]);
