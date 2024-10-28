<?php
require_once('config/database.php');
include('includes/header.php');
include('includes/sidebar.php');
include('data/fetch_quality_managements.php');
include('data/fetch_projects.php');
include('data/fetch_users.php');

$quality_id = $_GET['id'];
$quality = fetchQuality($quality_id);

?>

<div class="p-10 flex-1">
    <div class="bg-white rounded-lg w-full max-w-4xl p-6 mx-auto">
        <h3 class="text-2xl font-bold mb-4">Edit Risk - <?= $quality['title']; ?></h3>
        <form method="post" action="./data/update_quality.php" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="text" name="quality_id" value="<?= $quality_id; ?>" hidden>
            <div class="mb-4">
                <label for="qualityCriteria" class="block text-gray-700">Quality Criteria</label>
                <input value="<?= $quality['ufCrm17QualityCriteria']; ?>" type="text" class="w-full px-3 py-2 border rounded-lg" id="qualityCriteria" name="criteria" required placeholder="Enter quality criteria">
            </div>
            <div class="mb-4">
                <label for="qualityStandards" class="block text-gray-700">Quality Standards</label>
                <input value="<?= $quality['ufCrm17QualityStandards']; ?>" type="text" class="w-full px-3 py-2 border rounded-lg" id="qualityStandards" name="standards" required placeholder="Enter quality standards (Eg. ISO 9001)">
            </div>
            <div class="mb-4">
                <label for="kpi" class="block text-gray-700">Key Performance Indicators (KPIs)</label>
                <input value="<?= $quality['ufCrm17Kpi']; ?>" type="text" name="kpi" id="kpi" class="w-full px-3 py-2 border rounded-lg" required placeholder="Enter KPIs">
            </div>
            <div class="mb-4">
                <label for="measures" class="block text-gray-700">Quality Measures</label>
                <input value="<?= $quality['ufCrm17QualityMeasures']; ?>" type="text" name="measures" id="measures" class="w-full px-3 py-2 border rounded-lg" required placeholder="Enter quality measures">
            </div>
            <div class="mb-4">
                <label for="audit" class="block text-gray-700">Quality Audits</label>
                <input value="<?= (new DateTime($quality['ufCrm17QualityAudits']))->format('Y-m-d'); ?>" type="date" class="w-full px-3 py-2 border rounded-lg" id="audit" name="audit" required placeholder="Enter response strategy">
            </div>
            <div class="mb-4">
                <label for="nonConformities" class="block text-gray-700">Non-Conformities</label>
                <input value="<?= $quality['ufCrm17NonConformities']; ?>" type="text" class="w-full px-3 py-2 border rounded-lg" id="nonConformities" name="nonConformities" required placeholder="Enter non-conformities">
            </div>
            <div class="mb-4">
                <label for="correctionPlans" class="block text-gray-700">Correction Plans</label>
                <input value="<?= $quality['ufCrm17CorrectionPlans']; ?>" type="text" class="w-full px-3 py-2 border rounded-lg" id="correctionPlans" name="correctionPlans" required placeholder="Enter correction plans">
            </div>
            <div class="mb-4">
                <label for="manager" class="block text-gray-700">Quality Manager</label>
                <select name="manager" id="manager" class="w-full px-3 py-2 border rounded-lg" required>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= htmlspecialchars($user['ID']); ?>" <?= $user['ID'] == $quality['ufCrm17QualityManager'] ? 'selected' : ''; ?>><?= htmlspecialchars($user['NAME']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="status" class="block text-gray-700">Status</label>
                <select name="status" id="status" class="w-full px-3 py-2 border rounded-lg" required>
                    <option value="895" <?= $quality['ufCrm17QualityStatus'] == '895' ? 'selected' : ''; ?>>Complaint</option>
                    <option value="897" <?= $quality['ufCrm17QualityStatus'] == '897' ? 'selected' : ''; ?>>Non-Complaint</option>
                    <option value="899" <?= $quality['ufCrm17QualityStatus'] == '899' ? 'selected' : ''; ?>>In Correction</option>
                </select>
            </div>
            <div class="flex justify-end col-span-1 md:col-span-2">
                <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-lg mr-2" onclick="window.history.back();">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save Quality</button>
            </div>
        </form>
    </div>
</div>


<?php include('includes/footer.php'); ?>