<?php
require_once('config/database.php');
include('includes/header.php');
include('includes/sidebar.php');
include('data/fetch_risk_managements.php');
include('data/fetch_task_stages.php');
include('data/fetch_projects.php');
include('data/fetch_tasks.php');
include('data/fetch_users.php');

$task_id = $_GET['id'];
$task = fetchTask($task_id);

?>

<div class="p-10 flex-1">
    <div class="bg-white rounded-lg w-full max-w-4xl p-6 mx-auto">
        <h3 class="text-2xl font-bold mb-4">Edit Task - <?= $task['title']; ?></h3>
        <form method="post" action="./data/update_task.php">
            <input type="text" name="task_id" value="<?= $task_id; ?>" hidden>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="taskTitle" class="block text-gray-700">Title</label>
                    <input value="<?= $task['title']; ?>" type="text" class="w-full px-3 py-2 border rounded-lg" id="taskTitle" name="task_title" required placeholder="Enter task title">
                </div>
                <div class="mb-4">
                    <label for="taskDescription" class="block text-gray-700">Description</label>
                    <input value="<?= $task['description']; ?>" type="text" class="w-full px-3 py-2 border rounded-lg" id="taskDescription" name="task_description" required placeholder="Enter task description">
                </div>
                <div class="mb-4">
                    <label for="taskDeadline" class="block text-gray-700">Deadline</label>
                    <input value="<?= (new DateTime($task['deadline']))->format('Y-m-d'); ?>" type="date" class="w-full px-3 py-2 border rounded-lg" id="taskDeadline" name="deadline" required>
                </div>
                <div class="mb-4">
                    <label for="taskStartDate" class="block text-gray-700">Start Date</label>
                    <input value="<?= (new DateTime($task['startDatePlan']))->format('Y-m-d'); ?>" type="date" class="w-full px-3 py-2 border rounded-lg" id="taskStartDate" name="startDate" required>
                </div>
                <div class="mb-4">
                    <label for="taskEndDate" class="block text-gray-700">End Date</label>
                    <input value="<?= (new DateTime($task['endDatePlan']))->format('Y-m-d'); ?>" type="date" class="w-full px-3 py-2 border rounded-lg" id="taskEndDate" name="endDate">
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
                            <option value="<?php echo $stageId; ?>" <?php echo $stageId == $task['stageId'] ? 'selected' : ''; ?>><?php echo $stageName; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="responsiblePerson" class="block text-gray-700">Responsible Person</label>
                    <select name="responsiblePerson" id="responsiblePerson" class="w-full px-3 py-2 border rounded-lg" required>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= htmlspecialchars($user['ID']); ?>" <?= $user['ID'] == $task['responsible']['id'] ? 'selected' : ''; ?>><?= htmlspecialchars($user['NAME']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="taskCost" class="block text-gray-700">Task Cost</label>
                    <input value="<?= $task['ufAuto586224948951']; ?>" type="text" class="w-full px-3 py-2 border rounded-lg" id="taskCost" name="taskCost" required placeholder="Enter task cost">
                </div>
                <div class="mb-4">
                    <label for="materialResources" class="block text-gray-700">Material Resources</label>
                    <input value="<?= $task['ufAuto886998768121']; ?>" type="text" class="w-full px-3 py-2 border rounded-lg" id="materialResources" name="materialResources" required placeholder="Enter material resources">
                </div>
            </div>

            <div class="flex justify-end mt-4">
                <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-lg mr-2" onclick="toggleModal()">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save Task</button>
            </div>
        </form>
    </div>
</div>


<?php include('includes/footer.php'); ?>