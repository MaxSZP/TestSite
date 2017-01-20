<?php 
// Проверка - авторизован-ли администратор в системе.
if ( AdminAction::isSetAdmin() ) {
	// Если авторизован - выводим адми-панель
	?>
	
	<!-- START Top Navbar-->
	<nav role="navigation" class="navbar navbar-default navbar-top navbar-fixed-top">
		 <div class="nav-wrapper">
				<!-- START Right Navbar-->
				<ul class="nav navbar-nav navbar-right">
                    <li><a href="<?=ROOT?>">На главную страницу сайта</a></li>
					<li><a href="<?=ROOT?>adminnews">Новости</a></li>
					<li><a href="<?=ROOT?>adminpages">Страницы</a></li>
					<li><a href="<?=ROOT?><?=Config::$adminLogout?>">Выход</a></li>
				</ul>
				<!-- END Right Navbar-->
		 </div>
		 <!-- END Nav wrapper-->
	</nav>
	<!-- END Top Navbar-->
	
	<div class="row">
		<div class="col-md-4">
			<?=$this->namePage?>
		</div>
	</div>
	
	<?php
}
?>
