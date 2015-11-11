<?php

	class InterestsManager{
		private $connection;
		private $user_id;
		
		//kui tekitan new(datas), siis käivitatakse see funktsioon
		function __construct($mysqli, $user_id_from_session){
			// selle klassi muutuja
			$this->connection = $mysqli;
			$this->user_id = $user_id_from_session;
			echo "Huvialade haldus käivitatud, kasutaja=".$this->user_id;
		}
		
		function addInterest($new_interest){
			//võta eeskuju createUser klassist User
			//1. kontrolli kas selline huviala on juba olemas
			//2. kui ei ole, lisad juurde **********************
		$response = new StdClass();

		$stmt = $this->connection->prepare("SELECT id FROM interests WHERE name=?");
		$stmt->bind_param("s", $new_interest);
		$stmt->bind_result($id);
		$stmt->execute();
		
		//kas sain rea andmeid
		if($stmt->fetch()){

			$error = new StdClass();
			$error->id = 0;
			$error->message = "Huviala <strong>".$new_interest."</strong> on juba olemas!";
			$response->error = $error;
			return $response;
		}

		$stmt->close();
		
		$stmt = $this->connection->prepare("INSERT INTO interests (name) VALUES (?)");
		//echo $mysqli->error;
		$stmt->bind_param("s", $new_interest);

		if($stmt->execute()){
			$success = new StdClass();
			$success->message = "Huviala edukalt lisatud!";
			$response->success = $success;
		}else{
			$error = new StdClass();
			$error->id = 1;
			$error->message = "Midagi läks katki!";
			$response->error = $error;
		}
		
		$stmt->close();
		return $response;
		}
		
		function createDropdown(){
			
			$html = '';
			
			$html .= '<select name="new_dd_selection">';
			
			//$html .= '<option selected>1</option>';
			$stmt = $this->connection->prepare("SELECT id, name FROM interests");
			$stmt->bind_result($id, $name);
			$stmt->execute();
			
			//iga rea kohta
			while($stmt->fetch()){
				$html .= '<option value="'.$id.'">'.$name.'</option>';
			}

			$html .= '</select>';
			return $html;
		}
		function addUserInterest($new_interest_id){
		
			//kontrollin ega pole olemas ja lisan juurde
		$response = new StdClass();
		
		//kas sellel kasutajal on see huviala
		$stmt = $this->connection->prepare("SELECT id FROM user_interests WHERE user_id=? AND interests_id=?");
		$stmt->bind_param("ii", $this->user_id, $new_interest_id);
		$stmt->bind_result($id);
		$stmt->execute();
		
		//kas sain rea andmeid
		if($stmt->fetch()){
			$error = new StdClass();
			$error->id = 0;
			$error->message = " Huviala on sinul juba olemas!";
			$response->error = $error;
			return $response;
		}

		$stmt->close();
		
		$stmt = $this->connection->prepare("INSERT INTO user_interests (user_id, interests_id) VALUES (?,?)");
		//echo $mysqli->error;
		$stmt->bind_param("ii", $this->user_id, $new_interest_id);

		if($stmt->execute()){
			$success = new StdClass();
			$success->message = "Huviala edukalt lisatud!";
			$response->success = $success;
		}else{
			$error = new StdClass();
			$error->id = 1;
			$error->message = "Midagi läks katki!";
			$response->error = $error;
		}
		
		$stmt->close();
		return $response;
		
		}
		function getUserInterests(){
			$html = '';
			$stmt = $this->connection->prepare("SELECT interests.name FROM user_interests JOIN interests ON user_interests.interests_id = interests.id
			WHERE user_interests.user_id=?");
			$stmt->bind_param("i", $this->user_id);
			$stmt->bind_result($name);
			$stmt->execute();
			
			//iga rea kohta
			while($stmt->fetch()){
				$html .= '<p>'.'<p>';				
			}
			
			return $html;
		}
	
}?>