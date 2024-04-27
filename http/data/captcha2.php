<?php

	// $testGD = get_extension_funcs("gd"); // Grab function list 
	// 	if (!$testGD){ echo "GD not even installed. Please enable module GD in PHP"; exit; }
	// 	echo"<pre>".print_r($testGD,true)."</pre>";

	session_start();
 
	$randomnr = rand(1000, 9999);
	$_SESSION['randomnr2'] = md5($randomnr);

	$im = imagecreatetruecolor(100, 38);
 
	$white = imagecolorallocate($im, 255, 255, 255);
	$grey = imagecolorallocate($im, 150, 150, 150);
	$black = imagecolorallocate($im, 0, 0, 0);
 
	imagefilledrectangle($im, 0, 0, 200, 35, $black);
	
	$font = '../file/font/beer-money12.ttf';

	imagettftext($im, 20, 4, 22, 30, $grey, $font, $randomnr);
 
	imagettftext($im, 20, 4, 15, 32, $white, $font, $randomnr);
 	
	header("Expires: Wed, 1 Jan 1997 00:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
 
	header ("Content-type: image/gif");
	imagegif($im);
	imagedestroy($im);
?>
