<?php
	session_start();
	require('../config/functions.php');
	require('../config/connect.php');
	$content='';
	$login = 'nav.html';
	$output=file_get_contents('../html/layout.html');
	if (!empty($_SESSION['username']))
	{
		$login = 'navlogged.html';
	}