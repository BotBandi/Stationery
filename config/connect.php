<?php
	$database=mysqli_connect('localhost','root','','iroszeruzlet');
	$sql='SET NAMES utf8';
	$result=mysqli_query($database,$sql);
		if (mysqli_connect_errno() > 0) {
		echo "Sikertelen adatbázis kapcsolódás!";
		exit();
		mysqli_set_charset($database,"utf8");
		
	}