<?php
session_start(); 
if(!ISSET($_SESSION['is_logged']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
    header('location: ./'); 
    die(); 
}

require('../includes/database-conn.php');
$contestants_arr = include('../includes/contestants-list.php');

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

//setup csv header
$contestants_arr = include('../includes/contestants-list.php');
$header_row = ['From District', 'Zip Code'];
$to_districts = []; 
foreach($contestants_arr as $key=>$val) {
    array_push($to_districts, $key.' - '.$val);
}
array_splice($header_row, 1, 0, $to_districts);

//output csv
$output = fopen('php://output', 'w');
fputcsv($output, $header_row);

//get data
$sql = 'SELECT from_district, to_district, zip FROM votes ORDER By from_district, to_district';
$result = $conn->query($sql);

//per row, splice in contestant: ['from_district', [to_districts], 'zip']
while($next = $result->fetch_assoc()) {
    $row = [$next['from_district'], $next['zip']];
    $to_districts_row = array_fill(0, 10, null);
    $to_districts_row[intval($next['to_district'])-1] = 1;
    array_splice($row, 1, 0, $to_districts_row);
    fputcsv($output, $row);
}

$conn->close(); 
fclose($output);
exit();