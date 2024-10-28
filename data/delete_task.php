<?php
require_once(__DIR__ . '/../crest/crest.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'];

    $result = CRest::call('tasks.task.delete', [
        'taskId' => $task_id,
    ]);

    if (isset($result['error'])) {
        header('Location: ../tasks.php?error_description=' . urlencode($result['error']));
        exit;
    }

    if (isset($_POST['source'])) {
        header('Location: ../' . $_POST['source']);
        exit;
    }

    header('Location: ../tasks.php?success=' . urlencode('Task deleted successfully.'));
    exit;
}
