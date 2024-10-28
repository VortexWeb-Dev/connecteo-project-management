<?php
require_once(__DIR__ . '/../crest/crest.php');
require_once(__DIR__ . '/../crest/settings.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quality_id = $_POST['quality_id'];

    $result = CRest::call('crm.item.delete', [
        'entityTypeId' => QUALITY_MANAGEMENT_ENTITY_TYPE_ID,
        'id' => $quality_id,
    ]);

    if (isset($result['error'])) {
        header('Location: ../quality_management.php?error_description=' . urlencode($result['error_description']));
        exit;
    }

    if (isset($_POST['source'])) {
        header('Location: ../' . $_POST['source']);
        exit;
    }

    header('Location: ../quality_management.php?success=' . urlencode('Quality deleted successfully.'));
    exit;
}
