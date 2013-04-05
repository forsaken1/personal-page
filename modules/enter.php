<?php

defined('SITE') or die;
	
function content()
{
	global $db;
	
	if(isset($_SESSION['id'])) 
	{
		echo '<h3>Добро пожаловать, ', $_SESSION['login'], '</h3><br>';
		echo '<h3><a href = "logout">Выйти</a></h3><br>';
		
		if($_SESSION['level'] == 2)
		{
			$request = $db->query('SELECT * FROM users');
			$result = $request->fetchAll();
			
			echo '<h3><a href = "pages">Редактор страниц</a><h3>';
			echo '<h3><a href = "menu_edit">Редактор меню</a><h3>';
			echo '<div id = "longBar"></div>';
			echo '<br><h3>Пользователи сайта</h3><br>';
			echo '<table class = "table" cellspacing = 0>';
			echo '<tr><th>No</th><th>Логин</th><th>E-mail</th><th>Level</th><th>Дата</th></tr>';
			for($i = 0; $i < $request->rowCount(); $i++)
			{
				echo '<tr align = center><td>', $i + 1, '</td><td>', $result[$i]['login'], '</td><td>', 
				$result[$i]['mail'] ? $result[$i]['mail'] : '---', '</td><td>', $result[$i]['level'], '</td><td>', 
				$result[$i]['date'],'</td></tr>';
			}
			echo '</table>';
			echo '<div id = "longBar"></div>';
		}
	}
	else 
	{
		echo <<<HTML
		<h1>Войти на сайт</h1><br>
		<h3>Авторизуйтесь</h3><br>
		<input id = 'loginField' class = 'enterField' maxlength = 25><br>
		<input id = 'passField'  class = 'enterField' maxlength = 25 type = password><br>
		<input id = 'enterButton' type = 'button' class = 'enterButton' value = 'Войти' onclick = 'Enter()'><br><br>
		<h4>или <a href = 'registration'>зарегистрируйтесь</a> на сайте</h4>
HTML;
	}
} 
?>

<script>
function Enter()
{
	$.ajax
	({
		type: 'POST',
		url: 'index.php',
		async: true,
		dataType: 'json',
		data: 'n=0&login=' + $('#loginField').val() + '&pass=' + $('#passField').val(),
		success: function(msg)
		{
			if(msg.answer == 'OK')
			{
				SetNotice('Вы вошли на сайт');
				location = 'enter';
			}
			else
				SetWarning('Неверный логин или пароль!');
		},
	});	
}
</script>
