<?php
include('includes/header.php');
include('includes/sidebar.php');

include('data/fetch_tasks.php');
include('data/fetch_projects.php');
include('data/fetch_task_stages.php');
include('data/fetch_users.php');

include('utils/index.php');
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
        <h1 class="text-3xl font-bold mb-4">Tasks</h1>
        <button class="mt-8 px-6 py-3 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition" onclick="toggleModal()">Add Task</button>
    </div>

     <!-- Filters Section -->
     <div class="flex space-x-4 mb-6">
        <a href="?filter=" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 <?= $filter == '' ? 'bg-gray-400' : '' ?>">All Tasks</a>
        <a href="?filter=overdue" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 <?= $filter == 'overdue' ? 'bg-gray-400' : '' ?>">Overdue Tasks</a>
        <a href="?filter=in_progress" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 <?= $filter == 'in_progress' ? 'bg-gray-400' : '' ?>">Tasks in Progress</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($tasks as $task): ?>
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <a href="task.php?id=<?= $task['id']; ?>" class="hover:text-gray-500 block">
                    <h2 class="text-xl font-semibold mb-2"><?= htmlspecialchars($task['title']); ?></h2>
                </a>
                <div class="text-sm text-gray-600 mb-2">
                    <p><strong>Project ID:</strong> <?= htmlspecialchars($task['groupId']); ?></p>
                    <p><strong>Date Created:</strong> <?= date_format(new DateTime($task['createdDate']), 'd/m/Y'); ?></p>
                    <p><strong>Deadline:</strong> <?= $task['deadline'] ? date_format(new DateTime($task['deadline']), 'd/m/Y') : 'No deadline'; ?></p>
                    <div class="mt-3">
                        <span class="px-3 py-1 rounded-full text-sm font-semibold <?= getStatusBadgeClass($task['status']); ?>">
                            <?= getStatusText($task['status']); ?>
                        </span>
                    </div>
                </div>

                <div class="flex justify-end items-center space-x-2 mt-4">
                    <!-- Edit Button -->
                    <a href="edit_task.php?id=<?= $task['id']; ?>" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                        <i class="bi bi-pencil"></i>
                    </a>

                    <!-- Delete Button -->
                    <form method="POST" action="./data/delete_task.php">
                        <input type="hidden" name="task_id" value="<?= htmlspecialchars($task['id']); ?>">
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this task?');" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>

            </div>
        <?php endforeach; ?>

    </div>

    <!-- Pagination Section -->
    <div class="mt-10 flex justify-end space-x-4">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1; ?>" class="px-6 py-3 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">Previous</a>
        <?php endif; ?>

        <?php if ($next): ?>
            <a href="?page=<?= $page + 1; ?>" class="px-6 py-3 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">Next</a>
        <?php endif; ?>
    </div>
</div>

<!-- Modal for Adding Task -->
<div id="taskModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg w-3/4 lg:w-1/2 p-6 shadow-lg">
        <h3 class="text-xl font-bold mb-4">Add New Task</h3>
        <form method="post" action="./data/save_task.php">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="taskTitle" class="block text-gray-700">Title</label>
                    <input type="text" class="w-full px-3 py-2 border rounded-lg" id="taskTitle" name="task_title" required placeholder="Enter task title">
                </div>
                <div class="mb-4">
                    <label for="taskDescription" class="block text-gray-700">Description</label>
                    <input type="text" class="w-full px-3 py-2 border rounded-lg" id="taskDescription" name="task_description" required placeholder="Enter task description">
                </div>
                <div class="mb-4">
                    <label for="taskDeadline" class="block text-gray-700">Deadline</label>
                    <input type="date" class="w-full px-3 py-2 border rounded-lg" id="taskDeadline" name="deadline" required>
                </div>
                <div class="mb-4">
                    <label for="taskStartDate" class="block text-gray-700">Start Date</label>
                    <input type="date" class="w-full px-3 py-2 border rounded-lg" id="taskStartDate" name="startDate" required>
                </div>
                <div class="mb-4">
                    <label for="taskEndDate" class="block text-gray-700">End Date</label>
                    <input type="date" class="w-full px-3 py-2 border rounded-lg" id="taskEndDate" name="endDate">
                </div>
                <div class="mb-4">
                    <label for="groupId" class="block text-gray-700">Workgroup</label>
                    <select name="groupId" id="groupId" class="w-full px-3 py-2 border rounded-lg" required>
                        <?php foreach ($projects as $grp) : ?>
                            <option value="<?php echo $grp['ID']; ?>"><?php echo $grp['NAME']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="stage" class="block text-gray-700">Stage</label>
                    <select name="stage" id="stage" class="w-full px-3 py-2 border rounded-lg" required>
                        <?php foreach ($stages as $stageId => $stageName) : ?>
                            <option value="<?php echo $stageId; ?>"><?php echo $stageName; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="responsiblePerson" class="block text-gray-700">Responsible Person</label>
                    <select name="responsiblePerson" id="responsiblePerson" class="w-full px-3 py-2 border rounded-lg" required>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= htmlspecialchars($user['ID']); ?>"><?= htmlspecialchars($user['NAME']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="taskCost" class="block text-gray-700">Task Cost</label>
                    <input type="text" class="w-full px-3 py-2 border rounded-lg" id="taskCost" name="taskCost" required placeholder="Enter task cost">
                </div>
                <div class="mb-4">
                    <label for="materialResources" class="block text-gray-700">Material Resources</label>
                    <input type="text" class="w-full px-3 py-2 border rounded-lg" id="materialResources" name="materialResources" required placeholder="Enter material resources">
                </div>
            </div>

            <div class="flex justify-end mt-4">
                <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-lg mr-2" onclick="toggleModal()">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save Task</button>
            </div>
        </form>
    </div>
</div>


<script>
    function toggleModal() {
        document.getElementById('taskModal').classList.toggle('hidden');
    }
</script>

<?php include('includes/footer.php'); ?>