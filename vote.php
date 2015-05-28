<?php
if (!empty($_POST)){

	include_once('./_config.php');
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$photo_id = $_POST['photo_id'];
	$voter_id = $_POST['user_id'];

	$sql_get_photo = "select votes_count from instagram_photos where id = ".$photo_id;
	$result = $conn->query($sql_get_photo);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$vote_date = date('Y-m-d h:i:s', time());

			//encontrar el id de usuario basandose en el facebook id
			$sql_find_user_date = "select * from users where facebook_id = ".$voter_id;
			$elresult_date = $conn->query($sql_find_user_date);
			$last_vote_date = $elresult_date->fetch_assoc()['updated_on'];

			$now = time();
			$your_date = strtotime($last_vote_date);
			$datediff = $now - $your_date;
			$datediff = floor($datediff/(60*60*24));

			if($last_vote_date==NULL || $datediff >= 8){
				$vote_value = $row["votes_count"]+1;
				$sql_add_vote = "update instagram_photos set votes_count = ".$vote_value." where id = ".$photo_id;
				$conn->query($sql_add_vote);

				//guardar la fecha del ultimo voto del usuario en cuestion
				$sql_find_user = "select * from users where facebook_id = ".$voter_id;
				$elresult = $conn->query($sql_find_user);
				$real_user_id = $elresult->fetch_assoc()['id'];
				$sql_save_last_vote_date = "update users set updated_on = '".$vote_date."' where id = ".$real_user_id;
				$dateresult = $conn->query($sql_save_last_vote_date);

				//guardar el voto correspondiente al id de usuario
				$sql_save_voter = "INSERT INTO votes (photo_id, user_id, created_on) VALUES ('".$photo_id."', '".$real_user_id."', '".$vote_date."')";
				$conn->query($sql_save_voter);
				echo 'ok';
			}else{
				echo 'error';
			}
		}
	}
	$conn->close();
}
?>