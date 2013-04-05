<?php
	
defined('SITE') and !isset($_SESSION['id']) or die;
	
function content()
{ 
?>
<h1>Регистрация</h1><br>
<h3>Заполните формы</h3><br>
<h4>Логин</h4>
<input id = 'loginField' class = 'enterField' onblur = 'checkLogin()'><br>
<h4>E-mail</h4>
<input id = 'mailField' class = 'enterField'><br>
<h4>Пароль</h4>
<input id = 'passField' class = 'enterField' type = 'password'><br>
<h4>Повторите пароль</h4>
<input id = 'dpassField' class = 'enterField' type = 'password'><br>
<input id = 'regButton' type = 'button' class = 'enterButton' value = 'Зарегистрироваться' onclick = 'checkAll()'><br>

<script>
function checkPassword()
{
	if($('#passField').val().length < 6)
	{
		SetWarning('Длина пароля - не менее 6-ти символов!');
		return 0; 
	}		
	return 1;
}
	
function checkPasswordConf()
{
	if(checkPassword())
	{
		if($('#passField').val() == $('#dpassField').val())
		{
			return 1;
		}
		else
		{
			SetWarning('Пароли не совпадают!');
		}
	}
	return 0;
}
	
function checkLogin()
{
	var check = 0;
		
	if($('#loginField').val().length == 0)
	{ 
		SetWarning('Введите логин!');
		return 0;
	}
	$.ajax
	({
		type: 'POST',
		async: true,
		url: 'index.php',
		dataType: 'json',
		data: 'n=10&login=' + $('#loginField').val(),
		success: function(msg) 
		{
			if(msg.answer == 'OK')
			{
				check = 1;
				SetNotice('Логин корректен');
			}
			else
			{
				SetWarning('Данный логин уже зарегистрирован!');
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