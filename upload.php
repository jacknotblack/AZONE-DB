<?php

function picupload($picname,$dir){
	//var_dump($_FILES);
	$target_dir = ".\A\\";
//var_dump($_FILES);
	//$target_file = $target_dir . basename($_FILES["uploadpic"]["name"]);
	//var_dump($target_file);
	$target_file = $target_dir . $_FILES["uploadpic"]["name"];
//	var_dump($target_file);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
	if(isset($_POST["submit"])) {
		$check = getimagesize($_FILES["uploadpic"]["tmp_name"]);
		if($check !== false) {
			echo "File is an image - " . $check["mime"] . ".";
			$uploadOk = 1;
		} else {
			echo "File is not an image.";
			$uploadOk = 0;
		}
	}
// Check if file already exists
	if (file_exists($target_file)) {
		echo "Sorry, file already exists.";
		$uploadOk = 0;
	}
// Check file size
	if ($_FILES["uploadpic"]["size"] > 5000000) {
		echo "Sorry, your file is too large.";
		$uploadOk = 0;
	}

//var_dump($imageFileType);
// Allow certain file formats
	if(strcasecmp($imageFileType,"JPG")!=0 && strcasecmp($imageFileType,"PNG")!=0 && strcasecmp($imageFileType,"JPEG")!=0
		&& strcasecmp($imageFileType,"GIF")!=0 ) {
		echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
	$uploadOk = 0;
}
//check if dir exists
	if (!file_exists($dir)){
		mkdir($dir);
	}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
	echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
	if (move_uploaded_file($_FILES["uploadpic"]["tmp_name"], $dir.$picname.".".$imageFileType)) {
		echo "The file ". basename( $_FILES["uploadpic"]["name"]). " has been uploaded.";
	} else {
		echo "Sorry, there was an error uploading your file.";
	}
}
}
?>

