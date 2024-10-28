<?php
require_once('config/database.php');
include('includes/header.php');
include('includes/sidebar.php');

include('data/fetch_projects.php');
include('data/fetch_task_stages.php');
include('data/fetch_risk_managements.php');
include('data/fetch_users.php');

include('utils/index.php');

$project_id = $_GET['id'];

$project = fetchProject($project_id);

// echo '<pre>';
// print_r($project);
// echo '</pre>';

$tasks_res = CRest::call('tasks.task.list', ['filter' => ['GROUP_ID' => $project_id], 'select' => ['*', 'UF_AUTO_586224948951']]);
$tasks = $tasks_res['result']['tasks'];
$risks = fetchProjectRisks($project_id);

$totalProjectCost = 0;
foreach ($tasks as $task) {
    $totalProjectCost += $task['ufAuto586224948951'] ?? 0;
}

$conn = getDatabaseConnection();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$db_project_query = "SELECT * FROM projects WHERE project_id = ?";
$stmt = $conn->prepare($db_project_query);

if ($stmt === false) {
    die("Prepare failed: " . htmlspecialchars($conn->error));
}

$stmt->bind_param('i', $project_id);
$stmt->execute();
$db_project_result = $stmt->get_result();

if ($db_project_result->num_rows > 0) {
    $db_project = $db_project_result->fetch_assoc();
} else {
    $db_project = null;
}
$stmt->close();

?>

<div class="container mx-auto px-4 py-10">
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

    <!-- Project Overview -->
    <div class="text-center mb-10">
        <h1 class="text-4xl font-extrabold text-blue-700">Project Overview</h1>
        <p class="text-gray-500 mt-2 text-lg">Detailed insights into the project's goals and status</p>
    </div>

    <!-- Project Card -->
    <div class="flex justify-center mb-10">
        <div class="bg-gradient-to-r from-blue-50 to-white shadow-lg rounded-lg w-full md:w-2/3 lg:w-1/2 p-6 border-l-4 border-blue-600">
            <div class="flex items-center justify-between">
                <h2 class="text-3xl font-semibold text-blue-800"><?= htmlspecialchars($project['NAME']); ?></h2>
                <?php if (isset($db_project)): ?>
                    <form action="data/update_project_status.php" method="post" class="flex items-center space-x-2">
                        <input type="hidden" name="project_id" value="<?= $project_id; ?>">
                        <label for="status" class="text-sm font-medium">Project Status</label>
                        <select name="status" id="status" class="border border-gray-300 rounded-md p-1 focus:outline-none focus:ring focus:ring-blue-300">
                            <option value="<?= htmlspecialchars($db_project['status']); ?>"><?= htmlspecialchars($db_project['status']); ?></option>
                            <option value="INITIATION">Initiation</option>
                            <option value="PLANNING">Planning</option>
                            <option value="EXECUTION">Execution</option>
                            <option value="MONITORING AND CONTROL">Monitoring and Control</option>
                            <option value="CLOSING">Closing</option>
                        </select>
                        <button type="submit" class="bg-blue-600 text-white rounded-md px-4 py-2 transition duration-300 hover:bg-blue-700 focus:outline-none">Update</button>
                    </form>
                <?php endif; ?>
            </div>
            <p class="text-gray-700 mt-4"><?= htmlspecialchars($project['DESCRIPTION']); ?></p>
            <p class="text-gray-600 mt-2 text-lg">Project Cost: <span class="text-blue-700 font-bold"><?= number_format($totalProjectCost, 2) == 0 ? 'N/A' : '$' . number_format($totalProjectCost, 2); ?></span> / <span class="text-blue-700 font-bold"><?= number_format($db_project['total_cost'], 2) == 0 ? 'N/A' : '$' . number_format($db_project['total_cost'], 2); ?></span></p>
            <p class="text-gray-600 mt-2 text-lg">Total Tasks: <span class="text-blue-700 font-bold"><?= $tasks_res['total'] ?></span></p>
            <div class="flex flex-col mt-4 space-y-2 text-sm text-gray-600">
                <span><i class="fas fa-calendar-alt"></i> <strong>Created On:</strong>
                    <?= formatDate($project['DATE_CREATE']) ?>
                </span>
                <span><i class="fas fa-calendar-alt"></i> <strong>Last Updated:</strong>
                    <?= formatDate($project['DATE_UPDATE']) ?>
                </span>

                <span><i class="fas fa-calendar-alt"></i> <strong>Start Date:</strong> <?= $project['FORMATTED_PROJECT_DATE_START'] ?></span>
                <span><i class="fas fa-calendar-alt"></i> <strong>End Date:</strong> <?= $project['FORMATTED_PROJECT_DATE_FINISH'] ?></span>
            </div>
        </div>
    </div>


    <!-- Tasks Section -->
    <div class="text-center mb-6">
        <h3 class="text-3xl font-semibold text-blue-700">Tasks</h3>
        <p class="text-gray-500 mt-1">Tasks assigned to this project</p>
        <button class="mt-4 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg shadow-lg hover:bg-blue-700 transform hover:-translate-y-1 transition-all" onclick="toggleAddTaskModal()">
            <i class="fas fa-plus"></i> Add Task
        </button>
    </div>

    <!-- Task Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($tasks as $task): ?>
            <div class="bg-white shadow-md rounded-lg p-6 hover:bg-blue-50 transition-all transform hover:-translate-y-1 hover:shadow-lg">
                <a href="task.php?id=<?= $task['id']; ?>" class="hover:text-blue-500 text-xl font-semibold"><?= htmlspecialchars($task['title']); ?></a>
                <p class="text-gray-600 mt-2"><?= htmlspecialchars($task['description']); ?></p>
                <div class="mt-4 text-sm text-gray-500">
                    <p><i class="fas fa-calendar-day"></i> Created: <?= date_format(new DateTime($task['createdDate']), 'd/m/Y') ?></p>
                    <p><i class="fas fa-calendar-check"></i> Deadline: <?= isset($task['deadline']) ? date_format(new DateTime($task['deadline']), 'd/m/Y') : 'N/A'; ?></p>
                </div>
                <span class="mt-4 self-start px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-700">
                    <?= getStatusText($task['status']); ?>
                </span>
                <form method="post" action="./data/delete_task.php" class="mt-4">
                    <input type="hidden" name="task_id" value="<?= htmlspecialchars($task['id']); ?>">
                    <input type="hidden" name="source" id="source" value="project.php?id=<?= htmlspecialchars($project_id); ?>">
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-700 transition-all">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Task Modal -->
    <div id="taskModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden transition-all duration-300 z-50">
        <div class="bg-white rounded-lg w-3/4 lg:w-1/2 p-6 shadow-lg">
            <h3 class="text-xl font-bold mb-4 text-gray-800">Add New Task</h3>
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
                        <label for="groupId" class="block text-gray-700">Project</label>
                        <input type="hidden" name="groupId" id="groupId" value="<?= htmlspecialchars($project_id); ?>">
                        <input type="hidden" name="source" id="source" value="project.php?id=<?= htmlspecialchars($project_id); ?>">
                        <input type="text" class="w-full px-3 py-2 border rounded-lg" value="<?= htmlspecialchars($project['NAME']); ?>" readonly>
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
                    <button type="button" class="px-4 py-2 bg-red-500 text-white rounded-lg mr-2 hover:bg-red-700" onclick="toggleAddTaskModal()">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save Task</button>
                </div>

            </form>
        </div>
    </div>

    <!-- Risks Section -->
    <div class="text-center mb-6">
        <h3 class="text-3xl font-semibold text-blue-700">Risks</h3>
        <p class="text-gray-500 mt-1">Risks assigned to this project</p>
        <button class="mt-4 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg shadow-lg hover:bg-blue-700 transform hover:-translate-y-1 transition-all" onclick="toggleAddRiskModal()">
            <i class="fas fa-plus"></i> Add Risk
        </button>
    </div>

    <!-- Risk Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($risks as $risk): ?>
            <div class="bg-white shadow-md rounded-lg p-6 hover:bg-blue-50 transition-all transform hover:-translate-y-1 hover:shadow-lg">
                <a href="risk.php?id=<?= $risk['id']; ?>" class="hover:text-blue-500 text-xl font-semibold"><?= htmlspecialchars($risk['title']); ?></a>
                <p class="text-gray-600 mt-2"><?= htmlspecialchars($risk['ufCrm15Description']); ?></p>
                <div class="mt-4 text-sm text-gray-500">
                    <p><i class="fas fa-exclamation-triangle"></i> Probability of Occurrence: <?= $risk['ufCrm15ProbabilityOfOccurence']; ?></p>
                    <p><i class="fas fa-chart-line"></i> Risk Impact: <?= $risk['ufCrm15RiskImpact']; ?></p>
                    <p><i class="fas fa-flag-checkered"></i> Risk Priority: <?= $risk['ufCrm15RiskPriority']; ?></p>
                </div>

                <div class="mt-4 self-start px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-700 w-fit">
                    <?= getRiskStatusText($risk['ufCrm15RiskStatus']); ?>
                </div>
                <div class="mt-4 self-start px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-700 w-fit">
                    <?= getRiskCategoryText($risk['ufCrm15Category']); ?>
                </div>
                <form method="post" action="./data/delete_risk.php" class="mt-4">
                    <input type="hidden" name="risk_id" value="<?= htmlspecialchars($risk['id']); ?>">
                    <input type="hidden" name="source" id="source" value="project.php?id=<?= htmlspecialchars($project_id); ?>">
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-700 transition-all">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Risk Modal -->
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
                    <input type="hidden" name="projectId" id="projectId" value="<?= htmlspecialchars($project_id); ?>">
                    <input type="hidden" name="source" id="source" value="project.php?id=<?= htmlspecialchars($project_id); ?>">
                    <input type="text" class="w-full px-3 py-2 border rounded-lg" value="<?= htmlspecialchars($project['NAME']); ?>" readonly>
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
                    <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-lg mr-2" onclick="toggleAddRiskModal()">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save Risk</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    function toggleAddTaskModal() {
        const modal = document.getElementById('taskModal');
        modal.classList.toggle('hidden');
    }

    function toggleAddRiskModal() {
        const modal = document.getElementById('riskModal');
        modal.classList.toggle('hidden');
    }
</script>


<?php include('includes/footer.php'); ?>