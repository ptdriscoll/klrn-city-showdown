<?php
session_start(); 
if(!ISSET($_SESSION['is_logged']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
    header('location: ./'); 
    die(); 
}

require('conn.php');
$contestants_arr = include('contestants.php');

//send headers to disable browser caching
$now =  gmdate("D, d M Y H:i:s") . " GMT";
header("Expires: $now");
header("Last-Modified: $now");
header("Pragma: no-cache");
header("Cache-Control: no-cache, must-revalidate");

//send headers to force download of csv file
header('Content-Type: application/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="City-Showdown-Results.csv";'); 
echo pack("CCC",0xef,0xbb,0xbf); //tells Excel it's UTF-8 BOM

//output csv
$output = fopen('php://output', 'w');
fputcsv($output, ['From District', 'To District', 'Contestant', 'Zip Code']);

//get data
$contestants_arr = include('contestants.php');
$sql = 'SELECT from_district, to_district, zip FROM votes ORDER By from_district, to_district';
$result = $conn->query($sql);

//per row, splice in contestant: ['from_district', to_district', 'contestant', 'zip']
while($row = $result->fetch_assoc()) {
    $row = [$row['from_district'], $row['to_district'], $contestants_arr[$row['to_district']], $row['zip']];
    fputcsv($output, $row);
}

$conn->close(); 
fclose($output);
exit();