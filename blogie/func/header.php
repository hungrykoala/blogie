<?php
	session_start();
	if(empty($_SESSION['user_id'])){
		header("Location: register.php");
	}
	
?>