<?php
session_start();

// Generate a random 4-digit number for the captcha
$captcha = rand(1000, 9999);

// Store the captcha in the session
$_SESSION["captcha"] = $captcha;

// Create a 50x24 image canvas
$imageCanvas = imagecreatetruecolor(50, 24);

// Set background color to blue
$backgroundColor = imagecolorallocate($imageCanvas, 22, 86, 165);

// Set text color to white
$textColor = imagecolorallocate($imageCanvas, 255, 255, 255);

// Fill the image background with blue color
imagefill($imageCanvas, 0, 0, $backgroundColor);

// Print the captcha text on the image with random position and size
imagestring($imageCanvas, rand(1, 7), rand(1, 7), rand(1, 7), $captcha, $textColor);

// Prevent browser caching
header("Cache-Control: no-store, no-cache, must-revalidate");

// Specify that the content is an image in PNG format
header('Content-type: image/png');

// Output the captcha image to the browser
imagepng($imageCanvas);

// Free up memory by destroying the image resource
imagedestroy($imageCanvas);
?>

