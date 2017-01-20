<?php 
// Проверка - авторизован-ли администратор в системе.
if ( AdminAction::isSetAdmin() ) {
	// Если авторизован - выводим адми-панель
?>
	
<!-- START Admin-panel -->




<!-- END Admin-panel -->

	
	<?php
	
} else {
	// Если не авторизован - форму авторизации
	?>
	

<!-- START Login block -->
<div class="row row-table page-wrapper">
	<div class="col-lg-3 col-md-6 col-sm-8 col-xs-12 align-middle">
		 <!-- START panel-->
		 <div data-toggle="play-animation" data-play="fadeIn" data-offset="0" class="panel panel-dark panel-flat">
				<div class="panel-heading text-center mb-lg">
					 <p class="text-center mt-lg">
							<strong>Авторизируйтесь для получения доступа.</strong>
					 </p>
				</div>
				<div class="panel-body">
					 <form role="form" action="<?=ROOT?><?=Config::$adminLogin?>" method="post">
							<div class="form-group has-feedback">
								 <label for="signupInputEmail1" class="text-muted">Email</label>
								 <input id="signupInputEmail1" type="email" placeholder="Введите email" class="form-control" name="email">
								 <span class="fa fa-envelope form-control-feedback text-muted"></span>
							</div>
							<div class="form-group has-feedback">
								 <label for="signupInputPassword1" class="text-muted">Пароль</label>
								 <input id="signupInputPassword1" type="password" placeholder="Пароль" class="form-control" name="pass">
								 <span class="fa fa-lock form-control-feedback text-muted"></span>
							</div>
							<button type="submit" class="btn btn-block btn-success">Войти</button>
					 </form>
				</div>
		 </div>
		 <!-- END panel-->
	</div>
</div>
<!-- END Login block -->
	
	<?php
}
?>

<script>
$('form').submit(function(){
	console.log('prihli v funkciu');
	var pass      = $("input[name='pass']").val();
	pass = hex_md5(pass);
	$("input[name='pass']").val(pass);
});


</script>