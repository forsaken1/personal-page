<?php

defined('SITE') or die;

$menu = '';
$mode = isset($_SESSION['id']) ? ($_SESSION['level'] == 0 ? 1 : 2) : 0;
$enter = array('Войти', 'Профиль', 'Админка');
$menu_url = null;

if($cfg['cache'] && file_exists('cashe/main_menu'.$mode.'.cache'))
{
	$fp = fopen($f = 'cache/main_menu'.$mode.'.cache', 'r');
	$menu = fread($fp, filesize($f));
	fclose($fp);
	echo $menu;
}
else
{
	$request = $db->query('SELECT * FROM menu ORDER BY sort');
	$result = $request->fetchAll();
	
	$menu .= '<div id = "menu"><ul>';
	for($i = 0; $i < $request->rowCount() - 1; $i++)
	{
		$menu .= '<li><a href = "'.$result[$i]['url'].'">'.$result[$i]['name'].'</a></li>';
	}
	$menu .= '<li><a href = "'.$result[$request->rowCount() - 1]['url'].'">'.$result[$request->rowCount() - 1]['name'].'</a></li>';
	$menu .= '<li class = "lastMenu"><a href="/enter">';
	$menu .= $enter[$mode];
	$menu .= '</a></li>';
	$menu .= '</ul></div>';
	
	if($cfg['cache'])
	{
		$fp = fopen('cache/main_menu'.$mode.'.cache', 'w');
		fwrite($fp, $menu);
		fclose($fp);
	}
	echo $menu;
}

?>