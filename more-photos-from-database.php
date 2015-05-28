<?php
	//this file fetches approved photos from database and serves them to index.php
	include_once('./_config.php');
	$sql = "select * from instagram_photos where id < ".$_POST['offset']." order by id DESC limit 6";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		$items = array();
		while($row = $result->fetch_assoc()) {
			if(!empty($row["photo_url"])){
				$item = array();
				$item[0] = $row["photo_url"];
				$item[1] = $row["username"];
				$item[2] = $row["caption"];
				$item[3] = $row["id"];
				$item[4] = $row["votes_count"];
				$items[] = $item;
			}
		}
		echo json_encode(array(
			'next_id' => $media->pagination->next_max_id,
			'items'  => $items
		));
	} else {
	    echo "empty";
	}
	$conn->close();
?>