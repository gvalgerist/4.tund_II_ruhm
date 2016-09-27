<?php
	
	//functioons.php
	//var_dump($GLOBALS);
	
	
	//see fail peab olema koikidel lehtedel kus tahan kasutada SESSION muutujat
	session_start();
	
	//***************
	//****SIGNUP*****
	//***************
	
	function signUp ($email, $password) {
		
		$database = "if16_georg";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt = $mysqli->prepare("INSERT INTO user_sample(email, password) VALUES(?, ?)");
	
		echo $mysqli->error;
		
		$stmt->bind_param("ss", $email, $password);
		
		if($stmt->execute()) {
			
			echo "salvestamine onnestus";
			
		} else {
			
			echo "ERROR".$stmt->error;
		}
		
		$stmt->close();
		$mysqli->close();
		
	}

	function login($email, $password) {
		
		$error="";
		
		$database = "if16_georg";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt = $mysqli->prepare("SELECT id, email, password, created FROM user_sample WHERE email=?");
	
		echo $mysqli->error;
		
		
		//asendan kysimargi
		$stmt->bind_param("s", $email);
		
		//maaran vaartused muutujatesse
		$stmt->bind_result($id, $emailFromDb,$passwordFromDb, $created);
		
		$stmt->execute();
		
		//kas andmed tulid v mitte
		if($stmt->fetch()){
			
			//oli selline meil
			$hash=hash("sha512", $password);
			if($hash==$passwordFromDb){
				
				echo"Kasutaja logis sisse ".$id;
				
				$_SESSION["userId"]=$id;
				$_SESSION["userEmail"]=$emailFromDb;
				
				header("Location: data.php");
				
			}else {
				$error="vale parool";
			}
			
			
		}else{
			
			//ei olnud seda meili
			$error="ei ole sellist emaili";
			
		}
		
		return $error;
	
	}


?>