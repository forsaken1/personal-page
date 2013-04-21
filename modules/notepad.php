<?php

defined('SITE') or die;

function content()
{
	global $db;
	echo '<h1>Блокнот</h1><br>';
	$request = $db->query('SELECT DISTINCT topic FROM articles WHERE my = 0');
	$result = $request->fetchAll();
	
	echo '<table cellspacing = 0 id = "notepad" style = "width: 100%;"><tr><th><div class = "artTopic">Темы</div></th><th><div class = "artTopic">Статьи</div></th></tr><tr><td  valign = top align = left>';
	
	for($i = 0; $i < $request->rowCount(); $i++)
	{
		echo '<a onmouseout = "TopicUnselected()" onmouseover = "TopicSelected(this)" class = "articleLink" href = "/articles/', $result[$i][0],'">
			<div class = "topic">
				<span class = "artTopic">', $result[$i][0], '</span>
			</div>
		</a>';
	}
	
	echo '</td><td valign = top align = right>';
			
	$request = $db->query('SELECT * FROM articles WHERE view = 1 AND my = 0 ORDER BY topic');
	$result = $request->fetchAll();
		
	for($i = 0; $i < $request->rowCount(); $i++)
	{
		echo '<a class = "articleLink" href = "/articles/', $result[$i]['id'], '">
			<div class = "articleBlock">
				<span class = "artTopic">', $result[$i]['topic'], '::</span>
				<span class = "artTitle">', $result[$i]['title'],'</span>
			</div>
		</a>';
	}
	echo '</td></tr></table>'; 
?>

<script>

var selectedArticles = [];

function TopicSelected(handle)
{
	var art_block = document.getElementById('notepad').children[0].children[1].children[1];
	while(selectedArticles.length != 0)
	{
		var popped = selectedArticles.pop();
		$(popped).removeClass('selected');
	}		
	for(var i = 0; i < art_block.children.length; i++)
	{
		if(art_block.children[i].children[0].children[0].innerHTML.toString() == (handle.children[0].children[0].innerHTML + '::').toString())
		{
			$(art_block.children[i].children[0]).addClass('selected');
			selectedArticles.push(art_block.children[i].children[0]);
		}
	}
}

function TopicUnselected()
{
	while(selectedArticles.length != 0)
	{
		var popped = selectedArticles.pop();
		$(popped).removeClass('selected');
	}	
}

</script>

<?php
}

?>