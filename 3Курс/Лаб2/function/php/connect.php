<?php
	$link;
	$result;
	
	function Connect()
	{
		global $link;
		$port = "3307";
		$host = "localhost:".$port;
		$database = "articles";
		$user = "root";
		$password = "";
		$link = mysqli_connect($host, $user, $password, $database)
		or die("Ошибка " . mysqli_error($link));
		$link->set_charset("utf8");
	}

	function Close()
	{
		global $link;
		mysqli_close($link);
	}
?>