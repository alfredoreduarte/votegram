<?php
if (!empty($_POST)){
	include_once('./_config.php');
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//
	$target_dir = $images_directory . date_timestamp_get(date_create()) . '-';
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	// Check if image file is a actual image or fake image
	if(isset($_POST["submit"])) {
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		if($check !== false) {
			//echo "File is an image - " . $check["mime"] . ".";
			$uploadOk = 1;
		} else {
			//echo "File is not an image.";
			$uploadOk = 0;
		}
	}
	// Check if file already exists
	if (file_exists($target_file)) {
		//echo "Sorry, file already exists.";
		$uploadOk = 0;
	}
	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 1000000) {
		//echo "Sorry, your file is too large.";
		$uploadOk = 0;
	}
	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
		//echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		$uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		//echo "Sorry, your file was not uploaded.";
		die();
	// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			//echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
			//
			$id = $_POST['photo_id'];
			$url = $local_index_url . $target_file;
			$name = $conn->real_escape_string($_POST['username']);
			$caption = $conn->real_escape_string($_POST['caption']);

			$sql = "INSERT INTO instagram_photos (photo_id, photo_url, username, caption, approved_by) VALUES ('".$id."', '".$url."', '".$name."', '".$caption."', 'self_upload')";

			if ($conn->query($sql) === TRUE) {
				$success = true;
				//echo "New record created successfully";
				//
			} else {
				//echo "Error: " . $sql . "<br>" . $conn->error;
			}
			//
		} else {
			//echo "Sorry, there was an error uploading your file.";
		}
	}
	//
	$conn->close();?>
	<!DOCTYPE html>
	<html lang="en">
	<head>
		<script type="text/javascript">
			window.location.replace("<?php echo $photos_index_url; ?>");
		</script>
	</head>
	<body>
		Redirigiendo
	</body>
	</html>
<?php } ?>