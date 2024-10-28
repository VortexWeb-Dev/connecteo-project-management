<?php
require_once(__DIR__ . '/../crest/crest.php');
require_once(__DIR__ . '/../crest/settings.php');

function sanitizeInput($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $risk_description = sanitizeInput($_POST['description']);
    $category = sanitizeInput($_POST['category']);
    $probability = sanitizeInput($_POST['probability']);
    $impact = sanitizeInput($_POST['impact']);
    $strategy = sanitizeInput($_POST['strategy']);
    $owner = sanitizeInput($_POST['owner']);
    $status = sanitizeInput($_POST['status']);
    $plan = sanitizeInput($_POST['plan']);
    $projectId = sanitizeInput($_POST['projectId']);

    $risk_priority = $probability * $impact;

    if (empty($risk_description) || empty($owner)) {
        header('Location: ../risk_management.php?error_description=Please fill in all required fields.');
        exit;
    }

    $params = [
        'ufCrm15Description' => $risk_description,
        'ufCrm15Category' => $category,
        'ufCrm15ProbabilityOfOccurence' => $probability,
        'ufCrm15RiskImpact' => $impact,
        'ufCrm15RiskPriority' => $risk_priority,
        'ufCrm15ResponseStrategy' => $strategy,
        'ufCrm15RiskOwner' => $owner,
        'ufCrm15RiskStatus' => $status,
        'ufCrm15MonitoringPlan' => $plan,
        'ufCrm15ProjectId' => $projectId
    ];

    $result = CRest::call('crm.item.add', [
        'entityTypeId' => RISK_MANAGEMENT_ENTITY_TYPE_ID,
        'fields' => $params
    ]);

    if (isset($result['error'])) {
        header('Location: ../risk_management.php?error_description=' . urlencode($result['error_description']));
        exit;
    }

    if (isset($_POST['source'])) {
        header('Location: ../' . $_POST['source']);
        exit;
    }

    header('Location: ../risk_management.php?success=Risk created successfully.');
    exit;
}
