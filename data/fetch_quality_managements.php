<?php
require_once(__DIR__ . '/../crest/crest.php');
require_once(__DIR__ . '/../crest/settings.php');

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 50;
$offset = ($page - 1) * $limit;
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

$params = [
    'entityTypeId' => QUALITY_MANAGEMENT_ENTITY_TYPE_ID,
    'order' => ['ID' => 'DESC'],
    'start' => $offset,
    'limit' => $limit
];

if ($filter === 'complaint') {
    $params['filter'] = ['ufCrm17QualityStatus' => 895];
} elseif ($filter === 'non_complaint') {
    $params['filter'] = ['ufCrm17QualityStatus' => 897];
} elseif ($filter === 'in_correction') {
    $params['filter'] = ['ufCrm17QualityStatus' => 899];
}

$result = CRest::call('crm.item.list', $params);

$qualities = $result['result']['items'];
$dashboardQualities = array_slice($qualities, 0, 2);

$next = $result['next'] ?? null;

$totalQualities = $result['total'] ?? 0;
$totalPages = ceil($totalQualities / $limit);


function fetchComplaintQualitiesCount()
{
    $result = CRest::call('crm.item.list', [
        'entityTypeId' => QUALITY_MANAGEMENT_ENTITY_TYPE_ID,
        'filter' => ['ufCrm17QualityStatus' => 895],
    ]);
    return $result['total'] ?? 0;
};

function fetchNonComplaintQualitiesCount()
{
    $result = CRest::call('crm.item.list', [
        'entityTypeId' => QUALITY_MANAGEMENT_ENTITY_TYPE_ID,
        'filter' => ['ufCrm17QualityStatus' => 897],
    ]);
    return $result['total'] ?? 0;
};

function fetchInCorrectionQualitiesCount()
{
    $result = CRest::call('crm.item.list', [
        'entityTypeId' => QUALITY_MANAGEMENT_ENTITY_TYPE_ID,
        'filter' => ['ufCrm17QualityStatus' => 899],
    ]);
    return $result['total'] ?? 0;
};

function fetchQuality($quality_id)
{
    $result = CRest::call('crm.item.get', [
        'entityTypeId' => QUALITY_MANAGEMENT_ENTITY_TYPE_ID,
        'id' => $quality_id
    ]);
    return $result['result']['item'];
};
