<?php
	require_once("function.php");
	
	if(!isset($_SESSION["logged_in_user_id"])){
		header("Location: login.php");
		
		//edasist ei käivitata
		exit();
	}

	
	if(isset($_GET["logout"])){

		session_destroy();
		
		header("Location: login.php");
	}
	
	
	
	$target_dir = "profile_pics/";
	$target_file = $target_dir.$_SESSION["logged_in_user_id"].".jpg";
	
	if(isset($_POST["submit"])) {
	
		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		// Check if image file is a actual image or fake image
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		if($check !== false) {
			echo "File is an image - " . $check["mime"] . ".";
			$uploadOk = 1;
		} else {
			echo "File is not an image.";
			$uploadOk = 0;
		}
	
		// Check if file already exists
		if (file_exists($target_file)) {
			echo "Sorry, file already exists.";
			$uploadOk = 0;
		}	
		// Check file size - 
		if ($_FILES["fileToUpload"]["size"] > 1024000) {
			echo "Sorry, your file is too large.";
			$uploadOk = 0;
		}
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
			echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			echo "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
				echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
				
				header("Location: data.php");
				
			} else {
				echo "Sorry, there was an error uploading your file.";
			}
		}
		
	}
	
	
	if(isset($_GET["delete"])){
		
		unlink($target_file);
		
		header("Location: data.php");
	}
	
	
	
	
	
	
	
	
?>
<?php if(isset($_SESSION["login_success_message"])):  ?>

<p style="color:green;"> <?=$_SESSION["login_success_message"];?> </p>


<?php
	unset($_SESSION["login_success_message"]);
	
	endif; ?>


<p>Tere,  <?php echo $_SESSION["logged_in_user_email"];?>
	<a href="?logout=1"> Logi välja</a> 
</p> 

<h2> Profiilipilt <h2>

<?php if(file_exists($target_file)):?>

<div style=" 
 width: 200px;
 height: 200px;
 background-image: url(<?=$target_file;?>);
 background-position: center center;
 background-size: cover;"></div>

 <a href="?delete=1">Delete profile picture</a>
 
<?php else: ?>


<form action="data.php" method="post" enctype="multipart/form-data">
    Lae üles pilt (1MB, png, jpg, gif)
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>

<?php endif; ?>


<?php

	$file_array = scandir($target_dir);
	var_dump($file_array);
	
	
	
	for($i = 0; $i < count($file_array); $i++){
		
		echo "<a href='".$target_dir.$file_array[$i]."'>".$file_array[$i]."</a>";
		
	}
?>













