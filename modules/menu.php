<?php

defined('SITE') or die;

$menu = '';
$mode = (isset($_SESSION['id']) ? $_SESSION['level'] : 0);
$enter = array('Войти', 'Профиль', 'Админка');

if($cfg['cashe'] && file_exists('cashe/main_menu'.$mode.'.cashe'))
{
	$fp = fopen($f = 'cashe/main_menu'.$mode.'.cashe', 'r');
	$menu = fread($fp, filesize($f));
	fclose($fp);
	echo $menu;
}
else
{
	$request = $db->query('SELECT * FROM menu ORDER BY sort');
	$result = $request->fetchAll();
	
	$menu .= '<div id="menu"><ul>';
	$menu .= '<li class = "firstMenu"><a href = "'.$result[0]['url'].'">'.$result[0]['name'].'</a></li>';
	for($i = 1; $i < $request->rowCount() - 1; $i++)
	{
		$menu .= '<li><a href = "'.$result[$i]['url'].'">'.$result[$i]['name'].'</a></li>';
	}
	$menu .= '<li><a href = "'.$result[$request->rowCount() - 1]['url'].'">'.$result[$request->rowCount() - 1]['name'].'</a></li>';
	$menu .= '<li class = "lastMenu"><a href="/enter">';
	$menu .= $enter[$mode];
	$menu .= '</a></li>';
	$menu .= '</ul></div>';
	
	if($cfg['cashe'])
	{
		$fp = fopen('cashe/main_menu'.$mode.'.cashe', 'w');
		fwrite($fp, $menu);
		fclose($fp);
	}
	echo $menu;
}

?>