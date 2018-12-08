<?php
	require ('../config/header.php');
	session_destroy();
	header('Location: index.php');
?>