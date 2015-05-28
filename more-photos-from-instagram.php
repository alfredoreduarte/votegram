<?php
	//this file fetches more photos from instagram, filters out the ones you've already approved/discarded, and serves them to the backend for moderation
	include_once('./_config.php');

	//get already approved photos
	$sql = "select photo_id from instagram_photos";
	$result = $conn->query($sql);
	$already_saved_photos = array();
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$already_saved_photos[] = $row["photo_id"];
		}
	} else {
		echo "0 results";
	}

	$sql2 = "select photo_id from canceled";
	$result2 = $conn->query($sql2);
	$already_canceled_photos = array();
	if ($result2->num_rows > 0) {

		while($row = $result2->fetch_assoc()) {
			$already_canceled_photos[] = $row["photo_id"];
		}
	} else {
		echo "0 results";
	}

	$conn->close();
	//GET SAVED PHOTOS

    /**
     * Instagram PHP API
     */

	require_once 'Instagram.php';
	use MetzWeb\Instagram\Instagram;

	$instagram = new Instagram(array(
		'apiKey'      => $instagram_api_key,
		'apiSecret'   => $instagram_api_secret,
		'apiCallback' => $instagram_api_callback
	));
	
	$maxID = $_GET['max_id'];
	$clientID = $instagram->getApiKey();

	// Receive new data
	$media = $instagram->getTagMedia($tag,$auth=false,array('max_tag_id'=>$maxID));

	// Collect everything for json output
	$items = array();

	foreach ($media->data as $data) {
		//if(in_array($data->id, $already_saved_photos)){
		if(!in_array($data->id, $already_saved_photos) && !in_array($data->id, $already_canceled_photos)){
			$item = array();
			$item[0] = $data->images->thumbnail->url;
			$item[1] = $data->user->full_name;
			$item[2] = $data->caption->text;
			$item[3] = $data->id;
			$items[] = $item;
		}else{
			
		}
	}

	echo json_encode(array(
		'next_id' => $media->pagination->next_max_id,
		'items'  => $items
	));
?>