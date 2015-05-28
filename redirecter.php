<?php
	include 'Mobile_Detect.php';
	$detect = new Mobile_Detect();
	if ($detect->isMobile()){
		header("Location: https://apps.pixel.com.py/athletic/");
		die();
	}
	else{
		header("Location: https://www.facebook.com/AthleticParaguay/app_924074664279602");
		die();
	}
?>