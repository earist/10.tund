<?php
	
	require_once("functions.php");
	require_once("InterestsManager.class.php");
	
	if(!isset($_SESSION["logged_in_user_id"])){
		header("Location: login.php");
		//see katkestab faili edasise lugemise, soovitatav alati peale headerit kasutada, va funktsioonid
		exit();
	}


	if(isset($_GET["logout"])){

		session_destroy();
		
		header("Location: login.php");
	}
	// uus instants klassist
	$InterestsManager = new InterestsManager($mysqli, $_SESSION["logged_in_user_id"]);
	
	//aadressirealt muutuja
	if(isset($_GET["new_interest"])) {
		//$InterestsManager->addInterest($_GET["new_interest"]);
		$add_new_response = $InterestsManager->addInterest($_GET["new_interest"]);
	}
	//rippmenüü valiku kõrval vajutati nuppu
	if(isset($_GET["new_dd_selection"])) {
		
		$add_new_userinterest = $InterestsManager->addUserInterest($_GET["new_dd_selection"]);
	}
	
?>

<p>Tere, <?=$_SESSION["logged_in_user_email"];?>
	<a href="?logout=1"> Logi välja <a>
</p>
<h2>Lisa huviala</h2>
  <?php if(isset($add_new_response->error)): ?>
  
	<p style="color:red;">
		<?=$add_new_response->error->message;?>
	</p>
  
  <?php elseif(isset($add_new_response->success)): ?>
	
	<p style="color:green;" >
		<?=$add_new_response->success->message;?>
	</p>
	
  <?php endif; ?>
<form>
	<input type="text" name="new_interest" placeholder="Lisa huviala">
	<input type="submit" name="Lisa" value="Lisa">
</form>
	
<h2>Minu huvialad</h2>
  <?php if(isset($add_new_userinterest->error)): ?>
  
	<p style="color:red;">
		<?=$add_new_userinterest->error->message;?>
	</p>
  
  <?php elseif(isset($add_new_userinterest->success)): ?>
	
	<p style="color:green;" >
		<?=$add_new_userinterest->success->message;?>
	</p>
	
  <?php endif; ?>
<form>
	<!-- siia järele tuleb rippmenüü -->
	<?=$InterestsManager->createDropdown();?>
	<input type="submit">
</form>
<br><br>
<?=$InterestsManager->getUserInterests();?>



