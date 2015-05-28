<?php
	include_once('./_config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<title>Instagram photos</title>
	<link href="css/application.css" rel="stylesheet">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<img src="images/intro.png" style="width: 100%;cursor: pointer" onclick="fb_login()" />
	<a href="./upload-photo.php" style="
		position: absolute;
		width: 270px;
		height: 89px;
		display: block;
		top: 575px;
		left: 271px;">
	</a>
	<a href="./photos.php" style="
		position: absolute;
		width: 180px;
		height: 51px;
		display: block;
		top: 760px;
		left: 316px;">
	</a>
	<div id="fb-root"></div>
	<script>
	var fb_login;
	window.fbAsyncInit = function() {
		FB.init({
			appId      : '<?php echo $facebook_app_id; ?>',
			xfbml      : true,
			version    : 'v2.3'
		});
		fb_login = function(){
			FB.login(function(response) {
				console.log('fdsa');
				setTimeout("FB.Canvas.setAutoGrow(100)", 500);
				if (response.authResponse) {
					console.log(response)
					console.log('Welcome!  Fetching your information.... ');
					FB.api('/me', function(response) {
						console.log(response)
						console.log('Good to see you, ' + response.name + '.');
						$.post(
							"user-saver.php",
							{
								facebook_id: response.id,
								name: response.name,
								email: response.email
							}
						).done(function(data){
							window.location.replace("<?php echo $photos_index_url; ?>");
						});
					});
				} else {
					console.log('User cancelled login or did not fully authorize.');
				}
			}, {scope: 'email'});
		}
	};

	(function(d, s, id){
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) {return;}
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/sdk.js";
	fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
	</script>
</body>
</html>