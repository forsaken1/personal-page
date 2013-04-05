<?php

defined('SITE') or die;
	
function content()
{
	global $db;
	if(isset($_GET['id'])) /* GET ARTICLE */
	{
		if(is_numeric($_GET['id']))
		{
			$request = $db->prepare('SELECT * FROM articles WHERE id = ?');
			$request->execute(array($_GET['id']));
			$result = $request->fetch();
			
			echo '<h1>', $result['title'], '</h1><br>';
			echo '<h4>', $result['date'], '</h4>';
			echo '<div id = "longBar"></div>';
			echo '<div id = "articleText" align = left>', $result['text'], '</div>';
			echo '<div id = "longBar"></div><br>';
			echo '<h2>Комментарии</h2><br>';
			echo '<div id = "commentField">';
				
			$request = $db->prepare('SELECT * FROM comments WHERE article_id = ? AND view = 1');
			$request->execute(array($_GET['id']));
			$result = $request->fetchAll();
			for($i = 0; $i < $request->rowCount(); $i++)
			{
				echo '<div id = "comment_', $result[$i]['id'],'" class = "commentBlock"><div class = "commentHeadBlock"><b>Дата: </b>', $result[$i]['date'], '<b> Автор: </b>', $result[$i]['author'], '<a class = "deleteCommentButton" href = "javascript://" onclick = "DeleteComment(', $result[$i]['id'], ')">удалить</a></div><div class = "commentTextBlock" align = left>', $result[$i]['text'], '</div></div><br>';
			}
			echo '</div>';
		
			if(isset($_SESSION['id']))
			{ 
?>
			<textarea id = "commentText"></textarea><br>
			<input onclick = "SendComment(<?=$_GET['id']?>,<?=$_SESSION['user_id']?>,'<?=$_SESSION['login']?>')" type = button value = "Отправить" id = "sendComment"><br>
			<script>
			function DeleteComment(id)
			{
				$.ajax
				({
					type: 'POST',
					async: true,
					url: 'index.php',
					data: 'n=26&id=' + id,
					dataType: 'json',
					success: function(msg)
					{
						if(msg.answer == 'OK') 
						{
							SetNotice('Комментарий удален');
							$('#comment_' + id).remove();
						}
					}
				});
			}
			function SendComment(article_id, author_id, author) 
			{
				$.ajax
				({
					type: 'POST',
					async: true,
					url: 'index.php',
					data: 'n=24&article_id=' + article_id + '&author_id=' + author_id + '&text=' + htmlspecialchars($('#commentText').val()) + '&author=' + author,
					dataType: 'json',
					success: function(msg) 
					{
						if(msg.answer == 'OK') 
						{
							SetNotice('Комментарий добавлен');
							$('#commentField').append('<div id = "comment_' + msg.id + '" class = "commentBlock"><div class = "commentHeadBlock"><b>Дата: </b>' + msg.date + '<b> Автор: </b>' + author + '<a class = "deleteCommentButton" href = "javascript://" onclick = "DeleteComment(' + msg.id + ')">удалить</a></div><div class = "commentTextBlock" align = left>' + $('#commentText').val() + '</div></div><br>');
							$('#commentText').val('');
						}
						else
						{
							SetWarning('Ошибка при записи в базу данных!');
						}
					}
				});
			}
			</script>
<?php
			}
		}
		else
		{
			
		}
	}
	else
	{
		echo '<h1>Статьи</h1><br>'; /* GET ARTICLE LIST */
		
		$adminMode = false;
		if(isset($_SESSION['id']) && $_SESSION['level'] == 2)
		{
			$adminMode = true;
		}
		
		if($adminMode)
		{ 
			$request = $db->query('SELECT * FROM articles ORDER BY topic');
			$result = $request->fetchAll(); 
?>							
			<table class = "table" cellspacing = 0>
			<tr><th>No</th><th>П</th><th>Автор</th><th>Тема</th><th>Заголовок</th><th>Операции</th></tr>
<?php			
			for($i = 0; $i < $request->rowCount(); $i++) 
			{
				echo '<tr align = center><td>', $i + 1, '</td><td><input', $result[$i]['view'] ? ' checked ' : ' ', 'type = "checkbox" onclick = "ViewArticle(', $result[$i]['id'], ')" id = "cb_', $result[$i]['id'],'"></td><td>', $result[$i]['author'], '</td><td>', $result[$i]['topic'], '</td><td><a href = "articles/', $result[$i]['id'], '">', $result[$i]['title'], '</a></td><td align = center><input type = button value = "CHG" onclick = "ChangeArticle(', $result[$i]['id'], ')"><input type = button value = "DEL" onclick = "DeleteArticle(', $result[$i]['id'], ')"></td></tr>';
			}
?>				
			</table><br><br>
			<div id = 'longBar'></div><br>
			<h2>Добавить статью</h2><br>
			<h4>Тема</h4><input id = "topicField" class = "articleField"><br>
			<h4>Заголовок</h4><input id = "titleField" class = "articleField"><br>
			<h4>Предисловие<h4><textarea maxlength = 500 id = "introField" class = "smallTextField"></textarea><br>
			<h4>Текст статьи</h4><textarea id = "textField" class = "bigTextField"></textarea><br>
			<input id = "addArticleButton" class = "enterButton" type = "button" value = "Добавить"><br>
			<script>
			$('#addArticleButton').click(function() 
			{ 
				AddArticle(); 
			});
					
			function ViewArticle(id)
			{
				var checked = 0;
				if($('#cb_' + id).is(':checked'))
				{
					checked = 1;
				}
				$.ajax
				({
					type: 'POST',
					async: true,
					url: 'index.php',
					data: 'n=25&id=' + id + '&checked=' + checked,
					dataType: 'json',
					success: function(msg)
					{
						if(msg.answer == 'OK')
						{
							SetNotice('Видимость статьи изменена');
						}
					}
				});
			}
			function AddArticle() 
			{
				$.ajax
				({
					type: 'POST',
					async: false,
					url: 'index.php',
					data: 'n=20&topic=' + $('#topicField').val() + '&title=' + $('#titleField').val() + '&text=' + htmlspecialchars($('#textField').val()) + '&intro=' + htmlspecialchars($('#introField').val()),
					dataType: 'json',
					success: function() 
					{
						SetNotice('Статья добавлена');
						location = 'articles';
					}
				});
			}
			function DeleteArticle(id) 
			{
				if(confirm('Хотите удалить эту статью?')) 
				{
					$.ajax
					({
						type: 'POST',
						async: true,
						url: 'index.php',
						data: 'n=21&id=' + id,
						dataType: 'json',
						success: function(msg)
						{
							SetNotice('Статья удалена');
							location = 'articles';
						}
					});
				}
			}
			function ChangeArticle(id) 
			{
				$.ajax
				({
					type: 'POST',
					async: true,
					url: 'index.php',
					data: 'n=22&id=' + id,
					dataType: 'json',
					success: function(msg) 
					{
						$('#topicField').val(msg.topic);
						$('#titleField').val(msg.title);
						$('#introField').val(msg.intro);
						$('#textField').val(msg.text);
						$('#addArticleButton').unbind('click');
						$('#addArticleButton').val('Изменить').click(function() 
						{ 
							UpdateArticle(id); 
						});
					}
				});
			}
			function UpdateArticle(id) 
			{
				$.ajax
				({
					type: 'POST',
					async: true,
					url: 'index.php',
					data: 'n=23&id=' + id + '&topic=' + $('#topicField').val() + '&title=' + $('#titleField').val() + '&text=' + htmlspecialchars($('#textField').val()) + '&intro=' + htmlspecialchars($('#introField').val()),
					dataType: 'json',
					success: function(msg) 
					{
						if(msg.answer == 'OK')
						{
							SetNotice('Статья изменена');
							location = 'articles';
						}
						else
						{
							SetWarning('Ошибка при записи в базу данных!');
						}
					}
				});
			}
		</script>
<?php
		}
		else
		{
			$request = $db->query('SELECT DISTINCT topic FROM articles');
			$result = $request->fetchAll();
			
			for($i = 0; $i < $request->rowCount(); $i++)
			{
				echo '<a href = "', $result[$i][0],'"><div>', $result[$i][0],'</div></a>';
			}
			echo '<div id = "longBar"></div><br>';
			
			$request = $db->query('SELECT * FROM articles WHERE view = 1 ORDER BY topic');
			$result = $request->fetchAll();
				
			for($i = 0; $i < $request->rowCount(); $i++) 
			{
				echo '<a class = "articleLink" href = "/articles/', $result[$i]['id'], '"><div class = "articleBlock"><div class = "articleHeadBlock"><b>Заголовок: </b>', $result[$i]['title'], '</div><div class = "articleTextBlock">', $result[$i]['intro'], '</div><div class = "articleFootBlock"><b>Дата: </b>', $result[$i]['date'],'<b> Тема: </b>', $result[$i]['topic'],'<b> Автор: </b>', $result[$i]['author'],'</div></div></a><br>';
			}
		}
	}
}

?>