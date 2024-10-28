<?php
require_once(__DIR__ . '/../crest/crest.php');

// Get the current page from the query string or set to 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 50; // Number of tasks to fetch per page
$offset = ($page - 1) * $limit; // Calculate offset
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

$params = [
    'order' => ['id' => 'DESC'],
    'start' => $offset,
    'limit' => $limit,
    'select' => ['*', 'UF_AUTO_586224948951'],
];

if ($filter === 'overdue') {
    $params['filter'] = ['STATUS' => -1];
} elseif ($filter === 'in_progress') {
    $params['filter'] = ['REAL_STATUS' => 3];
}


// Fetch tasks with pagination
$result = CRest::call('tasks.task.list', $params);

$tasks = $result['result']['tasks'];
$next = count($tasks) === $limit; // Check if there's a next page\

$totalTasks = $result['total'] ?? 0;
$totalPages = ceil($totalTasks / $limit);

function fetchTask($taskId)
{
    $result = CRest::call('tasks.task.get', ['id' => $taskId, 'select' => ['*', 'UF_AUTO_586224948951']]);
    return $result['result']['task'];
};

function fetchOverdueTasksCount()
{
    $result = CRest::call('tasks.task.list', ['filter' => ['STATUS' => -1]]);
    return $result['total'] ?? 0;
};
function fetchInProgressTasksCount()
{
    $result = CRest::call('tasks.task.list', ['filter' => ['REAL_STATUS' => 3]]);
    return $result['total'] ?? 0;
};
