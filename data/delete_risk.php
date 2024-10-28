<?php
require_once(__DIR__ . '/../crest/crest.php');
require_once(__DIR__ . '/../crest/settings.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $risk_id = $_POST['risk_id'];

    $result = CRest::call('crm.item.delete', [
        'entityTypeId' => RISK_MANAGEMENT_ENTITY_TYPE_ID,
        'id' => $risk_id,
    ]);

    if (isset($result['error'])) {
        header('Location: ../risk_management.php?error_description=' . urlencode($result['error_description']));
        exit;
    }

    if (isset($_POST['source'])) {
        header('Location: ../' . $_POST['source']);
        exit;
    }

    header('Location: ../risk_management.php?success=' . urlencode('Risk deleted successfully.'));
    exit;
}
