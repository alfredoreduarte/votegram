<?php
if (!empty($_POST)){
	//this file fetches approved photos from database and serves them to index.php
	include_once('./_config.php');
	$sql = "select * from instagram_photos where photo_id like '%".$_POST['user_id']."%' limit 1";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		$items = array();
		while($row = $result->fetch_assoc()) {
			if(!empty($row["photo_url"])){
				echo 'already_uploaded';
			}
		}
	} else {
	    echo "empty";
	}
	$conn->close();
}
?>