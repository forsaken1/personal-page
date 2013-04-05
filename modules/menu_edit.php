 <?php
 
 defined('SITE') or die;
 
 function content()
 {
	if(!isset($_SESSION['id']) || (isset($_SESSION['id']) && $_SESSION['level'] < 2))
	{
		exit;
	}
	global $db;
	
	$request = $db->query('SELECT * FROM menu');
	$result = $request->fetchAll();
	
	
	echo '<h1>Редактор меню</h1><br>';
	echo '<table cellspacing = 0 class = "table">';
	echo '<tr><th>№</th><th>Название</th><th>URL</th><th>П</th><th>Операции</th></tr>';
	for($i = 1; $i <= $request->rowCount(); $i++)
	{
		echo <<<HTML
		<tr align = center><td>$i</td><td><div>{$result[$i-1]['name']}</div></td><td><div>{$result[$i-1]['url']}</div></td><td><div>{$result[$i-1]['sort']}</div></td><td><input type = button value = "CHG" onclick = "ChangeMenu({$result[$i-1]['id']})"><input onclick = "DeleteMenu({$result[$i-1]['id']})" type = button value = "DEL"></td></tr>
HTML;
	}
	echo '</table><br>';
	
	echo <<<HTML
		<h2>Добавить пункт меню</h2><br>
		<b>Название</b><br>
		<input class = "enterField" id = "nameField"><br>
		<b>URL</b><br>
		<input class = "enterField" id = "urlField"><br>
		<b>Позиция</b><br>
		<input class = "enterField" id = "sortField"><br>
		<input class = "enterButton" id = "pageButton" type = button value = "Добавить">
HTML;
?>
<script>

$('#pageButton').click(function()
{
	AddPage();
});

function ChangeMenu(id)
{
	$.ajax
	({
		type: 'POST',
		url: 'index.php',
		async: true,
		dataType: 'json',
		data: 'n=42&id=' + id,
		success: function(msg)
		{
			if(msg.answer == 'OK')
			{
				$('#nameField').val(msg.name);
				$('#urlField').val(msg.url);
				$('#sortField').val(msg.sort);
				$('#pageButton').unbind('click');
				$('#pageButton').val('Изменить').click(function()
				{
					UpdateMenu(id);
				});
			}
		},
	});	
}

function DeleteMenu(id)
{
	if(confirm('Удалить пункт меню?'))
	{
		$.ajax
		({
			type: 'POST',
			url: 'index.php',
			async: true,
			dataType: 'json',
			data: 'n=41&id=' + id,
			success: function(msg)
			{
				if(msg.answer == 'OK')
				{
					SetNotice('Пункт меню удален');
					location = 'menu_edit';
				}
			},
		});	
	}
}

function UpdateMenu(id)
{
	$.ajax
	({
		type: 'POST',
		url: 'index.php',
		async: true,
		dataType: 'json',
		data: 'n=43&id=' + id + '&name=' + $('#nameField').val() + '&url=' + $('#urlField').val() + '&sort=' + $('#sortField').val(),
		success: function(msg)
		{
			if(msg.answer == 'OK')
			{
				SetNotice('Пункт меню изменен');
				location = 'menu_edit';
			}
		},
	});	
}

function AddPage()
{
	$.ajax
	({
		type: 'POST',
		url: 'index.php',
		async: true,
		dataType: 'json',
		data: 'n=40&name=' + $('#nameField').val() + '&url=' + $('#urlField').val() + '&sort=' + $('#sortField').val(),
		success: function(msg)
		{
			if(msg.answer == 'OK')
			{
				SetNotice('Пункт меню добавлен');
				location = 'menu_edit';
			}
		},
	});	
}
 
</script>
<?php
}
?>