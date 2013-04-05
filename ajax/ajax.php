<?php

defined('SITE') or die;
	
if(isset($_POST['n'])) 
{	
	switch($_POST['n'])
	{
		/* AUTHENTIFICATION */
		case 0:
		{
			$request = $db->prepare('SELECT * FROM users WHERE login = ? AND pass = ?');
			$request->execute(array($_POST['login'], md5($_POST['pass'])));
			$result = $request->fetch();
			if($result['login']) 
			{
				$_SESSION['id'] = md5($result['login'].rand());
				$_SESSION['user_id'] = $result['id'];
				$_SESSION['login'] = $result['login'];
				$_SESSION['level'] = $result['level'];
				echo json_encode(array('answer' => 'OK'));
			}
			else
			{
				echo json_encode(array('answer' => 'NO'));
			}			
			exit;
		}
		/* REGISTRATION */
		case 10: //check login
		{
			if(!isLogin($_POST['login'])) 
			{
				echo json_encode(array('answer' => 'OK'));
			}
			else 
			{
				echo json_encode(array('answer' => 'NO'));
			}
			exit;
		}
		case 11: //add user
		{ 
			if(!isLogin($_POST['login'])) 
			{
				$request = $db->prepare("INSERT INTO users (login, pass, mail, date) VALUES (?, ?, ?, ?)");
				$request->execute(array($_POST['login'], md5($_POST['pass']), $_POST['mail'], date('H:i j.m.o')));
			}
			exit;
		}
		/* ARTICLES */
		case 20: //add article
		{
			if(isAdmin($_SESSION))
			{
				$request = $db->prepare("INSERT INTO articles (topic, title, intro, text, date, author, author_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
				$request->execute(array($_POST['topic'], $_POST['title'], HTMLSCDecode($_POST['intro']), HTMLSCDecode($_POST['text']), date('H:i j.m.o'), $_SESSION['login'], $_SESSION['user_id']));
			}
			exit;
		}
		case 21: //delete article
		{
			if(isAdmin($_SESSION))
			{
				$request = $db->prepare('DELETE FROM articles WHERE id = ?');
				$request->execute(array($_POST['id']));
				
				$request = $db->prepare('DELETE FROM comments WHERE article_id = ?');
				$request->execute(array($_POST['id']));
			}
			exit;
		}
		case 22: //for update
		{
			if(isAdmin($_SESSION))
			{
				$request = $db->prepare('SELECT * FROM articles WHERE id = ?');
				$request->execute(array($_POST['id']));
				$result = $request->fetch();
				echo json_encode(array('intro' => $result['intro'], 'topic' => $result['topic'], 'title' => $result['title'], 'text' => $result['text']));
			}
			exit;
		}
		case 23: //update article
		{
			if(isAdmin($_SESSION))
			{
				$request = $db->prepare('UPDATE articles SET intro = ?, topic = ?, title = ?, text = ? WHERE id = ?');
				$request->execute(array($_POST['intro'], $_POST['topic'], HTMLSCDecode($_POST['title']), HTMLSCDecode($_POST['text']), $_POST['id']));
				echo json_encode(array('answer' => 'OK'));
			}
			exit;
		}
		case 24: //add comment
		{
			if(isset($_SESSION['id']))
			{ 
				$request = $db->prepare('INSERT INTO comments (article_id, author, author_id, text, date) VALUES (?, ?, ?, ?, ?)');
				$request->execute(array($_POST['article_id'], $_POST['author'], $_POST['author_id'], HTMLSCDecode($_POST['text']), $date = date('H:i j.m.o')));
				echo json_encode(array('answer' => 'OK', 'date' => $date, 'id' => $db->lastInsertId()));
			}
			exit;
		}
		case 25: //article: change visible
		{
			if(isAdmin($_SESSION))
			{ 
				$request = $db->prepare('UPDATE articles SET view = ? WHERE id = ?');
				$request->execute(array($_POST['checked'], $_POST['id']));
				echo json_encode(array('answer' => 'OK'));
			}
			exit;
		}
		case 26: //delete (hide) comment
		{
			if(isset($_SESSION['id']))
			{
				$request = $db->prepare('UPDATE comments SET view = 0 WHERE id = ?');
				$request->execute(array($_POST['id']));
				echo json_encode(array('answer' => 'OK'));
			}
			exit;
		}
		/* PAGES */
		case 30: //add page
		{
			if(isAdmin($_SESSION))
			{ 
				$request = $db->prepare('INSERT INTO pages (title, code) VALUES (?, ?)');
				$request->execute(array($_POST['title'], HTMLSCDecode($_POST['code'])));
				echo json_encode(array('answer' => 'OK'));
			}
			exit;
		}
		case 31: //delete page
		{
			if(isAdmin($_SESSION))
			{ 
				$request = $db->prepare('DELETE FROM pages WHERE id = ?');
				$request->execute(array($_POST['id']));
				echo json_encode(array('answer' => 'OK'));
			}
			exit;
		}
		case 32: //change page
		{
			if(isAdmin($_SESSION))
			{
				$request = $db->prepare('SELECT * FROM pages WHERE id = ? LIMIT 1');
				$request->execute(array($_POST['id']));
				$result = $request->fetch();
				echo json_encode(array('answer' => 'OK', 'title' => $result['title'], 'code' => $result['code']));
			}
			exit;
		}
		case 33: //update page
		{
			if(isAdmin($_SESSION))
			{
				$request = $db->prepare('UPDATE pages SET title = ?, code = ? WHERE id = ?');
				$request->execute(array($_POST['title'], HTMLSCDecode($_POST['code']), $_POST['id']));
				echo json_encode(array('answer' => 'OK'));
			}
			exit;
		}
		/* MENU */
		case 40: //add menu
		{
			if(isAdmin($_SESSION))
			{
				$request = $db->prepare('INSERT INTO menu (name, url, sort) VALUES (?, ?, ?)');
				$request->execute(array($_POST['name'], $_POST['url'], $_POST['sort']));
				echo json_encode(array('answer' => 'OK'));
			}
			exit;
		}
		case 41: //delete menu
		{
			if(isAdmin($_SESSION))
			{
				$request = $db->prepare('DELETE FROM menu WHERE id = ?');
				$request->execute(array($_POST['id']));
				echo json_encode(array('answer' => 'OK'));
			}
			exit;
		}
		case 42: //change menu
		{
			if(isAdmin($_SESSION))
			{
				$request = $db->prepare('SELECT * FROM menu WHERE id = ? LIMIT 1');
				$request->execute(array($_POST['id']));
				$result = $request->fetch();
				echo json_encode(array('answer' => 'OK', 'name' => $result['name'], 'url' => $result['url'], 'sort' => $result['sort']));
			}
			exit;
		}
		case 43: //update menu
		{
			if(isAdmin($_SESSION))
			{
				$request = $db->prepare('UPDATE menu SET name = ?, url = ?, sort = ? WHERE id = ?');
				$request->execute(array($_POST['name'], $_POST['url'], $_POST['sort'], $_POST['id']));
				echo json_encode(array('answer' => 'OK'));
			}
			exit;
		}
		case 50:
		{
			if(isAdmin($_SESSION))
			{
				$request = $db->prepare('SELECT url FROM images WHERE id = ? LIMIT 1');
				$request->execute(array($_POST['id']));
				$result = $request->fetch();
				
				$request = $db->prepare('DELETE FROM images WHERE id = ?');
				$request->execute(array($_POST['id']));
				if(unlink($result[0]))
				{
					echo json_encode(array('answer' => 'OK'));
				}
				else
				{
					echo json_encode(array('answer' => 'NO'));
				}
			}
			exit;
		}
		default:
		{
			echo 'Suck my dick';
		}
	}
}

if(isset($_GET['n']))
{
	switch($_GET['n'])
	{
		/* UPLOAD IMAGES */
		case 0:
		{
			if(isAdmin($_SESSION))
			{
				$uploaddir = './uploads/images/';
				$file = $uploaddir.basename($_FILES['uploadfile']['name']);
				 
				if(move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file))
				{ 
					$require = $db->prepare('INSERT INTO images (url) VALUES (?)');
					$require->execute(array($file));
					echo 'OK;'.$file.';'.$db->lastInsertId();
				}
				else
				{
					echo 'NO';
				}
			}
			exit;
		}
	}
}

?>