<?php

defined('SITE') or die;
	
if(isset($_POST['n'])) 
{	
	switch($_POST['n'])
	{
		/* AUTHENTIFICATION */
		case 0:
		{
			$result = DBFetch( 'SELECT * FROM users WHERE login = ? AND pass = ?', array($_POST['login'], md5($_POST['pass'])) );
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
		/* ADMIN */
		case 1: //delete cache
		{
			if(isAdmin($_SESSION))
			{
				$dir = './cache/';
				$file = array('main_menu0.cache', 'main_menu1.cache', 'main_menu2.cache');
				$success = true;
				for($i = 0; $i < count($file); $i++)
				{
					if(file_exists($f = $dir.$file[$i]))
					{
						if(!unlink($f))
						{
							$success = false;
						}
					}
				}
				echo json_encode(array('answer' => ($success ? 'OK' : 'NO') ));
			}
			exit;
		}
		/* REGISTRATION */
		case 10: //check login
		{
			echo json_encode(array('answer' => (!isLogin($_POST['login'] ? 'OK' : 'NO')) ));
			exit;
		}
		case 11: //add user
		{ 
			if(!isLogin($_POST['login'])) 
			{
				DBExecute("INSERT INTO users (login, pass, mail, date) VALUES (?, ?, ?, ?)", array($_POST['login'], md5($_POST['pass']), $_POST['mail'], date('H:i d.m.o')));
			}
			exit;
		}
		/* ARTICLES */
		case 20: //add article
		{
			if(isAdmin($_SESSION))
			{
				DBExecute("INSERT INTO articles (topic, title, intro, text, date, author, author_id) VALUES (?, ?, ?, ?, ?, ?, ?)", array($_POST['topic'], $_POST['title'], HTMLSCDecode($_POST['intro']), HTMLSCDecode($_POST['text']), date('H:i d.m.o'), $_SESSION['login'], $_SESSION['user_id']));
			}
			exit;
		}
		case 21: //delete article
		{
			if(isAdmin($_SESSION))
			{
				DBExecute('DELETE FROM articles WHERE id = ?', array($_POST['id']));
				DBExecute('DELETE FROM comments WHERE article_id = ?', array($_POST['id']));
			}
			exit;
		}
		case 22: //for update
		{
			if(isAdmin($_SESSION))
			{
				$result = DBFetch('SELECT * FROM articles WHERE id = ?', array($_POST['id']));
				echo json_encode(array('intro' => $result['intro'], 'topic' => $result['topic'], 'title' => $result['title'], 'text' => $result['text']));
			}
			exit;
		}
		case 23: //update article
		{
			if(isAdmin($_SESSION))
			{
				DBExecute('UPDATE articles SET intro = ?, topic = ?, title = ?, text = ? WHERE id = ?', array($_POST['intro'], $_POST['topic'], HTMLSCDecode($_POST['title']), HTMLSCDecode($_POST['text']), $_POST['id']));
				echo json_encode(array('answer' => 'OK'));
			}
			exit;
		}
		case 24: //add comment
		{
			if(isset($_SESSION['id']))
			{ 
				DBExecute('INSERT INTO comments (article_id, author, author_id, text, date) VALUES (?, ?, ?, ?, ?)', array($_POST['article_id'], $_POST['author'], $_POST['author_id'], HTMLSCDecode($_POST['text']), $date = date('H:i d.m.o')));
				echo json_encode(array('answer' => 'OK', 'date' => $date, 'id' => $db->lastInsertId()));
			}
			exit;
		}
		case 25: //article: change visible
		{
			if(isAdmin($_SESSION))
			{ 
				DBExecute('UPDATE articles SET view = ? WHERE id = ?', array($_POST['checked'], $_POST['id']));
				echo json_encode(array('answer' => 'OK'));
			}
			exit;
		}
		case 26: //delete (hide) comment
		{
			if(isset($_SESSION['id']))
			{
				DBExecute('UPDATE comments SET view = 0 WHERE id = ?', array($_POST['id']));
				echo json_encode(array('answer' => 'OK'));
			}
			exit;
		}
		/* PAGES */
		case 30: //add page
		{
			if(isAdmin($_SESSION))
			{ 
				DBExecute('INSERT INTO pages (title, code) VALUES (?, ?)', array($_POST['title'], HTMLSCDecode($_POST['code'])));
				echo json_encode(array('answer' => 'OK'));
			}
			exit;
		}
		case 31: //delete page
		{
			if(isAdmin($_SESSION))
			{ 
				DBExecute('DELETE FROM pages WHERE id = ?', array($_POST['id']));
				echo json_encode(array('answer' => 'OK'));
			}
			exit;
		}
		case 32: //change page
		{
			if(isAdmin($_SESSION))
			{
				$result = DBFetch('SELECT * FROM pages WHERE id = ? LIMIT 1', array($_POST['id']));
				echo json_encode(array('answer' => 'OK', 'title' => $result['title'], 'code' => $result['code']));
			}
			exit;
		}
		case 33: //update page
		{
			if(isAdmin($_SESSION))
			{
				DBExecute('UPDATE pages SET title = ?, code = ? WHERE id = ?', array($_POST['title'], HTMLSCDecode($_POST['code']), $_POST['id']));
				echo json_encode(array('answer' => 'OK'));
			}
			exit;
		}
		/* MENU */
		case 40: //add menu
		{
			if(isAdmin($_SESSION))
			{
				DBExecute('INSERT INTO menu (name, url, sort) VALUES (?, ?, ?)', array($_POST['name'], $_POST['url'], $_POST['sort']));
				echo json_encode(array('answer' => 'OK'));
			}
			exit;
		}
		case 41: //delete menu
		{
			if(isAdmin($_SESSION))
			{
				DBExecute('DELETE FROM menu WHERE id = ?', array($_POST['id']));
				echo json_encode(array('answer' => 'OK'));
			}
			exit;
		}
		case 42: //change menu
		{
			if(isAdmin($_SESSION))
			{
				$result = DBFetch('SELECT * FROM menu WHERE id = ? LIMIT 1', array($_POST['id']));
				echo json_encode(array('answer' => 'OK', 'name' => $result['name'], 'url' => $result['url'], 'sort' => $result['sort']));
			}
			exit;
		}
		case 43: //update menu
		{
			if(isAdmin($_SESSION))
			{
				DBExecute('UPDATE menu SET name = ?, url = ?, sort = ? WHERE id = ?', array($_POST['name'], $_POST['url'], $_POST['sort'], $_POST['id']));
				echo json_encode(array('answer' => 'OK'));
			}
			exit;
		}
		case 50: //delete file
		{
			if(isAdmin($_SESSION))
			{
				$result = DBFetch('SELECT url FROM images WHERE id = ? LIMIT 1', array($_POST['id']));
				DBExecute('DELETE FROM images WHERE id = ?', array($_POST['id']));
				echo json_encode( array('answer' => (unlink($result[0]) ? 'OK' : 'NO') ));
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
					DBExecute('INSERT INTO images (url) VALUES (?)', array($file));
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