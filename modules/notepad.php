<?php

defined('SITE') or die;

function content()
{
	global $db;
	echo '<h1>Блокнот</h1><br>';
	$request = $db->query('SELECT DISTINCT topic FROM articles WHERE my = 0');
	$result = $request->fetchAll();
			
	for($i = 0; $i < $request->rowCount(); $i++)
	{
		echo '<a href = "/articles/', $result[$i][0],'"><span>', $result[$i][0], '</span></a><br>';
	}
	echo '<div id = "longBar"></div><br>';
			
	$request = $db->query('SELECT * FROM articles WHERE view = 1 AND my = 0 ORDER BY topic');
	$result = $request->fetchAll();
		
	for($i = 0; $i < $request->rowCount(); $i++)
	{
		echo '<a class = "articleLink" href = "/articles/', $result[$i]['id'], '">
		<div class = "articleBlock">
			<div class = "articleHeadBlock"><b>Заголовок: </b>', $result[$i]['title'], '</div>
			<div class = "articleTextBlock">', $result[$i]['intro'], '</div>
			<div class = "articleFootBlock"><b>Дата: </b>', $result[$i]['date'],'<b> Тема: </b>', $result[$i]['topic'],'<b> Автор: </b>', $result[$i]['author'],'</div>
		</div></a><br>';
	}
}

?>