	//Переменные, используемые в работе скриптов
	classID = "id-";
	
	activClass = 0;
	activId = 0;
	thisButton = "";
	
	filePicNews = "";
	categoryName = "";
	idCategoryItem = 0;
	idGalleryItem = 0;
	numPage = 0;
	addFileDat = "";
	
// Блок сервисных функций для всех страниц - начало
	
	//Определяем наличие Class содержащего classID и извлекает ID записи.
	function getClassId(thisElement) {
		var classList = thisElement.className.split(/\s+/);
		for (var i = 0; i < classList.length; i++) {
			if (classList[i].indexOf(classID) + 1) {
				activClass = classList[i];
				activId = parseInt(activClass.replace(classID, ""), 10);
			}
		}
	}
	
	// Функция активации кнопки "Сохранить" - class - add
	function activButtonAdd(activBlock) {
		// Определяем class 
		var classList = activBlock.className.split(/\s+/);
		for (var i = 0; i < classList.length; i++) {
			if (classList[i].indexOf(classID) + 1) {
			$( 'button.' + classList[i] + '.add' ).prop('disabled', false);
				$("div." + classList[i] + " div.formanswer").html(" ");
			}
		}
	}
	
	// Функция активации кнопки "Удалить" - class - del
	function activButtonDel(activBlock) {
		// Определяем class 
		var classList = activBlock.className.split(/\s+/);
		for (var i = 0; i < classList.length; i++) {
			if (classList[i].indexOf(classID) + 1) {
				if($("input:checkbox." + classList[i]).prop("checked")) {
					$( 'button.' + classList[i] + '.del' ).prop('disabled', false);
				}else {
					$( 'button.' + classList[i] + '.del' ).prop('disabled', true);
				}
			}
		}
	}
	
	// получаем значение куки
	function getCookie(name) {
		var cookie = " " + document.cookie;
		var search = " " + name + "=";
		var setStr = null;
		var offset = 0;
		var end = 0;
		if (cookie.length > 0) {
			offset = cookie.indexOf(search);
			if (offset != -1) {
				offset += search.length;
				end = cookie.indexOf(";", offset)
				if (end == -1) {
					end = cookie.length;
				}
				setStr = unescape(cookie.substring(offset, end));
			}
		}
		return(setStr);
	}
	
	
// Блок сервисных функций - окончание
