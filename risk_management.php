<?php
include('includes/header.php');
include('includes/sidebar.php');
include('data/fetch_risk_managements.php');
include('data/fetch_projects.php');
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
        <h1 class="text-3xl font-bold ">Risk Management</h1>
        <button class="mt-8 px-6 py-3 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition" onclick="toggleModal()">Add Risk</button>
    </div>

    <!-- Filters Section -->
    <div class="flex space-x-4 mb-6">
        <a href="?filter=" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 <?= $filter == '' ? 'bg-gray-400' : '' ?>">All</a>
        <a href="?filter=identified" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 <?= $filter == 'identified' ? 'bg-gray-400' : '' ?>">Identified</a>
        <a href="?filter=in_progress" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 <?= $filter == 'in_progress' ? 'bg-gray-400' : '' ?>">In Progress</a>
        <a href="?filter=resolved" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 <?= $filter == 'resolved' ? 'bg-gray-400' : '' ?>">Resolved</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($risks as $risk): ?>
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <a href="risk.php?id=<?= $risk['id']; ?>" class="hover:text-gray-500 block">
                    <h2 class="text-xl font-semibold mb-2 truncate"><?= htmlspecialchars($risk['title']); ?></h2>
                </a>

                <div class="text-sm text-gray-600">
                    <p><?= htmlspecialchars($risk['ufCrm15Description']); ?></p>
                    <p><strong>Category:</strong>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold <?= getRiskCategoryBadgeClass($risk['ufCrm15Category']); ?>">
                            <?= getRiskCategoryText($risk['ufCrm15Category']); ?>
                        </span>
                    </p>
                </div>

                <div class="flex justify-end items-center space-x-2 mt-4">
                    <!-- Edit Button -->
                    <a href="edit_risk.php?id=<?= $risk['id']; ?>" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                        <i class="bi bi-pencil"></i>
                    </a>

                    <!-- Delete Button -->
                    <form method="post" action="./data/delete_risk.php">
                        <input type="hidden" name="risk_id" value="<?= htmlspecialchars($risk['id']); ?>">
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this risk?');" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
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

<!-- Modal for Adding Risk -->
<div id="riskModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg w-full max-w-4xl p-6 shadow-lg">
        <h3 class="text-xl font-bold mb-4">Add New Risk</h3>
        <form method="post" action="./data/save_risk.php" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="mb-4">
                <label for="riskDescription" class="block text-gray-700">Description</label>
                <input type="text" class="w-full px-3 py-2 border rounded-lg" id="riskDescription" name="description" required placeholder="Enter risk description">
            </div>
            <div class="mb-4">
                <label for="category" class="block text-gray-700">Category</label>
                <select name="category" id="category" class="w-full px-3 py-2 border rounded-lg" required>
                    <option value="883" selected>Technical</option>
                    <option value="885">Financial</option>
                    <option value="887">Operational</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="probability" class="block text-gray-700">Probability of Occurrence</label>
                <input type="number" placeholder="1 - 5" name="probability" id="probability" min="1" max="5" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            <div class="mb-4">
                <label for="impact" class="block text-gray-700">Risk Impact</label>
                <input type="number" placeholder="1 - 5" name="impact" id="impact" min="1" max="5" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            <div class="mb-4">
                <label for="strategy" class="block text-gray-700">Response Strategy</label>
                <input type="text" class="w-full px-3 py-2 border rounded-lg" id="strategy" name="strategy" required placeholder="Enter response strategy">
            </div>
            <div class="mb-4">
                <label for="owner" class="block text-gray-700">Risk Owner</label>
                <select name="owner" id="owner" class="w-full px-3 py-2 border rounded-lg" required>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= htmlspecialchars($user['ID']); ?>"><?= htmlspecialchars($user['NAME']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="projectId" class="block text-gray-700">Project</label>
                <select name="projectId" id="projectId" class="w-full px-3 py-2 border rounded-lg" required>
                    <?php foreach ($projects as $project) : ?>
                        <option value="<?php echo $project['ID']; ?>"><?php echo $project['NAME']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="status" class="block text-gray-700">Status</label>
                <select name="status" id="status" class="w-full px-3 py-2 border rounded-lg" required>
                    <option value="889" selected>Identified</option>
                    <option value="891">In Progress</option>
                    <option value="893">Resolved</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="plan" class="block text-gray-700">Monitoring Plan</label>
                <input type="text" class="w-full px-3 py-2 border rounded-lg" id="plan" name="plan" required placeholder="Enter monitoring plan">
            </div>
            <div class="flex justify-end col-span-1 md:col-span-2">
                <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-lg mr-2" onclick="toggleModal()">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save Risk</button>
            </div>
        </form>
    </div>
</div>



<script>
    function toggleModal() {
        document.getElementById('riskModal').classList.toggle('hidden');
    }
</script>

<?php include('includes/footer.php'); ?>