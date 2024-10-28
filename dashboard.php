<?php
include('includes/header.php');
include('includes/sidebar.php');
?>

<div class="p-10 flex-1">
    <h1 class="text-3xl font-bold mb-6">Dashboard</h1>

    <!-- Loading Animation -->
    <div id="loading" class="flex justify-center items-center h-[calc(100vh-200px)]">
        <span class="loader"></span>
    </div>



    <div id="dashboard-content" class="hidden">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            <!-- KPI Cards will be inserted here -->
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-10">
            <div class="bg-white shadow-lg rounded-lg p-4">
                <h2 class="text-xl font-semibold mb-2">Project Status</h2>
                <canvas id="projectStatusChart"></canvas>
            </div>
            <div class="space-y-4">
                <div class="bg-white shadow-lg rounded-lg p-4">
                    <h2 class="text-xl font-semibold mb-2">Risk Category</h2>
                    <canvas id="riskCategoryChart"></canvas>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-4">
                    <h2 class="text-xl font-semibold mb-2">Quality Status</h2>
                    <canvas id="qualityStatusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Risks and Qualities Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="bg-white shadow-lg rounded-lg p-4">
                <h2 class="text-xl font-semibold mb-2">Risks Overview</h2>
                <ul id="risks-overview"></ul>
            </div>
            <div class="bg-white shadow-lg rounded-lg p-4">
                <h2 class="text-xl font-semibold mb-2">Quality Management</h2>
                <ul id="quality-management"></ul>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Function to fetch data and update dashboard
    async function loadDashboardData() {
        const response = await fetch('./data/fetch_dashboard_data.php');
        const data = await response.json();

        console.log(data);

        // Update KPIs
        const kpis = [
            ["Total Projects", data.totalProjects, 'text-blue-600', 'fas fa-folder-open'],
            ["Total Tasks", data.totalTasks, 'text-blue-600', 'fas fa-tasks'],
            ["Overdue Tasks", data.overdueTasks, 'text-red-600', 'fas fa-exclamation-circle'],
            ["Tasks In Progress", data.inProgressTasks, 'text-yellow-400', 'fas fa-spinner'],
            ["Risk Management", data.totalRisks, 'text-blue-600', 'fas fa-exclamation-triangle'],
            ["Resolved Risks", data.resolvedRisks, 'text-green-600', 'fas fa-check-circle'],
            ["Quality Management", data.totalQualities, 'text-blue-600', 'fas fa-thumbs-up'],
            ["Qualities In Correction", data.inCorrectionQualities, 'text-blue-600', 'fas fa-edit'],
            ["Total Projects Budget", new Intl.NumberFormat('en-US').format(data.totalProjectsBudget) + ' for ' + data.totalProjects + ' projects', 'text-blue-600', 'fas fa-dollar-sign'],
        ];
        const projectCounts = [
            data.activeProjectsCount,
            data.inactiveProjectsCount
        ];
        const risksData = data.risksData;
        const qualitiesData = data.qualitiesData;

        const dashboardRisks = data.dashboardRisks;
        const dashboardQualities = data.dashboardQualities;

        const kpiContainer = document.querySelector('#dashboard-content .grid:first-child');
        kpis.forEach(kpi => {
            const colorClass = kpi[2];
            const iconClass = kpi[3];

            kpiContainer.innerHTML += `
                <div class='bg-white shadow-lg rounded-lg p-5 hover:shadow-xl transition-shadow duration-300'>
                    <div class='flex items-center mb-3'>
                        <i class='${iconClass} text-3xl ${colorClass} mr-2'></i>
                        <h2 class='text-xl font-semibold'>${kpi[0]}</h2>
                    </div>
                    <p class='text-2xl font-bold ${colorClass}'>${kpi[1]}</p>
                </div>`;
        });


        // Update Risks and Qualities
        const risksOverviewContainer = document.querySelector('#risks-overview');
        const qualityManagementContainer = document.querySelector('#quality-management');

        function formatDate(dateString) {
            const date = new Date(dateString); // Create a Date object from the date string
            const options = {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            }; // Options for formatting
            return date.toLocaleDateString('en-GB', options); // Format date as dd/mm/yyyy
        }

        dashboardRisks.forEach(risk => {
            risksOverviewContainer.innerHTML += `
                <li class='mb-3'>
                    <p class="font-semibold">${risk.title}</p>
                    <p>Impact: ${risk.ufCrm15RiskImpact}/5 | Probability: ${risk.ufCrm15ProbabilityOfOccurence}/5</p>
                    <p>Priority: ${risk.ufCrm15RiskPriority}</p>
                    <p class='mb-2'>Status: <span class="px-3 py-1 rounded-full text-sm font-semibold ${risk.statusClass}">
                        ${risk.statusText}
                    </span></p>
                    <p>Category: <span class="px-3 py-1 rounded-full text-sm font-semibold ${risk.categoryClass}">
                        ${risk.categoryText}
                    </span></p>
                </li>
                `;
        });
        dashboardQualities.forEach(quality => {
            qualityManagementContainer.innerHTML += `
                <li class="mb-3">
                    <p class="font-semibold">${quality.title}</p>
                    <p>Criteria: ${quality.ufCrm17QualityCriteria}</p>
                    <p>Standards: ${quality.ufCrm17QualityStandards}</p>
                    <p>Audits: ${formatDate(quality.ufCrm17QualityAudits)}</p>
                </li>
                `;
        });



        // Update Charts
        new Chart(document.getElementById('projectStatusChart'), {
            type: 'pie',
            data: {
                labels: ['Active', 'Inactive'],
                datasets: [{
                    data: projectCounts,
                    backgroundColor: ['#36A2EB', '#FF6384']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    title: {
                        display: true,
                        text: 'Project Status Distribution'
                    }
                }
            }
        });

        new Chart(document.getElementById('riskCategoryChart'), {
            type: 'bar',
            data: {
                labels: ['Total Risks', 'Technical Risks', 'Financial Risks', 'Operational Risks'],
                datasets: [{
                    label: 'Risk Count',
                    data: [risksData.total, risksData.technical, risksData.financial, risksData.operational],
                    backgroundColor: [
                        '#4caf50', // Green for Total Risks
                        '#ff9800', // Orange for Technical Risks
                        '#f44336', // Red for Financial Risks
                        '#2196f3' // Blue for Operational Risks
                    ],
                    hoverBackgroundColor: [
                        '#66bb6a',
                        '#ffb74d',
                        '#e57373',
                        '#64b5f6'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Risk Categories'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        new Chart(document.getElementById('qualityStatusChart'), {
            type: 'bar',
            data: {
                labels: ['Total Qualities', 'Complaint Qualities', 'Non-Complaint Qualities', 'In Correction Qualities', ],
                datasets: [{
                    label: 'Quality Count',
                    data: [qualitiesData.total, qualitiesData.complaint, qualitiesData.nonComplaint, qualitiesData.inCorrection],
                    backgroundColor: [
                        '#4caf50', // Green for Total Qualities
                        '#f44336', // Red for Complaint Qualities
                        '#ff9800', // Orange for Non-Complaint Qualities
                        '#2196f3' // Blue for In-Correction Qualities
                    ],
                    hoverBackgroundColor: [
                        '#66bb6a',
                        '#e57373',
                        '#ffb74d',
                        '#64b5f6'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Quality Status'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });



        // Show dashboard content
        document.getElementById('loading').style.display = 'none';
        document.getElementById('dashboard-content').classList.remove('hidden');
    }

    // Load dashboard data on page load
    window.onload = loadDashboardData;
</script>

<style>
    .loader {
        border: 4px solid #f3f3f3;
        /* Light grey */
        border-top: 4px solid #3498db;
        /* Blue */
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        text-align: center;
    }

    .loader {
        width: 48px;
        height: 48px;
        border: 5px solid #f3f3f3;
        border-bottom: 5px solid rgb(31 41 55);
        border-radius: 50%;
        display: inline-block;
        box-sizing: border-box;
        animation: rotation 1s linear infinite;
        text-align: center;
    }

    @keyframes rotation {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>