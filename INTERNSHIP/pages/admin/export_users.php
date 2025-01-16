<?php
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=users.csv');

$conn = new mysqli("localhost", "root", "", "virtual_marketplace");
$sql = "SELECT name, email, role FROM users";
$result = $conn->query($sql);

$output = fopen('php://output', 'w');
fputcsv($output, ['Name', 'Email', 'Role']);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
}

fclose($output);
$conn->close();
