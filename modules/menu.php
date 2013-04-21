<?php

defined('SITE') or die;

$menu = '';
$mode = isset($_SESSION['id']) ? ($_SESSION['level'] == 0 ? 1 : 2) : 0;
$enter = array(
'<a href="/enter">Войти</a>',
 
'<a href = "/enter">Профиль</a>
<ul>
	<li><a>Выйти</a></li>
</ul>', 

'<a href="/enter">Админка</a>'
);
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
	$menu .= '<li>';
	$menu .= $enter[$mode];
	$menu .= '</li>';
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