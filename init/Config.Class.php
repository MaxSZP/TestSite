<?php

class Config {
	
	static public $db_param = "cfg/db_param.ini";  // конфигурационный файл с параметрами доступа к БД
	
	static public $defController = "Page";  // контроллер по умолчанию - вывод страницы
	static public $defAction = "view";            // метод по умолчанию - вывод страницы
	static public $defParamName = "name";         // параметр по умолчанию - имя страницы
	static public $defPage = "index";                 // значение параметра по умолчанию - головная страница
	static public $page404 = "page404";               // страница 404
	static public $IControllerName = "IController";   // интерфейс контроллеров
	static public $namePage = "name";							//параметр, определяющий имя выводимой страницы
  
	static public $defLang = "ru";														// Язык сайта по умолчанию
	static public $langList = array("ru" => 1, "en" => 1,);		// Перечень поддерживаемых сайтом языков
	
	static public $setDecim = array("ru" => '.', "en" => ',',);		// Разделитель для вывода десятичных чисел
	static public $setGroop = array("ru" => '', "en" => ' ',);		// Разделитель групп для вывода десятичных чисел
	
	static public $dir_tpl = '/../tpl/';						// путь к файлам шаблонов
	static public $end_tpl = '.tpl.php';						// хвост файла шаблона
	
	
	static public $blockMain = 'main.block';						// основной текстовый блок
	static public $blockMenu = 'head.nav';							// блок основного меню
	static public $blockGallery = 'gallery';					// блок фотогаллереи
	
	// Настройки Админ - панели
	static public $adminLogin = 'usvers';								// Страница доступа в админ-панель
	static public $adminPath = 'adimindat';								// Каталог с файлами админ-панели. Не должен совпадать с $adminLogin
	static public $loginTpl = 'adm.usver';								// Шаблон авторизации администратора
	static public $adminLogout = 'logout';								// Ссылка для выхода из админ-панели
	
	
	static public $sliderImg = 'img/slider/';					// путь к изображениям основного слайдера
  static public $widthSlider = 1920;
  static public $heightSlider = 536;
  static public $widthTitle = 1920;
  static public $heightTitle = 93;
  
	static public $pathImg = 'img/';									// путь к файлам изображений
	static public $galleryImg = 'img/gallery/';				// путь к изображениям галереи
	static public $whiteList = array(".jpg", ".png");	// Допустимые расширения графических файлов
	static public $imageResize = "/../lib/ImageResize/img_resize.php" ;  //Путь к библиотеке ресайза изображений
	static public $widthBig = 1024;						// Ширина изображений в галерее
	static public $heightBig = 768;						// Высота изображений в галерее
	static public $widthSmall = 370;					// Ширина миниатюр в галерее
	static public $heightSmall = 278;					// Высота миниатюр в галерее
	
	static public $newsImg = 'img/news/';			// путь к изображениям новостей
	static public $widthNews = 512;						// Ширина изображений в Новостях
	static public $heightNews = 384;					// Высота изображений в Новостях
	static public $titleNews = "TestSite - ";			// Префикс для title, description, keyword в новостях
	static public $newsImgDef = 'news-default.jpg';	// Файл превью новости по умолчанию
	
	static public $articlesImg = 'img/articles/';			// путь к изображениям статей
	static public $widthArticles = 512;						// Ширина изображений в Статьях
	static public $heightArticles = 384;					// Высота изображений в статьях
	static public $titleArticles = "TestSite - ";			// Префикс для title, description, keyword в Статьях
	static public $articlesImgDef = 'articles-default.jpg';	// Файл превью статьи по умолчанию
	
	static public $defJpgBig = 'def-big.jpg';								// изображение в галерее по умолчанию
	static public $defJpgSmall = 'def-small.jpg';						// иконка изображения в галерее по умолчанию
	static public $defJpgTitle = 'Фотографии отсутствуют';	// титл изображения в галерее по умолчанию
	
	static public $editGalleryPic = "adm.editgalpic";						// Шаблон админпанели - работа с изображениями галереи
	
	static public $priceImg = 'img/catalog/';			// путь к изображениям каталога продукции
	static public $priceWidth = 512;							// Ширина изображений в каталоге продукции
	static public $priceHeight = 384;							// Высота изображений в каталоге продукции
	
	static public $defPriceBig = 'def-big.jpg';								// изображение в каталоге по умолчанию
	static public $defPriceSmall = 'def-small.jpg';						// иконка изображения в каталоге по умолчанию
	static public $defPriceTitle = 'Фотографии отсутствуют';	// титл изображения в каталоге по умолчанию
	
	static public $editPrice = "adm.editcatalog";						// Шаблон админпанели - работа с каталогом товаров
	static public $editPriceItem = "adm.editcatalog.item";				// Шаблон админпанели - пустой - новый каталог товара
	static public $editPricePic = "adm.editpricepic";						// Шаблон админпанели - работа с изображениями товара
  
	static public $autorizErrorMes = 'Авторизация пользователя!!!!';	// Сообщение при попытке доступа не авторизованного пользователя
	
	// Возвращает IP-адрес клиента
	public static function GetRealIp() {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip=$_SERVER['HTTP_CLIENT_IP'];
		}  elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}  else {
			$ip=$_SERVER['REMOTE_ADDR'];
		}
	return $ip;
	}
	
	
	// Редирект
	 public static function location($url, $time = 0){
	 	if($time == 0)
		  @header("Location:" . $url);
		else 
		  @header("Refresh:". $time . ";url=". $url);	
	 }
	
}
