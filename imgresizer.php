
<?php
// File and new size
//$filename = ??;
//$newname = ??;
//$newheight = $height * $percent;
//$newwidth = 500;
//$percent = 0.5;

// Content type
//header('Content-Type: image/jpeg');

// Get new sizes
//list($width, $height) = getimagesize($filename);

// Load
$thumb = imagecreatetruecolor($newwidth, $newheight);

if($new_picture_type == 'image/jpeg'){
	$source = imagecreatefromjpeg($filename);
}
if ($new_picture_type == 'image/png'){	
	$source = imagecreatefrompng($filename);
}
if($new_picture_type == 'image/gif'){
	$source = imagecreatefromgif($filename);
}
if($new_picture_type == 'image/pjpeg'){
	$source = imagecreatefromjpeg($filename);
}

// Resize
//imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

// Output
imagejpeg($thumb, $filehome);
//echo "Image resized. ";
//header("Location: adinventory.php");

?>
