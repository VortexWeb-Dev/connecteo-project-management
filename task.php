<?php
include('includes/header.php');
include('includes/sidebar.php');
include('data/fetch_tasks.php');
include('utils/index.php');

$task_id = $_GET['id'];
$task = fetchTask($task_id);
?>

<div class="p-10 flex-1">
    <!-- Task Title and Overview -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800"><?= htmlspecialchars($task['title']); ?></h1>
        <p class="text-gray-600 mt-2"><?= htmlspecialchars($task['description'] ?: 'No description available'); ?></p>
    </div>

    <!-- Task Details Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- General Info Card -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">General Information</h2>
            <ul class="text-gray-600 space-y-2">
                <li><strong>Task ID:</strong> <?= htmlspecialchars($task['id']); ?></li>
                <li><strong>Project ID:</strong> <?= htmlspecialchars($task['groupId']); ?></li>
                <li><strong>Task Cost:</strong> <?= htmlspecialchars($task['ufAuto586224948951']); ?></li>
                <li><strong>Material Resources:</strong> <?= htmlspecialchars($task['ufAuto886998768121']); ?></li>
                <li><strong>Status:</strong> 
                    <span class="px-3 py-1 rounded-full text-sm font-semibold <?= getStatusBadgeClass($task['status']); ?>">
                        <?= getStatusText($task['status']); ?>
                    </span>
                </li>
                <li><strong>Priority:</strong> <?= $task['priority'] == 1 ? 'High' : 'Normal'; ?></li>
                <li><strong>Comments Count:</strong> <?= htmlspecialchars($task['commentsCount']); ?></li>
            </ul>
        </div>

        <!-- Dates and Deadlines Card -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Dates & Deadlines</h2>
            <ul class="text-gray-600 space-y-2">
                <li><strong>Deadline:</strong> <?= $task['deadline'] ? date_format(new DateTime($task['deadline']), 'd/m/Y') : 'No deadline'; ?></li>
                <li><strong>Start Date:</strong> <?= date_format(new DateTime($task['startDatePlan']), 'd/m/Y'); ?></li>
                <li><strong>End Date:</strong> <?= date_format(new DateTime($task['endDatePlan']), 'd/m/Y'); ?></li>
                <li><strong>Created On:</strong> <?= date_format(new DateTime($task['createdDate']), 'd/m/Y'); ?></li>
                <li><strong>Last Updated:</strong> <?= date_format(new DateTime($task['changedDate']), 'd/m/Y'); ?></li>
                <li><strong>Status Changed:</strong> <?= date_format(new DateTime($task['statusChangedDate']), 'd/m/Y'); ?></li>
            </ul>
        </div>

        <!-- Assignees and Auditors Card -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Assignees & Auditors</h2>
            <ul class="text-gray-600 space-y-2">
                <li><strong>Creator:</strong> 
                    <a href="<?= 'https://connecteo.bitrix24.in' . $task['creator']['link']; ?>" class="text-blue-500 hover:underline">
                        <?= htmlspecialchars($task['creator']['name']); ?>
                    </a>
                </li>
                <li><strong>Responsible:</strong> 
                    <a href="<?= 'https://connecteo.bitrix24.in' . $task['responsible']['link']; ?>" class="text-blue-500 hover:underline">
                        <?= htmlspecialchars($task['responsible']['name']); ?>
                    </a>
                </li>
                <?php if (!empty($task['auditorsData'])): ?>
                    <li><strong>Auditors:</strong></li>
                    <ul class="ml-4 space-y-1">
                        <?php foreach ($task['auditorsData'] as $auditor): ?>
                            <li>
                                <a href="<?= 'https://connecteo.bitrix24.in' . $auditor['link']; ?>" class="flex items-center space-x-2 text-blue-500 hover:underline">
                                    <img src="<?= $auditor['icon']; ?>" alt="<?= htmlspecialchars($auditor['name']); ?>" class="w-8 h-8 rounded-full">
                                    <span><?= htmlspecialchars($auditor['name']); ?> - <?= htmlspecialchars($auditor['workPosition']); ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <li>No auditors assigned</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <!-- Additional Information Section -->
    <div class="mt-6 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Additional Information</h2>
        <ul class="text-gray-600 space-y-2">
            <li><strong>Multi-tasking:</strong> <?= $task['multitask'] === 'Y' ? 'Yes' : 'No'; ?></li>
            <li><strong>Time Estimate:</strong> <?= $task['timeEstimate'] ? gmdate("H:i:s", $task['timeEstimate']) : 'Not estimated'; ?></li>
            <li><strong>Comments Available:</strong> <?= $task['commentsCount'] > 0 ? 'Yes' : 'No'; ?></li>
            <li><strong>Allow Deadline Change:</strong> <?= $task['allowChangeDeadline'] === 'Y' ? 'Yes' : 'No'; ?></li>
            <li><strong>Task Control:</strong> <?= $task['taskControl'] === 'Y' ? 'Enabled' : 'Disabled'; ?></li>
        </ul>
    </div>
</div>

<?php include('includes/footer.php'); ?>
