<?php

defined('SITE') or die;

function HTMLSCDecode($str)
{
	$search = array('%amp;', '%lt;', '%gt;', '%quot;', "'");
	$replace = array('&', '<', '>', '"', "'");
	for($i = 0; $i < count($search); $i++)
	{
		$str = str_replace($search, $replace, $str);
	}
	return $str;
}

function isLogin($login)
{
	global $db;
	$request = $db->prepare('SELECT login FROM users WHERE login = ? LIMIT 1');
	$request->execute(array($login));
	$result = $request->fetch();
	return $result['login'];
}

function isAdmin($array)
{
	return isset($array['id']) && $array['level'] == 2;
}

function GetDatabase($db_host, $db_name, $db_login, $db_pass) 
{
	return new PDO("mysql:host={$db_host};dbname={$db_name}", $db_login, $db_pass);
}

?>