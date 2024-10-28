<?php
include('includes/header.php');
include('includes/sidebar.php');
include('data/fetch_projects.php');
?>

<div class="p-10 flex-1">
    <?php if (isset($_GET['error'])): ?>
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
        <h1 class="text-3xl font-bold ">Projects</h1>
        <button class="mt-8 px-6 py-3 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition" onclick="toggleModal()">Add Project</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($projects as $project): ?>
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <a href="project.php?id=<?= $project['ID']; ?>" class="hover:text-gray-500 block">
                    <h2 class="text-xl font-semibold mb-2 truncate"><?= htmlspecialchars($project['NAME']); ?></h2>
                </a>

                <div class="text-sm text-gray-600">
                    <p><strong>No. of Members:</strong> <?= htmlspecialchars($project['NUMBER_OF_MEMBERS']); ?></p>
                    <p><strong>Created On:</strong> <?= date_format(new DateTime($project['DATE_CREATE']), 'd/m/Y') ?></p>
                </div>

                <div class="flex justify-end items-center space-x-2 mt-4">
                    <!-- Edit Button -->
                    <a href="edit_project.php?id=<?= $project['ID']; ?>" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <!-- Delete Button -->
                    <form method="post" action="./data/delete_project.php">
                        <input type="hidden" name="project_id" value="<?= htmlspecialchars($project['ID']); ?>">
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this project?');" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
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

<!-- Modal for Adding Project -->
<div id="projectModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg w-full max-w-4xl p-6 shadow-lg">
        <h3 class="text-xl font-bold mb-4">Add New Project</h3>
        <form method="post" action="./data/save_project.php" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="mb-4">
                <label for="exampleInputTitle" class="block text-gray-700">Title</label>
                <input type="text" class="w-full px-3 py-2 border rounded-lg" id="exampleInputTitle" name="title" required placeholder="Enter project title">
            </div>
            <div class="mb-4">
                <label for="exampleInputDescription" class="block text-gray-700">Description</label>
                <input type="text" class="w-full px-3 py-2 border rounded-lg" id="exampleInputDescription" name="description" required placeholder="Enter project description">
            </div>
            <div class="mb-4">
                <label for="visible" class="block text-gray-700">Visible</label>
                <select name="visible" id="visible" class="w-full px-3 py-2 border rounded-lg" required>
                    <option value="Y" selected>Yes</option>
                    <option value="N">No</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="opened" class="block text-gray-700">Opened</label>
                <select name="opened" id="opened" class="w-full px-3 py-2 border rounded-lg" required>
                    <option value="Y" selected>Yes</option>
                    <option value="N">No</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="permission" class="block text-gray-700">Permission</label>
                <select name="permission" id="permission" class="w-full px-3 py-2 border rounded-lg" required>
                    <option value="A" selected>Group Owner Only</option>
                    <option value="E">Group Owner and Moderator</option>
                    <option value="K">All Group Members</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="closed" class="block text-gray-700">Closed</label>
                <select name="closed" id="closed" class="w-full px-3 py-2 border rounded-lg" required>
                    <option value="Y">Yes</option>
                    <option value="N" selected>No</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="project" class="block text-gray-700">Project Type</label>
                <select name="project" id="project" class="w-full px-3 py-2 border rounded-lg" required>
                    <option value="Y" selected>Project</option>
                    <option value="N">Workgroup</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="exampleInputstartDate" class="block text-gray-700">Start Date</label>
                <input class="w-full px-3 py-2 border rounded-lg" type="date" name="startDate" id="exampleInputstartDate" required>
            </div>
            <div class="mb-4">
                <label for="exampleInputEndDate" class="block text-gray-700">End Date</label>
                <input class="w-full px-3 py-2 border rounded-lg" type="date" name="endDate" id="exampleInputEndDate">
            </div>
            <div class="mb-4">
                <label for="projectCost" class="block text-gray-700">Project Cost</label>
                <input class="w-full px-3 py-2 border rounded-lg" type="number" name="projectCost" id="projectCost" placeholder="Enter project cost">
            </div>
            <div class="mb-4">
                <label for="projectStatus" class="block text-gray-700">Project Status</label>
                <select name="projectStatus" id="projectStatus" class="w-full px-3 py-2 border rounded-lg">
                    <option value="INITIATION" selected>Initiation</option>
                    <option value="PLANNING">Planning</option>
                    <option value="EXECUTION">Planning</option>
                    <option value="MONITORING AND CONTROL">Monitoring and Control</option>
                    <option value="CLOSING">Closing</option>
                </select>
            </div>
            <div class="flex justify-end col-span-1 md:col-span-2">
                <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-lg mr-2" onclick="toggleModal()">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save Project</button>
            </div>
        </form>
    </div>
</div>



<script>
    function toggleModal() {
        document.getElementById('projectModal').classList.toggle('hidden');
    }
</script>

<?php include('includes/footer.php'); ?>