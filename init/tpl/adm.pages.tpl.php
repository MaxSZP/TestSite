<?php 
// Проверка - авторизован-ли администратор в системе.
if ( AdminAction::isSetAdmin() ) {
	// Если авторизован - выводим адми-панель
?>
	
<div class="row adminpages">
	<div class="col-md-2 text-left">
		<div class="row">
		
<?php
	$pages = $db->getEditPages();
	
	foreach($pages as $key){
?>
			<div class="col-md-12 adm-page-item id-<?=$key['id']?> text-center">
				<button namePage="<?=$key['name']?>" class="btn btn-block btn-info btn-xs editpage id-<?=$key['id']?>"><?=$key['page_name']?></button>
				<br/>
				<button namePage="<?=$key['name']?>" class="add btn btn-success btn-xs sendpage id-<?=$key['id']?>">Сохранить</button>
				<div class="formanswer id-<?=$key['id']?>"></div>
			</div>
<?php
	} 
	unset($key);
?>
		</div>
	</div>
	<div class="ckeditor col-md-10 editpage">
		
	</div>
</div>


<script>
jQuery(document).ready( function($){
	//при загрузке отключаем кнопки "Сохранить"
	$('.adm-page-item button.add').prop('disabled', true);
	
	//Передача данных на сервер через AJAX - кнопка "Сохранить" - редактируемый текстовый блок
	$('div.adm-page-item button.add').click(function() {
		//Отключаем кнопку
		$(this).prop('disabled', true);
		thisButton = this;
		$("div.formanswer." + activClass).html('');
		
		//collect input field values
		var page      = $(thisButton).attr('namePage');
		var mainblock      = CKEDITOR.instances['mainBlock'].getData();
		
		var php_skript      = "<?=ROOT?>page/changemainblock";
		var ses_id = getCookie("SiteMSFID");
		
		//simple validation at client's end
		//we simply change border color to red if empty field using .css()
		var proceed = true;
		// При необходимости - проверяем данные
		
		//everything looks good! proceed...
		if(proceed) {
			//data to be sent to server
			post_data = {'id':activId, 'page':page, 'mainblock':mainblock, 'sesID':ses_id};
			
			//Ajax post data to server
			$.post(php_skript, post_data, function(data){
				// Включаем кнопку "Сохранить"
				$(thisButton).prop('disabled', false);
				var dataJson = $.parseJSON(data);
				if (dataJson.err == 0){
					$("div.formanswer." + activClass).css({"color":"green"});
				} else {
					$("div.formanswer." + activClass).css({"color":"red"});
				}
				$("div.formanswer." + activClass).html(dataJson.mess);
			}).fail(function(err) {
					//load any error data
					console.log("Error at AJAX")
				});
		}
	});
	
	
	//Получение редактируемого блока через AJAX - кнопка " Имя Страницы "
	$('div.adm-page-item button.editpage').click(function() {
		thisButton = this;
		//Определяем Class кнопки и ID записи.
		getClassId(this);
		//collect input field values
		var page      = $(thisButton).attr('namePage');
		
		var php_skript      = "<?=ROOT?>page/getmainblock";
		var ses_id = getCookie("SiteMSFID");
		
		//simple validation at client's end
		var proceed = true;
		// При необходимости - проверяем данные
		
		//everything looks good! proceed...
		if(proceed) {
			//data to be sent to server
			post_data = {'id':activId, 'page':page, 'sesID':ses_id};
			
			//Ajax post data to server
			$.post(php_skript, post_data, function(data){
				// выводим полученную страницу
				$('div.editpage').html('<textarea name="mainBlock" id="mainBlock">' + data + '</textarea>');
				// отключаем все кнопки "Сохранить"
				$('div.adm-page-item button.add').prop('disabled', true);
				// включаем текущую кнопку "Сохранить"
				$('div.adm-page-item button.add.' + activClass).prop('disabled', false);
				//инициализируем редактор
				CKEDITOR.replace( 'mainBlock',{}); 
				
			}).fail(function(err) {
					//load any error data
					console.log("Error at AJAX")
				});
		}
	});
});
</script>

<?php
}
?>