<?php
include('includes/header.php');
include('includes/sidebar.php');
include('data/fetch_quality_managements.php');
include('data/fetch_users.php');
include('utils/index.php');

?>

<div class="p-10 flex-1">
    <?php if (isset($_GET['error_description'])): ?>
        <div class="bg-red-500 text-white p-3 rounded mb-4 absolute top-2 right-2">
            <?php echo htmlspecialchars($_GET['error_description']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <div class="bg-green-500 text-white p-3 rounded mb-4 absolute top-2 right-2">
            <?php echo htmlspecialchars($_GET['success']); ?>
        </div>
    <?php endif; ?>

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold ">Quality Management</h1>
        <button class="mt-8 px-6 py-3 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition" onclick="toggleModal()">Add Quality</button>
    </div>

    <!-- Filters Section -->
    <div class="flex space-x-4 mb-6">
        <a href="?filter=" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 <?= $filter == '' ? 'bg-gray-400' : '' ?>">All</a>
        <a href="?filter=complaint" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 <?= $filter == 'complaint' ? 'bg-gray-400' : '' ?>">Complaint</a>
        <a href="?filter=non_complaint" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 <?= $filter == 'non_complaint' ? 'bg-gray-400' : '' ?>">Non Complaint</a>
        <a href="?filter=in_correction" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 <?= $filter == 'in_correction' ? 'bg-gray-400' : '' ?>">In Correction</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($qualities as $quality): ?>
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <a href="quality.php?id=<?= $quality['id']; ?>" class="hover:text-gray-500 block">
                    <h2 class="text-xl font-semibold mb-2 truncate"><?= htmlspecialchars($quality['title']); ?></h2>
                </a>

                <div class="text-sm text-gray-600 space-y-2">
                    <p><strong>Status:</strong>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold <?= getQualityStatusBadgeClass($quality['ufCrm17QualityStatus']); ?>">
                            <?= getQualityStatusText($quality['ufCrm17QualityStatus']); ?>
                        </span>
                    </p>
                    <p><strong>Quality Audits:</strong> <?= date_format(new DateTime($quality['ufCrm17QualityAudits']), 'd/m/Y') ?></p>
                </div>

                <!-- Delete Button -->


                <div class="flex justify-end items-center space-x-2 mt-4">
                    <!-- Edit Button -->
                    <a href="edit_quality.php?id=<?= $quality['id']; ?>" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                        <i class="bi bi-pencil"></i>
                    </a>

                    <!-- Delete Button -->
                    <form method="post" action="./data/delete_quality.php">
                        <input type="hidden" name="quality_id" value="<?= htmlspecialchars($quality['id']); ?>">
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this quality?');" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>

    </div>

    <div class="mt-10 flex justify-end space-x-4">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1; ?>" class="px-6 py-3 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">Previous</a>
        <?php endif; ?>

        <?php if ($next): ?>
            <a href="?page=<?= $page + 1; ?>" class="px-6 py-3 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">Next</a>
        <?php endif; ?>
    </div>


</div>

<!-- Modal for Adding Quality -->
<div id="qualityModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg w-full max-w-4xl p-6 shadow-lg">
        <h3 class="text-xl font-bold mb-4">Add New Quality</h3>
        <form method="post" action="./data/save_quality.php" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="mb-4">
                <label for="qualityCriteria" class="block text-gray-700">Quality Criteria</label>
                <input type="text" class="w-full px-3 py-2 border rounded-lg" id="qualityCriteria" name="criteria" required placeholder="Enter quality criteria">
            </div>
            <div class="mb-4">
                <label for="qualityStandards" class="block text-gray-700">Quality Standards</label>
                <input type="text" class="w-full px-3 py-2 border rounded-lg" id="qualityStandards" name="standards" required placeholder="Enter quality standards (Eg. ISO 9001)">
            </div>
            <div class="mb-4">
                <label for="kpi" class="block text-gray-700">Key Performance Indicators (KPIs)</label>
                <input type="text" name="kpi" id="kpi" class="w-full px-3 py-2 border rounded-lg" required placeholder="Enter KPIs">
            </div>
            <div class="mb-4">
                <label for="measures" class="block text-gray-700">Quality Measures</label>
                <input type="text" name="measures" id="measures" class="w-full px-3 py-2 border rounded-lg" required placeholder="Enter quality measures">
            </div>
            <div class="mb-4">
                <label for="audit" class="block text-gray-700">Quality Audits</label>
                <input type="date" class="w-full px-3 py-2 border rounded-lg" id="audit" name="audit" required placeholder="Enter response strategy">
            </div>
            <div class="mb-4">
                <label for="nonConformities" class="block text-gray-700">Non-Conformities</label>
                <input type="text" class="w-full px-3 py-2 border rounded-lg" id="nonConformities" name="nonConformities" required placeholder="Enter non-conformities">
            </div>
            <div class="mb-4">
                <label for="correctionPlans" class="block text-gray-700">Correction Plans</label>
                <input type="text" class="w-full px-3 py-2 border rounded-lg" id="correctionPlans" name="correctionPlans" required placeholder="Enter correction plans">
            </div>
            <div class="mb-4">
                <label for="manager" class="block text-gray-700">Quality Manager</label>
                <select name="manager" id="manager" class="w-full px-3 py-2 border rounded-lg" required>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= htmlspecialchars($user['ID']); ?>"><?= htmlspecialchars($user['NAME']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="status" class="block text-gray-700">Status</label>
                <select name="status" id="status" class="w-full px-3 py-2 border rounded-lg" required>
                    <option value="895" selected>Complaint</option>
                    <option value="897">Non-Complaint</option>
                    <option value="899">In Correction</option>
                </select>
            </div>
            <div class="flex justify-end col-span-1 md:col-span-2">
                <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-lg mr-2" onclick="toggleModal()">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save Quality</button>
            </div>
        </form>
    </div>
</div>



<script>
    function toggleModal() {
        document.getElementById('qualityModal').classList.toggle('hidden');
    }
</script>

<?php include('includes/footer.php'); ?>