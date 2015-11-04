<?php
	require_once("function.php");
	
	if(!isset($_SESSION["logged_in_user_id"])){
		header("Location: login.php");
	}

	
	if(isset($_GET["logout"])){

		session_destroy();
		
		header("Location: login.php");
	}
	
?>
<p>Tere,  <?php echo $_SESSION["logged_in_user_email"];?>
	<a href="?logout=1"> Logi välja</a> 
</p> 
