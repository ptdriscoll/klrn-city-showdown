<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $to_district = intval($_POST['to_district']);
    $zip = substr(htmlspecialchars($_POST['zip_code']), 0, 5);

	$config = require(__DIR__ . '/../config.php');
	require('../includes/database-conn.php');

	//save to db and close connection
	$stmt = $conn->prepare("INSERT INTO votes (to_district, zip) VALUES (?, ?)");
	$stmt->bind_param('is', $to_district, $zip);
	$stmt->execute();
    //echo $stmt->error;
  
	$stmt->close();
	$conn->close();

	//for testing
	$test_response = '';
	$test_response = "&to={$to_district}&zip={$zip}"; 
	//echo '<br>'.$test_response; //for when not using post back redirect

	//post back
	$params = '?response=thank-you'.$test_response;  
	$is_localhost = in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']);
	$postback = $is_localhost ? $config['vote_url_dev'] : $config['vote_url_prod'];
	//echo $postback;
	header('Location: '.$postback.$params);
	exit(); 
}
?>