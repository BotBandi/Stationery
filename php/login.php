<?php
	require('../config/header.php');
	$content = file_get_contents('../html/login.html');
	if($_SERVER["REQUEST_METHOD"]=="POST"){
		
		$username=mysqli_real_escape_string($database,$_POST['username']);
		$password=(trim($_POST['password']));
		$sql="SELECT password FROM buyer WHERE username='$username'";
		$result=mysqli_query($database,$sql);
		
		$row= mysqli_fetch_row($result);
		$hash=$row[0];
		$logged_in = password_verify($password, $hash);
		
		if($logged_in){
			$_SESSION['username']=$username;
			header('Location: index.php');
		}
		else{
			header("Location: register.php");
		}	
	}
	require('../config/footer.php');
?>