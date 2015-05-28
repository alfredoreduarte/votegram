<?php
	date_default_timezone_set('America/Los_Angeles');
	$servername = "127.0.0.1";
	$username = "root";
	$password = "";
	$dbname = "votegram";

	$photos_index_url = 'http://localhost/athletic-instagram/photos.php';

	$local_index_url = 'http://localhost/athletic-instagram/';


	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$images_directory = 'images/clients/';

	$tag = 'dogstagram';
	$facebook_app_id = '935898406430561';
	$instagram_api_key = '30fb59038f044c86b41e2defdee681c3';
	$instagram_api_secret = '3856cc79f08c476084556bb555487b20';
	$instagram_api_callback = 'http://alfredore.com/tigoinstagram';

	//print_r($conn);
?>