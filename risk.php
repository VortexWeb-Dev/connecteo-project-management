<?php
include('includes/header.php');
include('includes/sidebar.php');
include('crest/crest.php');
include('crest/settings.php');
include('utils/index.php');
include('data/fetch_risk_managements.php');

$risk_id = $_GET['id'];

$risk = fetchRisk($risk_id);
?>

<div class="p-10 flex-1">
    <!-- Risk Title and Overview -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800"><?= htmlspecialchars($risk['title']); ?></h1>
        <p class="text-gray-600 mt-2"><?= htmlspecialchars($risk['ufCrm15Description'] ?: 'No description available'); ?></p>
    </div>

    <!-- Risk Details Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- General Info Card -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">General Information</h2>
            <ul class="text-gray-600 space-y-2">
                <li><strong>Category:</strong>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold <?= getRiskCategoryBadgeClass($risk['ufCrm15Category']); ?>">
                        <?= getRiskCategoryText($risk['ufCrm15Category']); ?>
                    </span>
                </li>
                <li><strong>Probability of Occurrence:</strong> <?= htmlspecialchars($risk['ufCrm15ProbabilityOfOccurence']); ?>/5</li>
                <li><strong>Risk Impact:</strong> <?= htmlspecialchars($risk['ufCrm15RiskImpact']); ?>/5</li>
                <li><strong>Risk Priority:</strong> <?= htmlspecialchars($risk['ufCrm15RiskPriority']); ?></li>
                <li><strong>Response Strategy:</strong> <?= htmlspecialchars($risk['ufCrm15ResponseStrategy']); ?></li>
                <li><strong>Monitoring Plan:</strong> <?= htmlspecialchars($risk['ufCrm15MonitoringPlan']); ?></li>
                <li><strong>Risk Owner:</strong> <?= htmlspecialchars($risk['ufCrm15RiskOwnerName']); ?></li>
                <li><strong>Status:</strong>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold <?= getRiskStatusBadgeClass($risk['ufCrm15RiskStatus']); ?>">
                        <?= getRiskStatusText($risk['ufCrm15RiskStatus']); ?>
                    </span>
                </li>
            </ul>
        </div>

        <!-- Assignees and Auditors Card -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Responsible & Creator</h2>
            <ul class="text-gray-600 space-y-2">
                <li><strong>Creator:</strong>
                    <a href="<?= 'https://connecteo.bitrix24.in/company/personal/user/' . $risk['createdBy'] . '/'; ?>" class="text-blue-500 hover:underline">
                        Created By
                    </a>
                </li>
                <li><strong>Responsible:</strong>
                    <a href="<?= 'https://connecteo.bitrix24.in/company/personal/user/' . $risk['ufCrm15RiskOwner'] . '/'; ?>" class="text-blue-500 hover:underline">
                        Risk Owner
                    </a>
                </li>

            </ul>
        </div>
    </div>

</div>

<?php include('includes/footer.php'); ?>