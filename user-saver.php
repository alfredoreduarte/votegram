<?php
if (!empty($_POST)){

	include_once('./_config.php');
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$facebook_id = $_POST['facebook_id'];
	$name = $_POST['name'];
	$email = $_POST['email'];

	$sql_find_user = "select * from users where facebook_id = ".$facebook_id;
	$result = $conn->query($sql_find_user);

	if ($result->num_rows > 0) {
		echo 'ya existe';
	}else{
		echo 'no existe';
		$sql = "INSERT INTO users (facebook_id, name, email) VALUES ('".$facebook_id."', '".$name."', '".$email."')";
		if ($conn->query($sql) === TRUE) {
			$success = true;
			echo "New record created successfully";
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
	$conn->close();
}
?>