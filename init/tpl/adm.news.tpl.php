<?php 
// Проверка - авторизован-ли администратор в системе.
if ( AdminAction::isSetAdmin() ) {
	// Если авторизован - выводим адми-панель
?>
	<div class="row">
		<div class="col-md-12">
			<form action="<?=ROOT?>news/add">
				<button class = "btn btn-xs btn-info">Добавить новость</button>
			</form>
		</div>
	</div>
	
	<div class="row adminnews"> 
		
<?php
		$num = 1;
		if(isset($params['num']) && (int)$params['num'] > 0){
			$num = (int)$params['num'];
		}
		
		$news = $db->getNewsAdm($num);
		
		$i = 1;
		foreach($news as $key){
			$checked = ($key['vis']) ? " checked " : " " ;
?>
			
			<div class="row adm-news-item id-<?=$key['id']?>">
			<div class="col-md-2 text-right">
				 <p>Дата публикации:</p>
				</div>
				<div class="col-md-1">
				 <input type="text" name="data" size="10" class="datepicker id-<?=$key['id']?>" value = "<?=date("d.m.Y", $key['data'])?>">
				</div>
				<div class="col-md-2 text-right">
					<p>Показать новость на сайте?</p>
				</div>
				<div class="col-md-1 text-left">
					<input type="checkbox" name="vis" class="vis id-<?=$key['id']?>" <?=$checked?>>
				</div>
				<div class="col-md-1">
					<button class="add btn btn-success btn-xs sendfaq id-<?=$key['id']?>">Сохранить</button>
				</div>
				<div class="formanswer col-md-1"></div>

				<div class="col-md-1 text-right">
					<p></p>
				</div>
				<div class="col-md-3 text-left">
					
				</div>

				<div class="col-md-12 text-left">
					<p>Заголовок :</p>
					<input type="text" name="title" class="id-<?=$key['id']?>" value = "<?=$key['title']?>">
				</div>
				
				<div class="news-picture-control col-md-2">
					<form id="add-pic-news-<?=$key['id']?>" method="post" enctype="multipart/form-data" onSubmit="">
					<input type="hidden" name="MAX_FILE_SIZE" value="100000000"> 
					<input class="file_form" type="file" name="upload_file" />
					</form><br />
					<div id="result-<?=$key['id']?>">Статус загрузки файла</div><br />
					<button class = "btn btn-xs btn-info add-news-picture id-<?=$key['id']?>">Добавить изображение</button>
					<hr>
					<input type="checkbox" name="del" class="del id-<?=$key['id']?>" >
					<button class="btn btn-xs btn-warning del-news-pic id-<?=$key['id']?>" >Удалить изображение</button>
				</div>
				<div class="news-picture col-md-2">
<?php if($key['img_news'] != ""){ ?>
			<img src="<?=ROOT.Config::$newsImg.$key['img_news']?>" alt="foto">
<?php }?>
					
				</div>
				<div class="col-md-8">
					<p>Текст новости:</p>
					<textarea rows="6" name="text<?=$key['id']?>" class="ckeditor id-<?=$key['id']?>" ><?=$key['text']?></textarea>
				</div>
			</div>

<?php
			$i++;
			
		} 
		unset($key);
?>
						
<?php
	//Блок пагинатора
	//Общее количество новостей для вывода
	$count = $db->getNewsCount();
	
	if($count > $_SESSION['maxNews']){
?>
	<div class="clearfix"></div>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pagiation-page text-center">
		<ul class="pagination margin-none">
<?php
			for($i = 1; ($i-1)*$_SESSION['maxNews'] < $count; $i++){
			$class = ($i == $num) ? "disabled" : "" ;
			$urlPage = ROOT . "page/view/name/" . $params['name'] . "/num/" . $i . "/" ;
?>
			<li class="<?=$class?>"><a href="<?=$urlPage?>"><?=$i?></a></li>
<?php
		}
?>
		</ul>
	</div>
<?php
	}
?>
						
						
	</div>

<!-- Individual JS - Scripts -->
<script src="<?=ROOT_ADM?>js/jquery-ui.min.js" type="text/javascript" ></script>
<script type="text/javascript" src="<?=ROOT_ADM?>js/jquery.ui.datepicker-ru.js"></script>
<script>
jQuery(document).ready( function($){
//
// Страница работы со страницей Новости - начало
//
	//Подключение календаря для класса .datepicker
	$.datepicker.setDefaults($.datepicker.regional['ru']);
	$('.datepicker').datepicker({onSelect: function(date, datepicker){
		activButtonAdd(this);
	}
	});
	
	//при загрузке отключаем кнопки "Сохранить" и "Удалить" - Новости
//	$('div.adm-news-item button.add').prop('disabled', true);
	$('div.adm-news-item button.del').prop('disabled', true);
	$('div.adm-news-item button.del-news-pic').prop('disabled', true);
	
	
	// при изменениях в полях - активируем кнопку "Сохранить" - Новости
//	$('div.adm-news-item input, div.adm-news-item textarea').bind('textchange', function (event, previousText) {
//		activButtonAdd(this);
//	});
	
	// при изменениях в чекбоксе vis - активируем кнопку "Сохранить" - Новости
//	$("div.adm-news-item input[type='checkbox'].vis").change(function(event){
//		activButtonAdd(this);
//	});
	
	// при изменениях в чекбоксе del-news-pic - активируем кнопку "Удалить изображение" - Новости
	$("div.adm-news-item input:checkbox.del").change(function(event){
		// Определяем class 
		var classList = this.className.split(/\s+/);
		for (var i = 0; i < classList.length; i++) {
			if (classList[i].indexOf(classID) + 1) {
				if($("input:checkbox.del." + classList[i]).prop("checked")) {
					$( 'button.' + classList[i] + '.del-news-pic' ).prop('disabled', false);
				}else {
					$( 'button.' + classList[i] + '.del-news-pic' ).prop('disabled', true);
				}
			}
		}
	});
	
	
	//Передача данных на сервер через AJAX - кнопка "Сохранить" - Новости
	$('div.adm-news-item button.add').click(function() {
		//Отключаем кнопку
		$(this).prop('disabled', true);
		thisButton = this;
		//Определяем Class кнопки и ID записи.
		getClassId(this);
		//Собираем данные полей с информацией
		var n_vis = 0;
		if ($("input[type='checkbox']." + activClass).prop('checked')){
			n_vis = 1;
		}
		var n_data      = $("input[name='data']." + activClass).val();
		var n_title     = $("input[name='title']." + activClass).val();
		var n_text      = CKEDITOR.instances['text'+activId].getData();
		
		var php_skript      = "<?=ROOT?>news/change";
		var ses_id = getCookie("SiteMSFID");
		
		var proceed = true;
		// При необходимости - проверяем данные
		
		//Если данные корректны
		if(proceed) {
			//data to be sent to server
			post_data = {'id':activId, 'vis':n_vis, 'data':n_data, 'title':n_title, 'text':n_text, 'sesID':ses_id};
			
			//Ajax post data to server
			$.post(php_skript, post_data, function(data){
				$(thisButton).prop('disabled', false);
				if (data == 1){
					$("div." + activClass + " div.formanswer").html("Сохранено");
				} else {
					$("div." + activClass + " div.formanswer").html("Ошибка!");
				}
			}).fail(function(err) {
					//load any error data
					console.log("Error at AJAX")
				});
		}
	});
	
	//Добавление файла изображения "Добавить изображение" - Новости
	$('div.news-picture-control button.add-news-picture').click(function() {
		//определяем id новости
		getClassId(this);
		//отправка файла на сервер
		var php_skript = "<?=ROOT?>news/addpicnews/id/" + activId;
		var formId = 'add-pic-news-' + activId;
		$$f({
			formid: formId,//id формы
			url: php_skript,//адрес на серверный скрипт который будет принимать файл
			onstart:function () {//действие при начале загрузки файла
				var resultId = 'result-' + activId;
				$$(resultId,'начинаю отправку файла');//в элемент с id="result-id" выводим информацию
			},
			onsend:function () {
				//Действия после отправки файла
				if(addFileError == 0){
					if (filePicNews != ""){
						$("div.adm-news-item."+activClass+" div.news-picture").html('<img src="'+filePicNews+'" alt="foto">');
					}
				}else{
					var resultId = 'result-' + activId;
					$$(resultId,'Ошибка при отправке');
				}
			}
		});
	});
	
	//Удаление изображения через AJAX - кнопка "Удалить изображение" - Новости
	$('div.adm-news-item button.del-news-pic').click(function() {
		//Отключаем кнопку
		$(this).prop('disabled', true);
		thisButton = this;
		//Определяем Class кнопки и ID записи.
		getClassId(this);
		//Собираем данные полей с информацией
		
		var php_skript      = "<?=ROOT?>news/delpicture";
		var ses_id = getCookie("SiteMSFID");
		
		var proceed = true;
		// При необходимости - проверяем данные
		
		//Если данные корректны
		if(proceed) {
			//data to be sent to server
			post_data = {'id':activId, 'sesID':ses_id};
			
			//Ajax post data to server
			$.post(php_skript, post_data, function(data){  
				
				if (data == 1){
					$("div.adm-news-item."+activClass+" div.news-picture").html('');
					$("div.adm-news-item input:checkbox.del").removeAttr("checked");
				} else {
					$(thisButton).prop('disabled', false);
				}
			}).fail(function(err) {
					//load any error data
					console.log("Error at AJAX")
				});
		}
	});
//
// Страница работы со страницей Новости - окончание
//
});
</script>

<?php
}
?>
