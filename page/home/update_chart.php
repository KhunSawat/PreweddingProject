<?php
include("../../config/dbcon.php");
$year = $_POST['year'] ?? date("Y");

$sql_count = "SELECT MONTH(B_DateTime_S) AS month, COUNT(*) AS job_count
FROM booking_h
WHERE YEAR(B_DateTime_S) = '$year'
GROUP BY MONTH(B_DateTime_S)";
$result = mysqli_query($conn, $sql_count);

$jobCounts = array_fill(1, 12, 0);
while ($row = mysqli_fetch_assoc($result)) {
    $month = (int)$row['month'];
    $jobCounts[$month] = (int)$row['job_count'];
}

$response = [
    'year' => $year + 543,
    'jobCounts' => array_values($jobCounts),
];

echo json_encode($response);
