<?php
require_once(__DIR__ . '/../crest/crest.php');
require_once(__DIR__ . '/../crest/settings.php');

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 50;
$offset = ($page - 1) * $limit;
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

$params = [
    'entityTypeId' => RISK_MANAGEMENT_ENTITY_TYPE_ID,
    'order' => ['ID' => 'DESC'],
    'start' => $offset,
    'limit' => $limit
];

if ($filter === 'identified') {
    $params['filter'] = ['ufCrm15RiskStatus' => 889];
} elseif ($filter === 'in_progress') {
    $params['filter'] = ['ufCrm15RiskStatus' => 891];
} elseif ($filter === 'resolved') {
    $params['filter'] = ['ufCrm15RiskStatus' => 893];
}


$result = CRest::call('crm.item.list', $params);

$risks = $result['result']['items'];
$dashboardRisks = array_slice($risks, 0, 2);

$next = $result['next'] ?? null;

$totalRisks = $result['total'] ?? 0;
$totalzPages = ceil($totalRisks / $limit);

function fetchResolvedRisksCount()
{
    $result = CRest::call('crm.item.list', [
        'entityTypeId' => RISK_MANAGEMENT_ENTITY_TYPE_ID,
        'FILTER' => ['ufCrm15RiskStatus' => 893],
    ]);
    return $result['total'] ?? 0;
};

function fetchTechnicalRisksCount()
{
    $result = CRest::call('crm.item.list', [
        'entityTypeId' => RISK_MANAGEMENT_ENTITY_TYPE_ID,
        'filter' => ['ufCrm15Category' => 883],
    ]);
    return $result['total'] ?? 0;
};

function fetchFinancialRisksCount()
{
    $result = CRest::call('crm.item.list', [
        'entityTypeId' => RISK_MANAGEMENT_ENTITY_TYPE_ID,
        'filter' => ['ufCrm15Category' => 885],
    ]);
    return $result['total'] ?? 0;
};

function fetchOperationalRisksCount()
{
    $result = CRest::call('crm.item.list', [
        'entityTypeId' => RISK_MANAGEMENT_ENTITY_TYPE_ID,
        'filter' => ['ufCrm15Category' => 887],
    ]);
    return $result['total'] ?? 0;
};

function fetchProjectRisks($project_id)
{
    $result = CRest::call('crm.item.list', [
        'entityTypeId' => RISK_MANAGEMENT_ENTITY_TYPE_ID,
        'filter' => ['ufCrm15ProjectId' => $project_id],
    ]);
    return $result['result']['items'] ?? [];
};

function fetchRisk($risk_id)
{
    $result = CRest::call('crm.item.get', [
        'entityTypeId' => RISK_MANAGEMENT_ENTITY_TYPE_ID,
        'id' => $risk_id,
    ]);
    return $result['result']['item'] ?? [];
};
