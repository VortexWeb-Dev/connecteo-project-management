<?php
require_once(__DIR__ . '/../crest/crest.php');
require_once(__DIR__ . '/../crest/settings.php');

function sanitizeInput($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $criteria = sanitizeInput($_POST['criteria']);
    $standards = sanitizeInput($_POST['standards']);
    $kpi = sanitizeInput($_POST['kpi']);
    $measures = sanitizeInput($_POST['measures']);
    $audit = sanitizeInput($_POST['audit']);
    $nonConformities = sanitizeInput($_POST['nonConformities']);
    $correctionPlans = sanitizeInput($_POST['correctionPlans']);
    $manager = sanitizeInput($_POST['manager']);
    $status = sanitizeInput($_POST['status']);

    if (empty($criteria) || empty($manager)) {
        header('Location: ../quality_management.php?error_description=Please fill in all required fields.');
        exit;
    }

    $params = [
        'ufCrm17QualityCriteria' => $criteria,
        'ufCrm17QualityStandards' => $standards,
        'ufCrm17Kpi' => $kpi,
        'ufCrm17QualityMeasures' => $measures,
        'ufCrm17QualityAudits' => $audit,
        'ufCrm17NonConformities' => $nonConformities,
        'ufCrm17CorrectionPlans' => $correctionPlans,
        'ufCrm17QualityManager' => $manager,
        'ufCrm17QualityStatus' => $status,
    ];

    $result = CRest::call('crm.item.add', [
        'entityTypeId' => QUALITY_MANAGEMENT_ENTITY_TYPE_ID,
        'fields' => $params
    ]);

    if (isset($result['error'])) {
        header('Location: ../quality_management.php?error_description=' . urlencode($result['error_description']));
        exit;
    }

    header('Location: ../quality_management.php?success=Risk created successfully.');
    exit;


}
