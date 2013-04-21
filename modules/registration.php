<?php
	
defined('SITE') and !isset($_SESSION['id']) or die;
	
function content()
{ 
?>
<h1>Регистрация</h1><br>
<h3>Заполните формы</h3><br>

<table cellspacing = 2 align = center>
	<tr>
		<td><h5>Логин</h5></td>
		<td><input id = 'loginField' class = 'enterField' onblur = 'checkLogin()'></td>
		<td style = 'color: red;  font-size: 8pt; position: absolute; margin-top: 5px;'><span id = 'loginM'></span></td>
	</tr>
	
	<tr>
		<td><h5>E-mail</h5></td>
		<td><input id = 'mailField' class = 'enterField'></td>
		<td style = 'color: red;  font-size: 8pt; position: absolute; margin-top: 5px;'><span id = 'mailM'></span></td>
	</tr>
	
	<tr>
		<td><h5>Пароль</h5></td>
		<td><input id = 'passField' class = 'enterField' type = 'password' onblur = 'checkPassword()'></td>
		<td style = 'color: red;  font-size: 8pt; position: absolute; margin-top: 5px;'><span id = 'passM'></span></td>
	</tr>
	
	<tr>
		<td><h5>Повторите пароль</h5></td>
		<td><input id = 'dpassField' class = 'enterField' type = 'password' onblur = 'checkPasswordConf()'></td>
		<td style = 'color: red;  font-size: 8pt; position: absolute; margin-top: 5px;'><span id = 'passConfM'></span></td>
	</tr>
	
	<tr>
		<td></td>
		<td><input id = 'regButton' type = 'button' class = 'enterButton' value = 'Зарегистрироваться' onclick = 'checkAll()'></td>
		<td></td>
	</tr>
</table>

<script>
function checkPassword()
{
	if($('#passField').val().length < 6)
	{
		$('#passM').text('Длина пароля - не менее 6-ти символов!');
		return false; 
	}
	else
	{
		$('#passM').text('OK');
		return true;
	}
}
	
function checkPasswordConf()
{
	if(checkPassword())
	{
		if($('#passField').val().toString() == $('#dpassField').val().toString())
		{
			$('#passConfM').text('OK');
			return true;
		}
		else
		{
			$('#passConfM').text('Пароли не совпадают!');
		}
	}
	return false;
}
	
function checkLogin()
{
	var check = false;
		
	if($('#loginField').val().length == 0)
	{ 
		$('#loginM').text('Введите логин!');
		return false;
	}
	$.ajax
	({
		type: 'POST',
		async: false,
		url: 'index.php',
		dataType: 'json',
		data: 'n=10&login=' + $('#loginField').val(),
		success: function(msg) 
		{
			if(msg.answer == 'OK')
			{
				check = true;
				$('#loginM').text('OK');
			}
			else
			{
				$('#loginM').text('Данный логин уже зарегистрирован!');
			}
		}
	});	
	return check;
}
	
function checkAll() 
{
	if(checkLogin() && checkPassword() && checkPasswordConf()) 
	{
		registration();
	}
}
	
function registration()
{
	$.ajax
	({
		type: 'POST',
		async: false,
		url: 'index.php',
		data: 'n=11&login=' + $('#loginField').val() + '&mail=' + $('#mailField').val() + '&pass=' + $('#passField').val(),
		complete: function() 
		{
			SetNotice('Вы успешно зарегистрировались');
			location = 'enter';
		}
	});	
}
</script><?php
}
?>