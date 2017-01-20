<!doctype html>
<!--[if IE 7 ]> <html lang="en" class="ie7"> <![endif]-->
<!--[if IE 8 ]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9 ]> <html lang="en" class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en">
<!--<![endif]-->
<head>
<meta charset="utf-8">

<!-- Данные для админпанели сайта -->
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
   <meta name="description" content="">
   <meta name="keywords" content="">
   <meta name="author" content="">
   <title>AdminPanel</title>
   <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
   <!--[if lt IE 9]><script src="<?=ROOT_ADM?>js/html5shiv.js"></script><script src="<?=ROOT_ADM?>js/respond.min.js"></script><![endif]-->
   <!-- Bootstrap CSS-->
   <link rel="stylesheet" href="<?=ROOT_ADM?>css/bootstrap.css">
   <!-- Vendor CSS-->
   <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
	<link rel="stylesheet" href="<?=ROOT_ADM?>css/animate+animo.css">
	<link rel="stylesheet" href="<?=ROOT_ADM?>css/jquery-ui.min.css">
	
	<link rel="stylesheet" href="<?=ROOT_ADM?>css/style.css">
	 
   <!-- App CSS-->
   <link rel="stylesheet" href="<?=ROOT_ADM?>css/app.css">
   <link rel="stylesheet" href="<?=ROOT_ADM?>css/common.css">
   <!-- Modernizr JS Script-->
   <script src="<?=ROOT_ADM?>js/modernizr.js" type="application/javascript"></script>
   <!-- FastClick for mobiles-->
   <script src="<?=ROOT_ADM?>js/fastclick.js" type="application/javascript"></script>
	
		<!-- Main vendor Scripts-->
	<script src="<?=ROOT_ADM?>js/jquery.min.js"></script>
	<script src="<?=ROOT_ADM?>js/bootstrap.min.js"></script>
	<script src="<?=ROOT_ADM?>js/jquery.textchange.min.js"></script>
	<script src="<?=ROOT_ADM?>js/scriptjava.js" type="text/javascript" ></script>
	
	<script src="<?=ROOT_ADM?>js/md5-min.js"></script>
	<script src="<?=ROOT_ADM?>js/main.lib.js"></script>
	<!-- END Scripts-->
	
	

</head>

<body>

<?php
// Проверка - авторизован-ли администратор в системе.
if ( AdminAction::isSetAdmin() ) {
	// Если авторизован - выводим элементы админ-панель
	?>
	<div class="container-fluid">

<?php } else { ?>
	
		<div class="wrapper">
	
<?php } ?>