<?php  class User {
	
	
	//private - klassi sees
	private $connection;
	
	// klassi loomisel(new User)
	function __construct($mysqli){
		
		// this tähendab selle klassi muutuhat
		$this->connection = $mysqli;
		
	}
	
	function createUser($user_email, $hash){
		$stmt = $this->connection->prepare('INSERT INTO user_sample (email, password) VALUES (?, ?)');
		$stmt->bind_param("ss", $user_email, $hash);
		$stmt->execute();
		$stmt->close();
		$mysqli->close();
	}

	function loginUser($log_email, $hash){
		$stmt = $this->connection->prepare("SELECT id, email FROM user_sample WHERE email=? AND password=?");
		$stmt->bind_param("ss", $log_email, $hash);
		$stmt->bind_result($id_from_db, $email_from_db);
		$stmt->execute();
		if($stmt->fetch()){
			//ab'i oli midagi
			echo "Email ja parool õiged, kasutaja id=".$id_from_db;	
			
			//tekitan sessiooni muutujad
			$_SESSION["logged_in_user_id"] = $id_from_db;
			$_SESSION["logged_in_user_email"] = $email_from_db;
			
			//suunan data.php lehele
			header("Location: data.php");
			
		}else{
			//ei leidnud
			echo "wrong credentials";
		}			
		$stmt->close();	
	}
}  ?>

