<?php
require_once(__DIR__ . '/../crest/crest.php');

$result = CRest::call('user.get');
$users = $result['result'];
