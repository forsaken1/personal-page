<?php

define('SITE', 1);
	
session_start();

require_once 'cfg.php';
require_once 'php/lib.php';

if(!isset($db))
{
	$db = GetDatabase($cfg['db_host'], $cfg['db_name'], $cfg['db_login'], $cfg['db_password']);
}

require_once 'ajax/ajax.php';
	
if($cfg['iterator'])
{
	$request = $db->prepare('INSERT INTO visitors (login, user_agent, remote_addr) VALUES (?, ?, ?)');
	$request->execute(array(isset($_SESSION['login']) ? $_SESSION['login'] : 'none', $_SERVER['HTTP_USER_AGENT'], $_SERVER['REMOTE_ADDR']));
}

$title = 'Сайт визитка Крылова Алексея';

if(isset($_GET['module']))
{
	if(file_exists($file = 'modules/'.$_GET['module'].'.php'))
	{
		require_once $file;
	}
	else
	{
		header('Location: /404');
	}
}
else
{
	header('Location: /pages/1');
}
	
require_once 'templates/template.php';
	
?>