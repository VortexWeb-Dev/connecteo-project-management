<?php
require_once('config/database.php');
include('includes/header.php');
include('includes/sidebar.php');
include('data/fetch_risk_managements.php');
include('data/fetch_projects.php');
include('data/fetch_users.php');

$risk_id = $_GET['id'];
$risk = fetchRisk($risk_id);
?>

<div class="p-10 flex-1">
    <div class="bg-white rounded-lg w-full max-w-4xl p-6 mx-auto">
        <h3 class="text-2xl font-bold mb-4">Edit Risk - <?= $risk['title']; ?></h3>
        <form method="post" action="./data/update_risk.php" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="text" name="risk_id" value="<?= $risk_id; ?>" hidden>
            <div class="mb-4">
                <label for="riskDescription" class="block text-gray-700">Description</label>
                <input value="<?= $risk['ufCrm15Description']; ?>" type="text" class="w-full px-3 py-2 border rounded-lg" id="riskDescription" name="description" required placeholder="Enter risk description">
            </div>
            <div class="mb-4">
                <label for="category" class="block text-gray-700">Category</label>
                <select name="category" id="category" class="w-full px-3 py-2 border rounded-lg" required>
                    <option value="883" <?= $risk['ufCrm15Category'] == '883' ? 'selected' : '' ?>>Technical</option>
                    <option value="885" <?= $risk['ufCrm15Category'] == '885' ? 'selected' : '' ?>>Financial</option>
                    <option value="887" <?= $risk['ufCrm15Category'] == '887' ? 'selected' : '' ?>>Operational</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="probability" class="block text-gray-700">Probability of Occurrence</label>
                <input value="<?= $risk['ufCrm15ProbabilityOfOccurence']; ?>" type="number" placeholder="1 - 5" name="probability" id="probability" min="1" max="5" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            <div class="mb-4">
                <label for="impact" class="block text-gray-700">Risk Impact</label>
                <input value="<?= $risk['ufCrm15RiskImpact']; ?>" type="number" placeholder="1 - 5" name="impact" id="impact" min="1" max="5" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            <div class="mb-4">
                <label for="strategy" class="block text-gray-700">Response Strategy</label>
                <input value="<?= $risk['ufCrm15ResponseStrategy']; ?>" type="text" class="w-full px-3 py-2 border rounded-lg" id="strategy" name="strategy" required placeholder="Enter response strategy">
            </div>
            <div class="mb-4">
                <label for="owner" class="block text-gray-700">Risk Owner</label>
                <select name="owner" id="owner" class="w-full px-3 py-2 border rounded-lg" required>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= htmlspecialchars($user['ID']); ?>" <?= $risk['ufCrm15RiskOwner'] == $user['ID'] ? 'selected' : '' ?>><?= htmlspecialchars($user['NAME']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="projectId" class="block text-gray-700">Project</label>
                <select name="projectId" id="projectId" class="w-full px-3 py-2 border rounded-lg" required>
                    <?php foreach ($projects as $project) : ?>
                        <option value="<?php echo $project['ID']; ?>" <?= $risk['ufCrm15ProjectId'] == $project['ID'] ? 'selected' : '' ?>><?php echo $project['NAME']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="status" class="block text-gray-700">Status</label>
                <select name="status" id="status" class="w-full px-3 py-2 border rounded-lg" required>
                    <option value="889" <?= $risk['ufCrm15RiskStatus'] == '889' ? 'selected' : '' ?>>Identified</option>
                    <option value="891" <?= $risk['ufCrm15RiskStatus'] == '891' ? 'selected' : '' ?>>In Progress</option>
                    <option value="893" <?= $risk['ufCrm15RiskStatus'] == '893' ? 'selected' : '' ?>>Resolved</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="plan" class="block text-gray-700">Monitoring Plan</label>
                <input value="<?= $risk['ufCrm15MonitoringPlan']; ?>" type="text" class="w-full px-3 py-2 border rounded-lg" id="plan" name="plan" required placeholder="Enter monitoring plan">
            </div>
            <div class="flex justify-end col-span-1 md:col-span-2">
                <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-lg mr-2" onclick="window.history.back();">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save Risk</button>
            </div>
        </form>
    </div>
</div>


<?php include('includes/footer.php'); ?>