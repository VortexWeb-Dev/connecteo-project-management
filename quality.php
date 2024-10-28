<?php
include('includes/header.php');
include('includes/sidebar.php');
include('crest/crest.php');
include('crest/settings.php');
include('utils/index.php');

$result = CRest::call('crm.item.get', [
    'entityTypeId' => QUALITY_MANAGEMENT_ENTITY_TYPE_ID,
    'id' => $_GET['id'],
]);
$quality = $result['result']['item'];
?>

<div class="p-10 flex-1">
    <!-- Risk Title and Overview -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800"><?= htmlspecialchars($quality['title']); ?></h1>
        <p class="text-gray-600 mt-2"><?= htmlspecialchars($quality['ufCrm17QualityCriteria'] ?: 'No quality criteria available'); ?></p>
    </div>

    <!-- Risk Details Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- General Info Card -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">General Information</h2>
            <ul class="text-gray-600 space-y-2">
                <li><strong>Key Performance Indicators (KPIs):</strong> <?= htmlspecialchars($quality['ufCrm17Kpi']); ?></li>
                <li><strong>Quality Measures:</strong> <?= htmlspecialchars($quality['ufCrm17QualityMeasures']); ?></li>
                <li><strong>Quality Audits:</strong> <?= date_format(new DateTime($quality['ufCrm17QualityAudits']), 'd/m/Y') ?></li>
                <li><strong>Non-Conformities:</strong> <?= htmlspecialchars($quality['ufCrm17NonConformities']); ?></li>
                <li><strong>Correction Plans:</strong> <?= htmlspecialchars($quality['ufCrm17CorrectionPlans']); ?></li>
                <li><strong>Status:</strong>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold <?= getQualityStatusBadgeClass($quality['ufCrm17QualityStatus']); ?>">
                        <?= getQualityStatusText($quality['ufCrm17QualityStatus']); ?>
                    </span>
                </li>
            </ul>
        </div>

        <!-- Assignees and Auditors Card -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Responsible & Creator</h2>
            <ul class="text-gray-600 space-y-2">
                <li><strong>Creator:</strong>
                    <a href="<?= 'https://connecteo.bitrix24.in/company/personal/user/' . $quality['createdBy'] . '/'; ?>" class="text-blue-500 hover:underline">
                        Created By
                    </a>
                </li>
                <li><strong>Responsible:</strong>
                    <a href="<?= 'https://connecteo.bitrix24.in/company/personal/user/' . $quality['ufCrm17QualityManager'] . '/'; ?>" class="text-blue-500 hover:underline">
                        Quality Manager
                    </a>
                </li>

            </ul>
        </div>
    </div>

</div>

<?php include('includes/footer.php'); ?>