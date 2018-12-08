<?php
	require('../config/header.php');
	$content=file_get_contents('../html/register.html');
	$error=array();
	if($_SERVER["REQUEST_METHOD"]=="POST"){
		$username=$_POST['username'];
		$password=trim($_POST['password']);
		$passwordre=trim($_POST['passwordre']);
		$name=$_POST['name'];
		$email=$_POST['email'];
		$phone_number=$_POST['phone_number'];
		$address=$_POST['address'];
		
		$sql="SELECT username FROM buyer WHERE username='$username'";
		
		if(!empty($username) AND !empty($password) AND !empty($passwordre) AND !empty($name) AND !empty($email) AND !empty($phone_number) AND !empty($address)){
			if((strlen($username)>5) AND (strlen($password)>5) AND (strlen($email)>5)){
				if($password==$passwordre){
					$result=mysqli_query($database,$sql);
					if(mysqli_num_rows($result)){
						$error[]="The username is already used!";
					}
					else{
						if((!filter_var($email,FILTER_VALIDATE_EMAIL))){
							$error[]="Incorrect e-mail!";
						}
						else{
							$password=password_hash($password, PASSWORD_DEFAULT);
							$sql="INSERT INTO buyer(username,password,name,email,phone_number,address)
									VALUES('$username','$password','$name','$email','$phone_number','$address')";
							mysqli_query($database,$sql);
							if(mysqli_errno($database)==0){
								$error[]="The registration was successful!";
								
							}
							else{
								$error[]="Registration failed!";
								$error[]=mysqli_error($database);
							}
						}
					}
				}
				else{
					$error[]="Passwords are not matching!";
				}
			}
			else{
				$error[]="Every field need to have at least 5 characters!";
			}
		}
		else{
			$error[]="Please fill every field!";
		}
	}
	$login_error_list='';
	if($errors=flash_get('registererrors')){
		$login_error_list=implode('<br>',$error);
	}
	$content=str_replace("::registererrors",$login_error_list,$content);
	if(!empty($error)){
		flash_set('registererrors',$error);
		header("Location: register.php");
	}
	require('../config/footer.php');
?>