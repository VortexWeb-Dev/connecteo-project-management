<?php
require_once(__DIR__ . '/../crest/crest.php');

function sanitizeInput($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = sanitizeInput($_POST['task_id']);

    $task_title = sanitizeInput($_POST['task_title']);
    $task_description = sanitizeInput($_POST['task_description']);
    $deadline = sanitizeInput($_POST['deadline']);
    $startDate = sanitizeInput($_POST['startDate']);
    $endDate = sanitizeInput($_POST['endDate']);
    $groupId = sanitizeInput($_POST['groupId']);
    $stage = sanitizeInput($_POST['stage']);
    $taskCost = sanitizeInput($_POST['taskCost']);
    $materialResources = sanitizeInput($_POST['materialResources']);
    $responsiblePerson = sanitizeInput($_POST['responsiblePerson']);

    if (empty($task_title) || empty($task_description) || empty($groupId) || empty($stage) || empty($responsiblePerson)) {
        header('Location: ../tasks.php?error_description=' . urlencode('Please fill in all required fields.'));
        exit;
    }

    $fields = [
        'TITLE' => $task_title,
        'DESCRIPTION' => $task_description,
        'GROUP_ID' => $project_id,
        'DEADLINE' => $deadline,
        'START_DATE_PLAN' => $startDate,
        'END_DATE_PLAN' => $endDate,
        'GROUP_ID' => $groupId,
        'STAGE_ID' => $stage,
        'UF_AUTO_586224948951' => $taskCost,
        'UF_AUTO_886998768121' => $materialResources,
        'RESPONSIBLE_ID' => $responsiblePerson,
    ];

    $result = CRest::call('tasks.task.update', [
        'taskId' => $task_id,
        'fields' => $fields,
    ]);


    if (isset($result['error'])) {
        if (isset($_POST['source'])) {
            header('Location: ../' . $_POST['source'] . '&error_description=' . urlencode($result['error_description']));
            exit;
        }

        header('Location: ../tasks.php?error_description=' . urlencode($result['error_description']));
        exit;
    }

    if (isset($_POST['source'])) {
        header('Location: ../' . $_POST['source']);
        exit;
    }

    header('Location: ../tasks.php?success=' . urlencode('Task updated successfully.'));
    exit;
}
