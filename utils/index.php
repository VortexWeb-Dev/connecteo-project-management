<?php
// Functions for task status badge and text
function getStatusBadgeClass($status)
{
    switch ($status) {
        case 1:
            return 'bg-gray-200 text-gray-800'; // New
        case 2:
            return 'bg-yellow-100 text-yellow-700'; // Pending
        case 3:
            return 'bg-blue-100 text-blue-700'; // In Progress
        case 4:
            return 'bg-teal-100 text-teal-700'; // Supposedly Completed
        case 5:
            return 'bg-green-100 text-green-700'; // Completed
        case 6:
            return 'bg-gray-100 text-gray-600'; // Deferred
        case 7:
            return 'bg-red-100 text-red-700'; // Declined
        default:
            return 'bg-gray-300 text-gray-700'; // Unknown
    }
}
function getStatusText($status)
{
    switch ($status) {
        case 1:
            return 'New';
        case 2:
            return 'Pending';
        case 3:
            return 'In Progress';
        case 4:
            return 'Supposedly Completed';
        case 5:
            return 'Completed';
        case 6:
            return 'Deferred';
        case 7:
            return 'Declined';
        default:
            return 'Unknown';
    }
}

function getRiskStatusBadgeClass($status)
{
    switch ($status) {
        case 889:
            return 'bg-gray-200 text-gray-800'; // Identified
        case 891:
            return 'bg-blue-100 text-blue-700'; // In Progress
        case 893:
            return 'bg-green-100 text-green-700'; // Resolved
        default:
            return 'bg-gray-300 text-gray-700'; // Unknown
    }
}

function getRiskStatusText($status)
{
    switch ($status) {
        case 889:
            return 'Identified';
        case 891:
            return 'In Progress';
        case 893:
            return 'Resolved';
        default:
            return 'Unknown';
    }
}

function getRiskCategoryBadgeClass($status)
{
    switch ($status) {
        case 883:
            return 'bg-gray-200 text-gray-800'; // Technical
        case 885:
            return 'bg-blue-100 text-blue-700'; // Financial
        case 887:
            return 'bg-green-100 text-green-700'; // Operational
        default:
            return 'bg-gray-300 text-gray-700'; // Unknown
    }
}

function getRiskCategoryText($status)
{
    switch ($status) {
        case 883:
            return 'Technical';
        case 885:
            return 'Financial';
        case 887:
            return 'Operational';
        default:
            return 'Unknown';
    }
}

function getQualityStatusBadgeClass($status)
{
    switch ($status) {
        case 895:
            return 'bg-gray-200 text-gray-800'; // Complaint
        case 897:
            return 'bg-blue-100 text-blue-700'; // Non-Complaint
        case 899:
            return 'bg-green-100 text-green-700'; // In Correction
        default:
            return 'bg-gray-300 text-gray-700'; // Unknown
    }
}

function getQualityStatusText($status)
{
    switch ($status) {
        case 895:
            return 'Complaint';
        case 897:
            return 'Non-Complaint';
        case 899:
            return 'In Correction';
        default:
            return 'Unknown';
    }
}

function formatDate($dateString)
{
    $months = [
        '01' => 'January',
        '02' => 'February',
        '03' => 'March',
        '04' => 'April',
        '05' => 'May',
        '06' => 'June',
        '07' => 'July',
        '08' => 'August',
        '09' => 'September',
        '10' => 'October',
        '11' => 'Novober',
        '12' => 'December',
    ];

    list($date, $time) = explode(' ', $dateString);
    list($day, $month, $year) = explode('/', $date);

    $formattedDate = sprintf('%d %s %d', $day, $months[$month], $year);

    return $formattedDate;
}
