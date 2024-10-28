<?php
require_once(__DIR__ . '/../crest/crest.php');

// Get the current page number from the query parameters, default to 1
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 50;
$offset = ($page - 1) * $limit;

// Fetch projects with pagination
$result = CRest::call('sonet_group.get', [
    'FILTER' => ['PROJECT' => 'Y'],
    'order' => ['ID' => 'DESC'],
    'start' => $offset,
    'limit' => $limit
]);

$projects = $result['result'];
$next = $result['next'] ?? null;

$totalProjects = $result['total'] ?? 0;
$totalPages = ceil($totalProjects / $limit);

function fetchProject($projectId)
{
    $result = CRest::call('socialnetwork.api.workgroup.get', ['params' => ['groupId' => $projectId],]);
    return $result['result'];
};
function fetchActiveProjectsCount()
{
    $result = CRest::call('sonet_group.get', ['FILTER' => ['PROJECT' => 'Y', 'ACTIVE' => 'Y'],]);
    return $result['total'] ?? 0;
};
function fetchInactiveProjectsCount()
{
    $result = CRest::call('sonet_group.get', ['FILTER' => ['PROJECT' => 'Y', 'ACTIVE' => 'N'],]);
    return $result['total'] ?? 0;
};
