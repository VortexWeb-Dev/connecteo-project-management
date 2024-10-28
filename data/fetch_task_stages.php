<?php
require_once(__DIR__ . '/../crest/crest.php');

$stagesResult = CRest::call('task.stages.get', ['entityid' => 'TASKS']);
$stages = [];
if ($stagesResult && isset($stagesResult['result'])) {
    foreach ($stagesResult['result'] as $stage) {
        $stages[$stage['ID']] = $stage['TITLE'];
    }
}
