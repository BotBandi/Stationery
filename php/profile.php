<?php
	require ('../config/header.php');
	$error = array();
	
	if(isset($_SESSION['username'])){
		$username=$_SESSION['username'];
		$sql="SELECT * FROM buyer WHERE username='$username'";
		$result=mysqli_query($database,$sql);
		$row=mysqli_fetch_assoc($result);
		$content=file_get_contents('../html/profile.html');
		$table = '<div><br><br><p><h2>Welcome '.$row['username'].'!</h2></p><br>';
		$table.= "<form method='post' action='profile.php'>
			<table>";
		
		
		$table .= "<tr><td><br>New Password:</td>";	
		$table .= '<td><br><input class="form-control" type="password" name="password"></td></tr>';
		$table .= '<tr><td><br>Verify Password:</td>';
		$table.= ' <td><br><input class="form-control" type="password" name="passwordre"></td></tr>';		

		$table .= "<tr><td><br>Recently used e-mail:</td>";
		$table .= "<td><br>".$row['email']."</td></tr>";
		$table.="<tr><td><br>New e-mail:</td>";
		$table .= '<td><br><input class="form-control" type="email" name="email"></td> </tr>';
		$table .= '</table> <br>	
				<button type="submit" class="btn btn-group-vertical btn-primary" data-toggle="button" aria-pressed="false" autocomplete="off">Apply</button>
				</form></div>';
		if ($_SERVER["REQUEST_METHOD"]=="POST"){
			$password = trim($_POST['password']);
			$passwordre = trim($_POST['passwordre']);
			$email = $_POST['email'];
			if(strlen($password)<5){
				$error[] = "The password must be at least 5 character long!";
			}
			else{
				if(!($password==$passwordre)){
					$error[] = "The passwords aren't matching!";
				}
				else{
					if (!(filter_var($email,FILTER_VALIDATE_EMAIL))){
						$error[] = "Invalid e-mail!";
					}
					else{
						$password=password_hash($password, PASSWORD_DEFAULT);	
						$sql = "UPDATE buyer SET password='$password',email='$email' WHERE username='$username'";	
						mysqli_query($database,$sql);
						if(mysqli_errno($database) == 0)
						{
							$error[] = "Succesfull modify!";
						}
						else{
							$error[] = "The modification failed!";
						}
					}
				}
			}
		}
		$content = str_replace('::content',$table,$content);
		$modify_error_list='';
		if(!empty ($error))
		{
			flash_set('modifyerrors',$error);
		}
		if($error=flash_get('modifyerrors'))
		{
			$modify_error_list=implode('<br>',$error);
		}
		$content=str_replace("::modifyerrors",$modify_error_list,$content);
	}
	else
	{
		$content = "Please login!";
	}
	
	require('../config/footer.php');
?>