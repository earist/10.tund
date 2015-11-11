<?php
	
	require_once("functions.php");
	
	if(!isset($_SESSION["logged_in_user_id"])){
		header("Location: login.php");
		//see katkestab faili edasise lugemise, soovitatav alati peale headerit kasutada, va funktsioonid
		exit();
	}


	if(isset($_GET["logout"])){

		session_destroy();
		
		header("Location: login.php");
	}
?>


<p>Tere, <?=$_SESSION["logged_in_user_email"];?>
	<a href="?logout=1"> Logi välja <a>
</p>
	