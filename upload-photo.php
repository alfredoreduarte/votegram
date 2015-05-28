<?php include_once('./_config.php'); ?>
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
	<a href="<?php echo $photos_index_url;?>" class="btn btn-primary" style="position: absolute;top: 15px;right: 15px;">Volver al inicio</a>
	<img src="images/header.png" style="width: 100%">
	<div class="container">
		<form action="photo-uploader.php" method="post" enctype="multipart/form-data" class="col-sm-6 col-sm-offset-3">
			<div class="form-group">
				<label for="fileToUpload">Selecctiona tu foto</label>
				<input type="file" class="form-control" name="fileToUpload" id="fileToUpload" required>
			</div>
			<div class="form-group">
				<label for="comment">Breve comentario</label>
				<textarea class="form-control" rows="6" name="caption" id="caption" placeholder="Escribí acerca de tu foto" required></textarea>
			</div>
			<input type="hidden" class="form-control" name="username" id="username">
			<input type="hidden" class="form-control" name="photo_id" id="photo_id">
			<p class="text-center"><input type="submit" value="Publicar foto" name="submit" class="btn btn-primary" data-loading-text="Favor espera..."></p>
		</form>
	</div>

	<div id="fb-root"></div>
	<script>
	var user_id;
	window.fbAsyncInit = function() {
		FB.init({
			appId      : '<?php echo $facebook_app_id; ?>',
			xfbml      : true,
			version    : 'v2.3'
		});
		FB.Canvas.setSize();
		FB.login(function(response) {
			if (response.authResponse) {
				console.log('Welcome!  Fetching your information.... ');
				FB.api('/me', function(response) {
					user_id = response.id;
					console.log(response);
					console.log('Good to see you, ' + response.name + '.');
					document.getElementById('username').value = response.name;
					document.getElementById('photo_id').value = response.id + '-' + new Date().getTime();
				});
			} else {
				console.log('User cancelled login or did not fully authorize.');
			}
		}, {scope: 'email'});
		setTimeout("FB.Canvas.setAutoGrow(100)", 500);
	};

	(function(d, s, id){
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) {return;}
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/sdk.js";
	fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
	</script>
	<script type="text/javascript">
	$(document).ready(function() {
		// $('.btn').on('click', function () {
		// 	var $btn = $(this).button('loading');
		// })
	});
	</script>
</body>
</html>