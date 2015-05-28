<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<title>Instagram photos</title>
	<!-- Bootstrap -->
	<!-- <link href='http://fonts.googleapis.com/css?family=Titillium+Web:400,300,600,700' rel='stylesheet' type='text/css'> -->
	<link href="application.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<a href="./upload-photo.php" class="btn btn-lg btn-primary animated bounceInDown" style="position: absolute;top: 15px;right: 15px;display: none" id="go-to-upload">Subir foto</a>
	<img src="images/header.png" style="width: 100%">
	<div class="container">
		<div class="row">
			<div id="photos" class="col-md-12 photos">
				<?php
					include_once('./_config.php');

					date_default_timezone_set('America/Los_Angeles');
					$date = date('Y-m-d h:i:s', time());

					$sql = "select * from instagram_photos order by id DESC limit 6";
					$result = $conn->query($sql);
					$i = 1;
					if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc()) {
							if(!empty($row["photo_url"])){
								echo '<div class="col-md-3 col-sm-4"><div id="photo-'.$row["id"].'" class="photo">
										<img src="'.$row["photo_url"].'" data-toggle="modal" data-target="#photoModal">
										<div>
											<h2 class="photo-title">'.$row["username"].'</h2>
											<h3 class="photo-meta"><span class="votes-count">'.$row["votes_count"].'</span> <span class="glyphicon glyphicon-heart"></span> <a href="javascript:vote('.$row["id"].');" class="btn btn-primary pull-right vote-button">Votar</a></h3>
											<p class="photo-desc">'.$row["caption"].'</p>
										</div>
									</div></div>';
								if($i % 3 == 0){
									echo '<div style="width:100%;float:left"></div>';
								}
								$i++;
								?>
								<script>var offset_id = <?php echo $row["id"];?></script>
								<?php
							}
						}
					} else {
						echo "0 results";
					}
					$conn->close();
				?>
			</div>
		</div>
		<p class="text-center"><a href="javascript:getMorePhotos();" class="btn btn-success btn-lg">Ver más fotos</a></p>
	</div>
	<!-- Modal -->
	<div class="modal fade single-photo-modal" id="photoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title text-center"><b id="modal-title"></b></h4>
				</div>
				<div class="modal-body">
					<div id="modal-photo"></div>
					<div id="modal-text"></div>
				</div>
				<div class="modal-footer">
					<span class="pull-left" id="modal-votes-count"></span>
					<a href="#" type="button" class="btn btn-primary" id="modal-vote-button">Votar</a>
				</div>
			</div>
		</div>
	</div>


	<div id="fb-root"></div>
	<script>
	var fb_login;
	var user_id;
	window.fbAsyncInit = function() {
		FB.init({
			appId      : '<?php echo $facebook_app_id; ?>',
			xfbml      : true,
			version    : 'v2.3'
		});
		FB.Canvas.setSize();
		// Load in the user credentials
		FB.getLoginStatus(function(response){
			if (response.status === 'connected') {
				FB.api('/me', function(response) {
					user_id = response.id;
					console.log(response);
					console.log('Good to see you, ' + response.name + '.');
					$.post(
						"user-already-uploaded.php",
						{
							user_id: user_id
						}
					).done(function(data){
						console.log(data);
						if(data != 'already_uploaded'){
							$('#go-to-upload').show();
						}
					});
				});
			} else {
				FB.login(function(response) {
					console.log('fdsa');
					console.log('holfdfdsasaa');
					if (response.authResponse){
						console.log('Welcome!  Fetching your information.... ');
					} else {
						console.log('User cancelled login or did not fully authorize.');
					}
				}, {scope: 'email'});
			}
		});
		setTimeout("FB.Canvas.setAutoGrow(100)", 500);
	};

	(function(d, s, id){
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) {return;}
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/es_ES/sdk.js";
	fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
	</script>
	<script type="text/javascript">
		var photoIndex = 2;
		var respuesta;
		var able = true;
		var voted_photo_id;
		function vote(photo_id){
			console.log(photo_id);
			voted_photo_id = photo_id;
			console.log(user_id);
			$.post(
				"vote.php",
				{
					photo_id: photo_id,
					user_id: user_id
				}
			).done(function(data){
				console.log(data);
				$('#modal-vote-button, .vote-button').attr('disabled', 'disabled');
				if(data=='ok'){
					var currentnumber = parseInt($('#photo-'+voted_photo_id).find('.votes-count').html());
					$('#photo-'+voted_photo_id).find('.votes-count').html(currentnumber+1)
					$('#modal-votes-count').html(currentnumber+1);
				}
			});
		}
		function getMorePhotos(){
			$.post(
				"more-photos-from-database.php",
				{
					offset: offset_id
				},
				function(data) {

					console.log('deberia cambiar');
					able = true;
					if(data=='empty'){
						console.log(empty)
					}else{
						console.log(data.items);
						respuesta = data;
						$.each(data.items, function(i, item) {
							console.log('i = '+i);
							$('#photos').append('<div class="col-md-3 col-sm-4"><div id="photo-'+item[3]+'" class="photo">\
									<img src="' + item[0] + '" data-toggle="modal" data-target="#photoModal">\
									<div>\
										<h2 class="photo-title">' + item[1] + '</h2>\
										<h3 class="photo-meta"><span class="votes-count">' + item[4] + '</span> <span class="glyphicon glyphicon-heart"></span> <a href="javascript:vote('+item[3]+');" class="btn btn-primary pull-right vote-button">Votar</a></h3>\
										<p class="photo-desc">' + item[2] + '</p>\
									</div>\
								</div></div>');
							if((i+1) % 3 ==0){
								$('#photos').append('<div style="width:100%;float:left"></div>');
							}
							offset_id = item[3];
						});
					}
				},
				'json'
			);
		}

		$(document).ready(function() {
			$('#photoModal').on('show.bs.modal', function (event) {
				var trigger = $(event.relatedTarget);
				var photo_parent = trigger.parent('.photo');
				var photo_id = (photo_parent.attr('id')).substring(6);
				$('#modal-photo').empty().html('<img src="'+trigger.attr('src')+'" />');
				$('#modal-title').empty().html(photo_parent.find('h2').html());
				$('#modal-text').empty().html(photo_parent.find('p').html());
				$('#modal-votes-count').empty().html(photo_parent.find('.votes-count').html());
				$('#modal-vote-button').attr('href', photo_parent.find('.vote-button').attr('href'));
				console.log(photo_parent.find('.vote-button').attr('href'))
				console.log('photoid '+photo_id);
			});
		});
	</script>
</body>
</html>