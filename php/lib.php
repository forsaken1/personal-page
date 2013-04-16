<?php

defined('SITE') or die;

function DBFetch($q, $arr)
{
	global $db;
	$request = $db->prepare($q);
	$request->execute($arr);
	return $request->fetch();
}

function DBFetchAll($q, $arr)
{
	global $db;
	$request = $db->prepare($q);
	$request->execute($arr);
	return $request->fetchAll();
}

function DBExecute($q, $arr)
{
	global $db;
	$request = $db->prepare($q);
	$request->execute($arr);
}

function DBQuery($q)
{
	global $db;
	$request = $db->query($q);
}

function DBQueryFetchAll($q)
{
	global $db;
	$request = $db->query($q);
	return $request->fetchAll();
}

function HTMLSCDecode($str)
{
	$search = array('%amp;', '%lt;', '%gt;', '%quot;', "'", '%ques;', '%plus;');
	$replace = array('&', '<', '>', '"', "'", '?', '+');
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