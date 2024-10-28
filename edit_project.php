<?php
require_once('config/database.php');
include('includes/header.php');
include('includes/sidebar.php');
include('data/fetch_projects.php');

$project_id = $_GET['id'];
$project = fetchProject($project_id);

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

<div class="p-10 flex-1">
    <div class="bg-white rounded-lg w-full max-w-4xl p-6 mx-auto">
        <h3 class="text-2xl font-bold mb-4">Edit Project - <?= $project['NAME']; ?></h3>
        <form method="post" action="./data/update_project.php" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="text" name="project_id" value="<?= $project_id; ?>" hidden>
            <div class="mb-4">
                <label for="exampleInputTitle" class="block text-gray-700">Title</label>
                <input value="<?= $project['NAME']; ?>" type="text" class="w-full px-3 py-2 border rounded-lg" id="exampleInputTitle" name="title" required placeholder="Enter project title">
            </div>
            <div class="mb-4">
                <label for="exampleInputDescription" class="block text-gray-700">Description</label>
                <input value="<?= $project['DESCRIPTION']; ?>" type="text" class="w-full px-3 py-2 border rounded-lg" id="exampleInputDescription" name="description" required placeholder="Enter project description">
            </div>
            <div class="mb-4">
                <label for="visible" class="block text-gray-700">Visible</label>
                <select name="visible" id="visible" class="w-full px-3 py-2 border rounded-lg" required>
                    <option value="Y" <?= $project['VISIBLE'] == 'Y' ? 'selected' : '' ?>>Yes</option>
                    <option value="N" <?= $project['VISIBLE'] == 'N' ? 'selected' : '' ?>>No</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="opened" class="block text-gray-700">Opened</label>
                <select name="opened" id="opened" class="w-full px-3 py-2 border rounded-lg" required>
                    <option value="Y" <?= $project['OPENED'] == 'Y' ? 'selected' : '' ?>>Yes</option>
                    <option value="N" <?= $project['OPENED'] == 'N' ? 'selected' : '' ?>>No</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="permission" class="block text-gray-700">Permission</label>
                <select name="permission" id="permission" class="w-full px-3 py-2 border rounded-lg" required>
                    <option value="A" <?= $project['INITIATE_PERMS'] == 'A' ? 'selected' : '' ?>>Group Owner Only</option>
                    <option value="E" <?= $project['INITIATE_PERMS'] == 'E' ? 'selected' : '' ?>>Group Owner and Moderator</option>
                    <option value="K" <?= $project['INITIATE_PERMS'] == 'K' ? 'selected' : '' ?>>All Group Members</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="closed" class="block text-gray-700">Closed</label>
                <select name="closed" id="closed" class="w-full px-3 py-2 border rounded-lg" required>
                    <option value="Y" <?= $project['CLOSED'] == 'Y' ? 'selected' : '' ?>>Yes</option>
                    <option value="N" <?= $project['CLOSED'] == 'N' ? 'selected' : '' ?>>No</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="project" class="block text-gray-700">Project Type</label>
                <select name="project" id="project" class="w-full px-3 py-2 border rounded-lg" required>
                    <option value="Y" <?= $project['PROJECT'] == 'Y' ? 'selected' : '' ?>>Project</option>
                    <option value="N" <?= $project['PROJECT'] == 'N' ? 'selected' : '' ?>>Workgroup</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="exampleInputstartDate" class="block text-gray-700">Start Date</label>
                <input value="<?= (new DateTime($project['PROJECT_DATE_START']))->format('Y-m-d'); ?>" class="w-full px-3 py-2 border rounded-lg" type="date" name="startDate" id="exampleInputstartDate" required>
            </div>
            <div class="mb-4">
                <label for="exampleInputEndDate" class="block text-gray-700">End Date</label>
                <input value="<?= (new DateTime($project['PROJECT_DATE_FINISH']))->format('Y-m-d'); ?>" class="w-full px-3 py-2 border rounded-lg" type="date" name="endDate" id="exampleInputEndDate">
            </div>
            <div class="mb-4">
                <label for="projectCost" class="block text-gray-700">Project Cost</label>
                <input value="<?= isset($db_project['total_cost']) ? $db_project['total_cost'] : 0; ?>" class="w-full px-3 py-2 border rounded-lg" type="number" name="projectCost" id="projectCost" placeholder="Enter project cost">
            </div>
            <div class="mb-4">
                <label for="projectStatus" class="block text-gray-700">Project Status</label>
                <select name="projectStatus" id="projectStatus" class="w-full px-3 py-2 border rounded-lg">
                    <option value="INITIATION" <?= empty($db_project['status']) ? 'selected' : ($db_project['status'] == 'INITIATION' ? 'selected' : '') ?>>Initiation</option>
                    <option value="PLANNING" <?= $db_project['status'] == 'PLANNING' ? 'selected' : '' ?>>Planning</option>
                    <option value="EXECUTION" <?= $db_project['status'] == 'EXECUTION' ? 'selected' : '' ?>>Execution</option>
                    <option value="MONITORING AND CONTROL" <?= $db_project['status'] == 'MONITORING AND CONTROL' ? 'selected' : '' ?>>Monitoring and Control</option>
                    <option value="CLOSING" <?= $db_project['status'] == 'CLOSING' ? 'selected' : '' ?>>Closing</option>
                </select>
            </div>
            <div class="flex justify-end col-span-1 md:col-span-2">
                <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-lg mr-2" onclick="window.history.back()">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Update Project</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal for Adding Project -->




<?php include('includes/footer.php'); ?>