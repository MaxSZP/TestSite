jQuery(document).ready( function($){
//
// Страница работы с галереей изображений - начало
//
	//при загрузке отключаем кнопки "Сохранить" и "Удалить" - галерея
	$('.adm-gallery-item button.add').prop('disabled', true);
	$('.adm-gallery-item button.del').prop('disabled', true);
	
	// при изменениях в полях - активируем кнопку "Сохранить" - галерея
	$('.adm-gallery-item input, .adm-gallery-item textarea').bind('textchange', function (event, previousText) {
		activButtonAdd(this);
	});
	
	// при изменениях в чекбоксе - активируем кнопку "Удалить" - галерея
	$(".adm-gallery-item input[type='checkbox']").change(function(event){
		activButtonDel(this);
	});
	
	
	//Передача данных на сервер через AJAX - кнопка "Сохранить" - информация элемента галереи
	$('div.adm-gallery-item button.add').click(function() {
		//Отключаем кнопку
		$(this).prop('disabled', true);
		thisButton = this;
		//Определяем Class кнопки и ID записи.
		getClassId(this);
		//collect input field values
		var g_tag      = $("input[name='tag']." + activClass).val();
		var g_name       = $("input[name='name']." + activClass).val();
		var g_title       = $("input[name='title']." + activClass).val();
		var g_descript   = $("textarea[name='descript']." + activClass).val();
		
		var php_skript      = "gallery/change";
		var ses_id = getCookie("SiteMSFID");
		
		//simple validation at client's end
		//we simply change border color to red if empty field using .css()
		var proceed = true;
		// При необходимости - проверяем данные
		
		//everything looks good! proceed...
		if(proceed) {
			//data to be sent to server
			post_data = {'id':activId, 'tag':g_tag, 'name':g_name, 'title':g_title, 'descript':g_descript, 'sesID':ses_id};
			
			//Ajax post data to server
			$.post(php_skript, post_data, function(data){  
				if (data == 1){
					$("div." + activClass + " div.formanswer").html("Сохранено");
				} else {
					$(thisButton).prop('disabled', false);
					$("div." + activClass + " div.formanswer").html("Ошибка!");
				}
			}).fail(function(err) {
					//load any error data
					console.log("Error at AJAX")
				});
		}
	});
	
	
	//Получение перечня изображений фотогалереи через AJAX - кнопка "Работа с изображениями"
	$('div.adm-gallery-item button.pic').click(function() {
		thisButton = this;
		//Определяем Class кнопки и ID записи.
		getClassId(this);
		idGalleryItem = activId;
		//collect input field values
		var g_tag      = $("input[name='tag']." + activClass).val();
		var g_name       = $("input[name='name']." + activClass).val();
		var g_title       = $("input[name='title']." + activClass).val();
		var g_descript   = $("textarea[name='descript']." + activClass).val();
		
		var php_skript      = "gallery/getpicture";
		var ses_id = getCookie("SiteMSFID");
		
		//simple validation at client's end
		//we simply change border color to red if empty field using .css()
		var proceed = true;
		// При необходимости - проверяем данные
		
		//everything looks good! proceed...
		if(proceed) {
			//data to be sent to server
			post_data = {'id':activId, 'sesID':ses_id};
			
			//Ajax post data to server
			$.post(php_skript, post_data, function(data){
				// выводим полученную галерею
				$('div.gallery-image').html(data);
				//устанавливаем события на кнопки и чекбокс
				
				//при загрузке отключаем кнопки "Сохранить" и "Удалить"
				$('div.pic-item button.save-gallery-item').prop('disabled', true);
				$('div.pic-item button.del-gallery-item').prop('disabled', true);
				
				// при изменениях в полях - активируем кнопку "Сохранить"
				$('div.pic-item input').bind('textchange', function (event, previousText) {
					// Определяем class 
					var classList = this.className.split(/\s+/);
					for (var i = 0; i < classList.length; i++) {
						if (classList[i].indexOf(classID) + 1) {
						$( 'div.pic-item button.save-gallery-item.' + classList[i] ).prop('disabled', false);
						}
					}
				});
				
				// при изменениях в чекбоксе - активируем кнопку "Удалить"
				$("div.pic-item input[type='checkbox']").change(function(event){
					// Определяем class 
					var classList = this.className.split(/\s+/);
					for (var i = 0; i < classList.length; i++) {
						if (classList[i].indexOf(classID) + 1) {
							if($("div.pic-item input:checkbox." + classList[i]).prop("checked")) {
								$( 'div.pic-item button.del-gallery-item.' + classList[i] ).prop('disabled', false);
							}else {
								$( 'div.pic-item button.del-gallery-item.' + classList[i] ).prop('disabled', true);
							}
						}
					}
				});
				
				//Действия при клике на "Добавить"
				$('div.gallery-image button.add-gallery-item').click(addPictureIten);
				
				//Действия при клике на "Сохранить"
				$('div.pic-item button.save-gallery-item').click(changePictureTitle);
				
				//Действия при клике на "Удалить"
				$('div.pic-item button.del-gallery-item').click(delPictureItem);
				
			}).fail(function(err) {
					//load any error data
					console.log("Error at AJAX")
				});
		}
	});
	
	//Функция добавления изображения в галлерею
	function addPictureIten() {
		//отправка файла на сервер
		var php_skript = "gallery/addpicgal/id/" + idGalleryItem;
		$$f({
			formid: 'add-pic-gallery',//id формы
			url: php_skript,//адрес на серверный скрипт который будет принимать файл
			onstart:function () {//действие при начале загрузки файла
				$$('result','начинаю отправку файла');//в элемент с id="result" выводим информацию
			},
			onsend:function () {//действие по окончании загрузки файла
				//при загрузке отключаем кнопки "Сохранить" и "Удалить"
				$('div.pic-item button.save-gallery-item').prop('disabled', true);
				$('div.pic-item button.del-gallery-item').prop('disabled', true);
				
				// при изменениях в полях - активируем кнопку "Сохранить"
				$('div.pic-item input').bind('textchange', function (event, previousText) {
					// Определяем class 
					var classList = this.className.split(/\s+/);
					for (var i = 0; i < classList.length; i++) {
						if (classList[i].indexOf(classID) + 1) {
						$( 'div.pic-item button.save-gallery-item.' + classList[i] ).prop('disabled', false);
						}
					}
				});
				
				// при изменениях в чекбоксе - активируем кнопку "Удалить"
				$("div.pic-item input[type='checkbox']").change(function(event){
					// Определяем class 
					var classList = this.className.split(/\s+/);
					for (var i = 0; i < classList.length; i++) {
						if (classList[i].indexOf(classID) + 1) {
							if($("div.pic-item input:checkbox." + classList[i]).prop("checked")) {
								$( 'div.pic-item button.del-gallery-item.' + classList[i] ).prop('disabled', false);
							}else {
								$( 'div.pic-item button.del-gallery-item.' + classList[i] ).prop('disabled', true);
							}
						}
					}
				});
				//Действия при клике на "Добавить"
				$('div.gallery-image button.add-gallery-item').click(addPictureIten);
				//Действия при клике на "Сохранить"
				$('div.pic-item button.save-gallery-item').click(changePictureTitle);
				//Действия при клике на "Удалить"
				$('div.pic-item button.del-gallery-item').click(delPictureItem);
			}
		});
	};
	
	//Функция сохранения заголовка изображения в галерее при его изменении
	function changePictureTitle() {
		//Отключаем кнопку
		$(this).prop('disabled', true);
		thisButton = this;
		//Определяем Class кнопки и ID записи.
		getClassId(this);
		//collect input field values
		var gp_title = $("input[name='jpgtitle']." + activClass).val();
		var php_skript      = "gallery/changetitle";
		var ses_id = getCookie("SiteMSFID");
		
		//При необходимости - проверяем данные
		var proceed = true;
		//Если данные корректны
		if(proceed) {
			//Данные для отправки на сервер
			post_data = {'id':activId, 'jpgtitle':gp_title, 'sesID':ses_id};
			//Ajax post data to server
			$.post(php_skript, post_data, function(data){  
				if (data == 1){
					$("div." + activClass + " div.formanswer").html("Сохранено");
				} else {
					$(thisButton).prop('disabled', false);
					$("div." + activClass + " div.formanswer").html("Ошибка!");
				}
			}).fail(function(err) {
					//load any error data
					console.log("Error at AJAX")
				});
		}
	};
	
	//Функция удаления изображения из галереи
	function delPictureItem() {
		//Отключаем кнопку
		$(this).prop('disabled', true);
		thisButton = this;
		//Определяем Class кнопки и ID записи.
		getClassId(this);
		//collect input field values
		var gp_title   = $("input[name='jpgtitle']." + activClass).val();
		var php_skript = "gallery/delpicitem/id/" + activId;
		var ses_id = getCookie("SiteMSFID");
		//При необходимости - проверяем данные
		var proceed = true;
		//Если данные корректны
		if(proceed) {
			//Данные для отправки на сервер
			post_data = {'id':activId, 'sesID':ses_id};
			//Ajax post data to server
			$.post(php_skript, post_data, function(data){  
				if (data == 1){
					//Удаляем удаленный блок на странице
					$("div.pic-item." + activClass).remove();
					$("div." + activClass + " div.formanswer").html("Сохранено");
				} else {
					$(thisButton).prop('disabled', false);
					$("div." + activClass + " div.formanswer").html("Ошибка!");
				}
			}).fail(function(err) {
					//load any error data
					console.log("Error at AJAX")
				});
		}
	};
	
//
// Страница работы с галереей изображений - окончание
//
});
