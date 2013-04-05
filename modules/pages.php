<?php

defined('SITE') or die();

function content()
{
	global $db, $title;
	
	if(isset($_GET['id']))
	{
		$request = $db->prepare('SELECT * FROM pages WHERE id = ? LIMIT 1');
		$request->execute(array($_GET['id']));
		$result = $request->fetch();
		
		if($result['id'])
		{
			$title = $result['title'];
			echo $result['code'];
		}
		else
		{
			echo '<h1>Страницы не существует</h1>';
		}
	}
	else
	{
		if($_SESSION['level'] == 2)
		{
			$request = $db->query('SELECT * FROM pages');
			$result = $request->fetchAll();
			
			echo '<h1>Редактор страниц</h1><br>';
			echo '<table class = "table" cellspacing = 0>';
			echo '<tr><th>№</th><th>ID</th><th>Заголовок</th><th>Операции</th></tr>';
			for($i = 1; $i <= $request->rowCount(); $i++)
			{ 
				echo <<<HTML
<tr align = center><td>{$i}</td><td>{$result[$i-1]['id']}</td><td><div>{$result[$i-1]['title']}</div></td><td><input type = button value = "CNG" onclick = "ChangePage({$result[$i-1]['id']});"><input type = button value = "DEL" onclick = "DeletePage({$result[$i-1]['id']});"></td></tr>			
HTML;
			}
			echo '</table><br><div id = "longBar"></div><br>'; 
?>
			<h2>Добавить страницу</h2>
			<b>Заголовок страницы</b><br>
			<input id = "titleField" class = "articleField"><br>
			<b>Код страницы</b><br>
			<textarea id = "codeField" class = "bigTextField"></textarea><br>
			<input id = "textButton" type = "button" class = "enterButton" value = "Добавить">

			<script>
			$('#textButton').click(function()
			{
				AddPage();
			});
			
			function DeletePage(id)
			{
				if(confirm('Удалить страницу?'))
				{
					$.ajax
					({
						type: 'POST',
						async: true,
						url: 'index.php',
						data: 'n=31&id=' + id,
						dataType: 'json',
						success: function(msg) 
						{
							if(msg.answer == 'OK')
							{
								SetNotice('Страница удалена');
								location = 'pages';
							}
						}
					});
				}
			}
			function UpdatePage(id)
			{
				$.ajax
				({
					type: 'POST',
					async: true,
					url: 'index.php',
					data: 'n=33&id=' + id + '&code=' + htmlspecialchars($('#codeField').val()) + '&title=' + $('#titleField').val(),
					dataType: 'json',
					success: function(msg) 
					{
						if(msg.answer == 'OK')
						{
							SetNotice('Страница изменена');
							location = 'pages';
						}
					}
				});
			}
			function ChangePage(id)
			{
				$.ajax
				({
					type: 'POST',
					async: true,
					url: 'index.php',
					data: 'n=32&id=' + id,
					dataType: 'json',
					success: function(msg) 
					{
						if(msg.answer == 'OK')
						{
							$('#titleField').val(msg.title);
							$('#codeField').val(msg.code);
							$('#textButton').unbind('click');
							$('#textButton').val('Изменить').click(function()
							{
								UpdatePage(id);
							});
						}
					}
				});
			}
			function AddPage()
			{
				$.ajax
				({
					type: 'POST',
					async: false,
					url: 'index.php',
					data: 'n=30&code=' + htmlspecialchars($('#codeField').val()) + '&title=' + $('#titleField').val(),
					dataType: 'json',
					success: function(msg) 
					{
						if(msg.answer == 'OK')
						{
							SetNotice('Страница добавлена');
							location = 'pages';
						}
					}
				});
			}
			</script>
<?php
		}
		else
		{
			header('Location: enter');
		}
	}
}

?>
