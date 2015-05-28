<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<title>Buscapersonas</title>
	<link href='http://fonts.googleapis.com/css?family=Titillium+Web:400,300,600,700' rel='stylesheet' type='text/css'>
	<link href="application.css" rel="stylesheet">
</head>
<body>
<?php
	date_default_timezone_set('UTC');
	include_once('./_config.php');

	//get already passed photos
	$sql = "select photo_id from instagram_photos";
	$result = $conn->query($sql);
	$already_saved_photos = array();

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$already_saved_photos[] = $row["photo_id"];
		}
	} else {
		//echo "0 results";
	}

	//get already discarded photos
	$sql2 = "select photo_id from canceled";
	$result2 = $conn->query($sql2);
	$already_canceled_photos = array();
	if ($result2->num_rows > 0) {
		while($row = $result2->fetch_assoc()) {
			$already_canceled_photos[] = $row["photo_id"];
		}
	} else {
		//echo "0 results";
	}
	$conn->close();


	//Get instagram photos
	require_once 'Instagram.php';
	use MetzWeb\Instagram\Instagram;
	$instagram = new Instagram(array(
		'apiKey'      => $instagram_api_key,
		'apiSecret'   => $instagram_api_secret,
		'apiCallback' => $instagram_api_callback
	));
	$media = $instagram->getTagMedia($tag);
?>
<div class="container">
	<form id="login" class="col-md-12" style="margin-top: 20px">
		<h4 id="validation" style="display: none" class="text-center">Datos incorrectos</h4>
		<div class="form-group">
			<label for="exampleInputEmail1">Username</label>
			<input type="text" class="form-control" id="userinput" placeholder="Username">
		</div>
		<div class="form-group">
			<label for="exampleInputPassword1">Password</label>
			<input type="password" class="form-control" id="passwordinput" placeholder="Password">
		</div>
		<p class="text-center">
			<button type="submit" class="btn btn-primary" id="login-btn">Ingresar</button>
		</p>
	</form>
</div>
<div id="locked">
	<div class="items">
		<?php
		foreach ($media->data as $data) {
			if(getdate($data->created_time)['year'] == 2015){
				if(!in_array($data->id, $already_saved_photos) && !in_array($data->id, $already_canceled_photos)){
			?>
				<div class="item" data-photo-id="<?php echo $data->id ?>">
					<div class="image">
						<img src="<?php echo $data->images->low_resolution->url ?>">
					</div>
					<div class="meta">
						<h3><?php echo$data->user->full_name; ?></h3>
						<p><?php echo $data->caption->text ?></p>
					</div>
				</div>
			<?php 
				}
			}
		}
		?>
	</div>
	<?php echo '<br><button id="more" style="display: none;" data-maxid="'.$media->pagination->next_max_id.'">Load more ...</button>'; ?>
	
	<p class="text-center">
		<a href="javascript:void(0);" class="actions" id="no"><span class="glyphicon glyphicon-remove"></span></a>
		<a href="javascript:void(0);" class="actions" id="yes"><span class="glyphicon glyphicon-ok"></span></a>
	</p>
</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script type="text/javascript">
		var adminuser;
		var alreadyshown = new Array();
		$(document).ready(function() {

			$(".item").each(function( index ) {
				alreadyshown.push($(this).attr('data-photo-id'));
			});

			$('#login').submit(function(e){
				console.log('prevented');
				e.preventDefault();
				var userinput = $("#userinput").val();
				var passwordinput = $("#passwordinput").val();
				console.log(userinput);
				console.log(passwordinput);
				if(userinput == 'cm1' && passwordinput == '12345'){
					adminuser = userinput;
					$("#locked").show();
					$("#login").hide();
					console.log('prevented');
				}
				if(userinput == 'cm2' && passwordinput == '123456'){
					adminuser = userinput;
					$("#locked").show();
					$("#login").hide();
				}
				if(userinput == 'cm3' && passwordinput == '1234567'){
					adminuser = userinput;
					$("#locked").show();
					$("#login").hide();
				}
				if(userinput == 'admin' && passwordinput == '12345'){
					adminuser = userinput;
					$("#locked").show();
					$("#login").hide();
				}
				$("#validation").show();
			});

			//manage ok and discard events
			$('.actions').on('click', function(){
				btn = $(this);
				item = $('.item:visible')
				photo_id = item.attr('data-photo-id');
				photo_url = item.find('img').attr('src');
				username = item.find('h3').html();
				caption = item.find('p').html();
				if(btn.attr('id')=='no'){
					$.post(
						"photo-cancel.php",
						{
							photo_id: photo_id,
							approved_by: adminuser
						}
					);
					item.remove();
					//$('#more').click();
				}else{
					$.post(
						"photo-saver.php",
						{
							photo_id: photo_id,
							photo_url: photo_url,
							username: username,
							caption: caption,
							approved_by: adminuser
						}
					);
					item.remove();
					if($('.item').length == 0 ){
						//$('#more').click();
					}
				}
			});

			//getting new photos for moderation every 2 seconds
			setInterval(function(){
				$('#more').click();
			}, 2000);

			//manage clicks on more button
			$('#more').click(function() {
				maxid = $(this).data('maxid');
				$.ajax({
					type: 'GET',
					url: 'more-photos-from-instagram.php',
					data: {
						max_id: maxid
					},
					dataType: 'json',
					cache: false,
					success: function(data) {
						// Output data
						$.each(data.items, function(i, item) {
							if(alreadyshown.indexOf(item[3]) >= 0){
								//console.log('ya estaba');
							}else{
								$('.items').append('<div class="item" data-photo-id="' + item[3] + '"><div class="image"><img src="' + item[0] + '"></div><div class="meta"><h3>' + item[1] + '</h3><p>' + item[2] + '</p></div></div>');
								alreadyshown.push(item[3]);
							}
						});
						// Store new maxid
						$('#more').data('maxid', data.next_id);
					}
				});
			});
		});
	</script>
</body>
</html>