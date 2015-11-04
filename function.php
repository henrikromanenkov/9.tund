<?php
	// Loon andmebaasi ühenduse
	require_once("../config_global.php");
	$database = "if15_henrrom";
	
	//tellitakse sessioon, mida hoitakse serveris.
	//kõik sessioni muutujad on kättesaadavad kuni viimase brauseri akna sulgemiseni.
	session_start();
	
	//võtab andmed ja sisestab andmebaasi
	function createUser($user_email, $hash){
		$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare('INSERT INTO user_sample (email, password) VALUES (?, ?)');
		// asendame küsimärgid. ss - s on string email, s on string password
		$stmt->bind_param("ss", $user_email, $hash);
		$stmt->execute();
		$stmt->close();
		$mysqli->close();
	}

	function loginUser($log_email, $hash){
		$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id, email FROM user_sample WHERE email=? AND password=?");
		$stmt->bind_param("ss", $log_email, $hash);
		//muutujad tulemustele
		$stmt->bind_result($id_from_db, $email_from_db);
		$stmt->execute();
		//kontrolli, kas tulemus leiti
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
		$mysqli->close();	
	}
	
	function addCarPlate($number_plate, $color){
		$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare('INSERT INTO car_plates (user_id, number_plate, color) VALUES (?,?,?)');
		$stmt->bind_param("iss", $_SESSION["logged_in_user_id"], $number_plate, $color);
		
		//Sõnum
		$message = "";
		
		if($stmt->execute()){
			//kui on tõene siis INSERT õnnestut
			$message = "Sai edukalt lisatud";
			
		}else{
			// kui on väär, siis kuvame error
			echo $stmt->error;
		}
		return $message;
		
		$stmt->close();
		$mysqli->close();
	}
	
?>