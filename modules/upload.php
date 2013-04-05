<?php

defined('SITE') and isAdmin($_SESSION) or die;

function content()
{
?>
<style>

#upload
{
	border: 1px solid 	#1111FF;
	background-color: 	#CCCCFF;
	width: 				120px;
	padding: 			5px;
}

</style>
<script type = "text/javascript" src = "/js/ajaxupload.3.5.js"></script>
<script type = "text/javascript">

$(function()
{
	var btnUpload = $('#upload');
	new AjaxUpload(btnUpload, 
	{
		action: 'index.php?n=0',
		name: 'uploadfile',
		onSubmit: function(file, ext)
		{
			if(!(ext && /^(jpg|png|jpeg|gif)$/.test(ext)))
			{ 
				SetWarning('Только JPG, PNG или GIF изображения!');
				return false;
			}
			SetNotice('Загрузка...');
		},
		onComplete: function(file, response)
		{
			var arr = response.split(';');
			if(arr[0] === 'OK')
			{
				SetNotice('Изображение загружено');
				$('#files').append('<tr align = center><td>n</td><td>' + arr[1] + '</td><td><input onclick = "DeleteImage(' + arr[2] + ')" value = "DEL" type = button></td></tr>');
			}
			else
			{
				SetWarning('Ошибка при загрузке изображения!');
			}
		}
	});	
});

function DeleteImage(id)
{
	if(confirm('Удалить изображение?'))
	{
		$.ajax
		({
			type: 'POST',
			asyns: true,
			url: 'index.php',
			data: 'n=50&id=' + id,
			dataType: 'json',
			success: function(msg)
			{
				if(msg.answer == 'OK')
				{
					SetNotice('Изображение удалено');
					location = 'upload';
				}
			}
		});
	}
}

</script>
<div id = "mainbody">
	<h1>Загрузка изображений</h1><br>
	<div id = "upload">
		<h3>Загрузить</h3>
	</div><br>
	<table id = 'files' class = 'table' cellspacing = 0>
	<tr><th>№</th><th>URL</th><th>Операции</th></tr>
<?php
	global $db;
	$require = $db->query('SELECT * FROM images');
	$result = $require->fetchAll();
	
	for($i = 0; $i < $require->rowCount(); $i++)
	{
		echo '<tr align = center><td>', $i + 1, '</td><td>', $result[$i]['url'], '</td><td><input onclick = "DeleteImage(', $result[$i]['id'],')" type = button value = "DEL"></td></tr>';
	}
?>
	</table>
</div>

<?php
}

?>