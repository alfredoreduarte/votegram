<?php
if (!empty($_POST)){

	include_once('./_config.php');
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$id = $_POST['photo_id'];
	$url = $_POST['photo_url'];
	$name = $conn->real_escape_string($_POST['username']);
	$caption = $conn->real_escape_string($_POST['caption']);
	$approved_by = $conn->real_escape_string($_POST['approved_by']);

	$sql = "INSERT INTO instagram_photos (photo_id, photo_url, username, caption, approved_by) VALUES ('".$id."', '".$url."', '".$name."', '".$caption."', '".$approved_by."')";

	if ($conn->query($sql) === TRUE) {
		$success = true;
		echo "New record created successfully";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
	$conn->close();

	
}
?>