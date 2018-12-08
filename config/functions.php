<?php
    function is_logged()
	{
		return !empty($_SESSION['username']);
	}
	
	
	function flash_set($key,$value)
	{
		$_SESSION['_flash'][$key]=$value;
	}
	
	function flash_has($key)
	{
		return isset($_SESSION['_flash'][$key]);
	}
	
	function flash_delete($key)
	{
		unset($_SESSION['_flash'][$key]);
	}
	
	function flash_get($key,$default=null)
	{
		if(!flash_has($key))
		{
			return $default;
		}
		$value=$_SESSION['_flash'][$key];
		flash_delete($key);
		return $value;
	}
	
	function flash_init()
	{
		if
		(!isset($_SESSION['_flash']))
		{
			 $_SESSION['_flash']=array();
		}
	
	}
	
	