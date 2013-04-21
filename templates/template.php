<?php

defined('SITE') or die;

?>

<html>
  <head>
	<meta charset = 'UTF-8'>
    <title><?php echo $title; ?></title>
	<link href = '/css/style.css' rel = 'stylesheet'>
	<link href = '/css/menu.css'  rel = 'stylesheet'>
	<script src = '/js/jquery.js'></script>
	<script src = '/js/script.js'></script>
  </head>
  
  <body>
	<div align = center>
	  <div id = 'header'>
		<div id = 'menuContainer' align = left>
		  <?php require_once 'modules/menu.php'; ?>
		</div>
	  </div>
	  
	  <div id = 'main'>
		<div id = 'inMain'>
		  <div class = 'messageBlock' id = 'messageNotice'>
		    <h3 id = 'messageN'></h3>
		  </div>
		  <div class = 'messageBlock' id = 'messageWarning'>
		    <h3 id = 'messageW'></h3>
		  </div>
		  <?php content(); ?>
		</div>
	  </div>
	  
	  <div id = 'longBar' style = 'width: 800px;'></div>
	  <div><h5>Дизайн и разработка Крылов А. alexey2142@mail.ru (с) 2013</h5></div>
	</div>
  </body>
  
  <script>
	$(document).ready(function()
	{
		var urlStr = location.toString(), ul_pointer = document.getElementById('menu').children[0];
		for(var i = 0; i < ul_pointer.children.length; i++)
		{
			if(urlStr == ul_pointer.children[i].children[0].href.toString())
			{
				ul_pointer.children[i].children[0].className += ' active';
			}
		}
	});
  </script>
</html>