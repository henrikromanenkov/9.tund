<?php  class User {
	
	
	//private - klassi sees
	private $connection;
	
	// klassi loomisel(new User)
	function __construct($mysqli){
		
		// this tähendab selle klassi muutuhat
		$this->connection = $mysqli;
		
	}
	
	function createUser($user_email, $hash){
		
		//teen objekti
		//seal on error, ->id ja ->message või success ja sellel on ->message
		$response = new Stdclass();
		
		//kas selline email on juba olemas
		$stmt = $this->connection->prepare("SELECT id FROM user_sample WHERE email=?");
		$stmt->bind_param("s", $user_email);
		$stmt->bind_result($id);
		$stmt->execute();
		
		if($stmt->fetch()){
			
			//annan errori, et selline email on olemas
			$error = new StdClass();
			$error->id = 0;
			$error->message = "Sellise epostiga kasutaja on juba olemas!";
			
			$response->error = $error;
			
			//rohkem enam ei käivitata
			return $response;
			
		}
		
		//panene eelmise päringu kinni
		
		$stmt->close();
			
		$stmt = $this->connection->prepare('INSERT INTO user_sample (email, password) VALUES (?, ?)');
		$stmt->bind_param("ss", $user_email, $hash);
		
		//sain edukalt salvestatud
		if($stmt->execute()){
			
			$success  = new StdClass();
			$success->message = "Kasutaja edukalt loodud!";
			$response->success = $success;
			return $response;
			
		}else{
			
			$error = new StdClass();
			$error->id = 1;
			$error->message = "Midagi läks katki!";
			
			$response->error = $error;
			
		}
		$stmt->close();
		return $response;
		
	}

	function loginUser($log_email, $hash){
		$stmt = $this->connection->prepare("SELECT id FROM user_sample WHERE email=?");
		$stmt->bind_param("s", $log_email);
		$stmt->bind_result($id);
		$stmt->execute();

		// ! -> ei olnud e-posti
		if(!$stmt->fetch()){
			
			$error = new StdClass();
			$error->id = 0;
			$error->message = "Sellist kasutajat ei ole!";
			
			$response->error = $error;
			return $response;
			
		}	
			
		$stmt->close();

		$stmt = $this->connection->prepare("SELECT id, email FROM user_sample WHERE email=? AND password=?");
		$stmt->bind_param("ss", $log_email, $hash);
		$stmt->bind_result($id_from_db, $email_from_db);
		$stmt->execute();
		if($stmt->fetch()){
		
			//kõik õige
			$success  = new StdClass();
			$success->message = "Kasutaja edukalt sisselogitud!";
			$response->success = $success;
			
			$user = new StdClass();
			$user->id = $id_from_db;
			$user->email = $email_from_db;
			
			$response->user = $user;
			
		}else{
			
			//parool vale
			$error = new StdClass();
			$error->id = 1;
			$error->message = "Parool on vale!";
			
			$response->error = $error;
			
		}
		
		$stmt->close();
		return $response;
	}
}  ?>

