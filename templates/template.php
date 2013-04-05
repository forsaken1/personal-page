<?php

defined('SITE') or die;

?>

<html>
  <head>
	<meta charset = 'UTF-8'>
    <title><?=$title?></title>
	<link href = '/css/style.css' rel = 'stylesheet'>
	<link href = '/css/menu.css'  rel = 'stylesheet'>
	<script src = '/js/jquery.js'></script>
	<script src = '/js/script.js'></script>
  </head>
  
  <body>
	<div align = center>
	  <div id = 'header'>
	    <?php require_once 'modules/menu.php'; ?>
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
	  
	  <div><h5>Дизайн и разработка Крылов А. (с) 2013</h5></div>
	</div>
  </body>
</html>