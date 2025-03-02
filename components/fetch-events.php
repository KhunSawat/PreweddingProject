<?php
header('Content-Type: application/json; charset=utf-8');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kawaii";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to utf8mb4
mysqli_set_charset($conn, "utf8mb4");

// Fetch data from database
$sql = "SELECT
booking_h.BookingH_No,
booking_h.B_DateTime_S,
booking_h.B_DateTime_F,
booking_h.Customer_No,
customers.cus_Name
FROM
booking_h
INNER JOIN customers ON customers.customer_No = booking_h.Customer_No";

$result = mysqli_query($conn, $sql);

$events = array();
while ($row = mysqli_fetch_assoc($result)) {
    $events[] = array(
        'title' => $row['BookingH_No'],
        'start' => $row['B_DateTime_S'],
        'cus_Name' => $row['cus_Name'],
        'end'   => date('Y-m-d', strtotime($row['B_DateTime_F'] . ' +1 day')),
        'extendedProps' => array(
            'BookingH_No' => $row['BookingH_No']
        )
    );
}

mysqli_close($conn);

// Convert to JSON and output
echo json_encode($events);
?>
