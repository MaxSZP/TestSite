<?php
class DbMysqli extends mysqli {

	static $_instance;
	private $_lang;

	public static function getInstance() {
		if(!(self::$_instance instanceof self)) 
			self::$_instance = new self();
		return self::$_instance;
	}

	private function __construct() {
		if (is_file(Config::$db_param)){
			$db_array = parse_ini_file(Config::$db_param);
			
			parent::__construct($db_array['host'], $db_array['user'], $db_array['pass'], $db_array['db']);
			$this->set_charset("utf8");
			if (mysqli_connect_error()) {
				die('<h1>Ошибка в работе сайта 1</h1>/');
			}
		}else{
			die('<h1>Ошибка в работе сайта 2</h1>/');
		}
		$this->_lang = Lang::getLang();
		
		
		//date_default_timezone_set('Europe/Kiev');
		
	}
	// минимальная обработка строковой переменной для запроса
	public function prepareStringMin($value) {
		$value = trim($value);
		return($value);
	}

	// обработка строковой переменной для запроса c экранированием спецсимволов
	public function prepareStringMiddle($value) {
		$value = $this->real_escape_string($this->prepareStringMin($value));
		return($value);
	}

	// обработка строковой переменной для запроса c вырезкой тегов
	public function prepareStringFull($value) {
		$value = strip_tags($this->prepareStringMiddle($value));
		return($value);
	}

	// обработка целой положительной переменной для запроса полный вариант
	public function prepareIntFull($value) {
		$value = ABS((int)$value);
		return($value);
	}
	
	// Сервисная функция - по полученной строке SQL-запроса отдает массив результатов
		public function getSearchArray($query){
		if($query) {
		if( !($result = $this->query($query)) ) die('<h1>Ошибка в работе сайта 3</h1>');
		$i = 0;
		$rezArray = array();
		while ($res = $result->fetch_assoc()){
			$rezArray[$i] = $res;
			$i++;
		}
		$result->close();
		return $rezArray;
		}
		// Если пришел пустой запрос
		die('<h1>Ошибка в работе сайта 4</h1>');
	}
	
	
	
//
//   --------------------   Методы для работы со Страницами   --------------------
//
	//Получаем данные страницы по ее ID
	public function getPageDat($id){
		$id = $this->prepareIntFull($id);
		$query = "SELECT id, name, class, page_name, comment, title, descript, keyword, crumb, user_page, admin_page FROM msf_pages WHERE id = '$id' LIMIT 1";
		return $this->getSearchArray($query);
	}
	
	//Получаем данные страницы по ее NAME
	public function getPageName($name){
		$name = $this->prepareStringFull($name);
		$query = "SELECT id, name, class, page_name, comment, title, descript, keyword, crumb, user_page, admin_page FROM msf_pages WHERE name = '$name' LIMIT 1";
		return $this->getSearchArray($query);
	}
	
	//Получаем перечень блоков для страницы по id страницы
	public function getBlocksPage($id){
		$id = $this->prepareIntFull($id);
		$query = "SELECT id, activ_block, name_block, range_block, required_block, tpl_block FROM msf_page_block 
											WHERE id_page = '$id' AND activ_block = '1' ORDER BY range_block ASC";
		return $this->getSearchArray($query);
	}
	
	// вытягиваем параметры для текущего блока и выводим шаблон
	public function getBlockParams($block_id){
		//Здесь $block_id - это текст!
		$block_id = $this->prepareStringFull($block_id);
		$query = "SELECT id_block, param_name, param_value FROM msf_block_param WHERE id_block = '$block_id'";
		return $this->getSearchArray($query);
	}
	
	// Получаем параметры для всех блоков переданного языка или всех блоков при его отсутствии
	public function getBlockParamsAll($lang = "all"){
		//Здесь $lang - это текст!
		if ($lang === "all"){
			$query = "SELECT param_name, param_value FROM msf_block_param";
		}else {
			$lang = $this->prepareStringFull($lang);
			$query = "SELECT param_name, param_value FROM msf_block_param WHERE lang = '$lang'";
		}
		return $this->getSearchArray($query);
	}
	
	// Получаем массив меню
	public function getNavArray(){
		$query = "SELECT number, submenu, name, link FROM msf_menu ORDER BY number ASC";
		return $this->getSearchArray($query);
	}
	
	// Изменяем основной информационный блок
	public function setMainblock($page, $mainblock){
		$page = $this->prepareStringFull($page);
		$mainblock = $this->prepareStringMin($mainblock);
		$query = "UPDATE msf_main SET mainblock = '$mainblock' WHERE page = '$page' ";
		return $this->query($query);
	}
	
	// Получаем основной информационный блок
	public function getMainBlock($value){
		$pageName = $this->prepareStringFull($value);
		$query = "SELECT mainblock FROM msf_main WHERE page = '$pageName'";
		if( !($result = $this->query($query)) ) die('<h1>Ошибка в работе сайта 5</h1>');
		$res = $result->fetch_assoc();
		$result->close();
		return $res['mainblock'];
	}
	
	// Получаем все страницы, имеющие редактируемый основной блок.
	public function getEditPages(){
		$query = "SELECT msf_pages.id AS id, msf_pages.name AS name, msf_pages.page_name AS page_name FROM msf_main INNER JOIN msf_pages ON msf_main.page = msf_pages.name";
		return $this->getSearchArray($query);
	}
	
	
//
//   --------------------   Методы для работы с Новости   --------------------
//
	// Получаем последние новости
	public function getLastNews($count = 5){
		$data = time();
		$count = $this->prepareIntFull($count);
		$query = "SELECT id, data, title, img_news, text, counter, img_count FROM msf_news WHERE vis = '1' AND data < '$data' ORDER BY data DESC LIMIT $count";
		return $this->getSearchArray($query);
	}

	// Получаем новости для страницы новостей
	public function getNews($numPage = 0){
		$data = time();
		$numPage = ($numPage > 0) ? $numPage-1 : 0;
		$numPage = $numPage * $_SESSION['maxNews'];
		$maxNews = $_SESSION['maxNews'];
		$query = "SELECT id, data, title, img_news, text, counter, img_count FROM msf_news WHERE vis = '1' AND data < '$data' ORDER BY data DESC LIMIT $numPage, $maxNews";
		return $this->getSearchArray($query);
	}
	
	//Получаем количество новостей для страницы вопросов в базе
	public function getNewsCount($vis=0){
    $vis = $this->prepareIntFull($vis);
    if($vis){
      $query = "SELECT COUNT(*) FROM msf_news WHERE vis = '1'";
    }else{
      $query = "SELECT COUNT(*) FROM msf_news";
    }
		$result = $this->getSearchArray($query);
		$count = $result[0]['COUNT(*)'];
		return $count;
	}
	
	
	// Получаем новость по ID
	public function getNewsId($id){
		$id = $this->prepareIntFull($id);
		$query = "SELECT id, data, title, img_news, text, counter, img_count FROM msf_news WHERE vis = '1' AND id = '$id' ";
		return $this->getSearchArray($query);
	}

	// Получаем новость по ID, увеличивая счетчик
	public function getNewsIdCounter($id){
		$id = $this->prepareIntFull($id);
		$query = "SELECT id, data, title, img_news, text, counter, img_count FROM msf_news WHERE vis = '1' AND id = '$id' ";
		$_rezult = $this->getSearchArray($query);
		if($_rezult){
			$_counter = $_rezult[0]['counter'] + 1;
			$query = "UPDATE msf_news SET counter = '$_counter' WHERE id = '$id' ";
			$this->query($query);
		}
		return $_rezult;
	}

	// Получаем ID предыдущей новости по ID
	public function getPrevNewsId($id){
		$id = $this->prepareIntFull($id);
		$query = "SELECT id FROM msf_news WHERE vis = '1' AND id < '$id' ORDER BY `id` DESC LIMIT 1";
		$_rezult = $this->getSearchArray($query);
		$_id = ($_rezult) ? $_rezult[0]['id'] : 0 ;
		return $_id ;
	}

	// Получаем ID следующей новости по ID
	public function getNextNewsId($id){
		$id = $this->prepareIntFull($id);
		$query = "SELECT id FROM msf_news WHERE vis = '1' AND id > '$id' ORDER BY `id` ASC LIMIT 1";
		$_rezult = $this->getSearchArray($query);
		$_id = ($_rezult) ? $_rezult[0]['id'] : 0 ;
		return $_id ;
	}

	// Добавляем новость в базу
	public function addNews($data = 0) {
		if($data == 0) $data = mktime(0,0,0);
		$data = $this->prepareIntFull($data);
		$query = "INSERT INTO msf_news (data, vis) VALUES ('$data', '0')";
		if(!($result = $this->query($query))) die('<h1>Ошибка в работе сайта 6</h1>');
		return ;
	}
	
	// Получаем новости для Админ-панели.
	public function getNewsAdm($numPage = 0){
		$numPage = ($numPage > 0) ? $numPage-1 : 0;
		$numPage = $numPage * $_SESSION['maxNews'];
		$maxNews = $_SESSION['maxNews'];
		$query = "SELECT id, data, title, img_news, text, vis FROM msf_news ORDER BY data DESC LIMIT $numPage, $maxNews";
		return $this->getSearchArray($query);
	}
	
	// Изменяем новость в базе
	public function changeNews($id, $vis, $data, $title, $text){
		$vis = $this->prepareIntFull($vis);
		$id = $this->prepareIntFull($id);
		$data = $this->prepareIntFull($data);
		$title = $this->prepareStringFull($title);
		$text = $this->prepareStringMiddle($text);
		$query = "UPDATE msf_news SET vis = '$vis', data = '$data', title = '$title', text =  '$text' WHERE id = '$id' ";
		return $this->query($query);
	}
	
	//Добавляем информацию о изображении в новости
	public function addPicNews($id, $imgNews){
		$id = $this->prepareIntFull($id);
		$imgNews = $this->prepareStringFull($imgNews);
		$query = "UPDATE msf_news SET img_news = '$imgNews' WHERE id = '$id' ";
		return $this->query($query);
	}
	
//
//   --------------------   Методы для работы со Статьями   --------------------
//
	// Получаем последние статьи
	public function getLastArticles($count = 5, $lang = ""){
		if(empty($lang)) $lang = $this->_lang;
		$lang = $this->prepareStringFull($lang);
		$data = time();
		$count = $this->prepareIntFull($count);
		$query = "SELECT id, data, title, img, text, counter, img_count FROM msf_articles WHERE vis = '1' AND data < '$data' AND lang = '$lang' ORDER BY data DESC LIMIT $count";
		return $this->getSearchArray($query);
	}

	// Получаем статьи для страницы статей
	public function getArticles($numPage = 0, $lang = ""){
		if(empty($lang)) $lang = $this->_lang;
		$lang = $this->prepareStringFull($lang);
		$data = time();
		$numPage = ($numPage > 0) ? $numPage-1 : 0;
		$numPage = $numPage * $_SESSION['maxArticles'];
		$maxArticles = $_SESSION['maxArticles'];
		$query = "SELECT id, data, title, img, text, counter, img_count FROM msf_articles WHERE vis = '1' AND data < '$data' AND lang = '$lang' ORDER BY data DESC LIMIT $numPage, $maxArticles";
		return $this->getSearchArray($query);
	}

	// Получаем статью по ID, увеличивая счетчик
	public function getArticlesIdCounter($id){
		$id = $this->prepareIntFull($id);
		$query = "SELECT id, data, title, img, text, counter, img_count FROM msf_articles WHERE vis = '1' AND id = '$id' ";
		$_rezult = $this->getSearchArray($query);
		if($_rezult){
			$_counter = $_rezult[0]['counter'] + 1;
			$query = "UPDATE msf_articles SET counter = '$_counter' WHERE id = '$id' ";
			$this->query($query);
		}
		return $_rezult;
	}

	// Получаем ID предыдущей статьи по ID
	public function getPrevArticlesId($id){
		$id = $this->prepareIntFull($id);
		$query = "SELECT id FROM msf_articles WHERE vis = '1' AND id < '$id' ORDER BY `id` DESC LIMIT 1";
		$_rezult = $this->getSearchArray($query);
		$_id = ($_rezult) ? $_rezult[0]['id'] : 0 ;
		return $_id ;
	}

	// Получаем ID следующей статьи по ID
	public function getNextArticlesId($id){
		$id = $this->prepareIntFull($id);
		$query = "SELECT id FROM msf_articles WHERE vis = '1' AND id > '$id' ORDER BY `id` ASC LIMIT 1";
		$_rezult = $this->getSearchArray($query);
		$_id = ($_rezult) ? $_rezult[0]['id'] : 0 ;
		return $_id ;
	}
	
	//Получаем количество статей для страницы вопросов в базе
	public function getArticlesCount($lang = ""){
		if(empty($lang)) $lang = $this->_lang;
		$lang = $this->prepareStringFull($lang);
		$query = "SELECT COUNT(*) FROM msf_articles WHERE lang = '$lang' AND vis = '1'";
		$result = $this->getSearchArray($query);
		$count = $result[0]['COUNT(*)'];
		return $count;
	}
	
	// Получаем статью по ID
	public function getArticlesId($id, $lang = ""){
		if(empty($lang)) $lang = $this->_lang;
		$lang = $this->prepareStringFull($lang);
		$id = $this->prepareIntFull($id);
		$query = "SELECT id, data, title, img, text, counter, img_count FROM msf_articles WHERE vis = '1' AND id = '$id' AND lang = '$lang'";
		return $this->getSearchArray($query);
	}
	
	// Добавляем статью в базу
	public function addArticles($data = 0, $lang = "") {
		if(empty($lang)) $lang = $this->_lang;
		$lang = $this->prepareStringFull($lang);
		if($data == 0) $data = mktime(0,0,0);
		$data = $this->prepareIntFull($data);
		$query = "INSERT INTO msf_articles (data, vis, lang) VALUES ('$data', '0', '$lang')";
		if(!($result = $this->query($query))) die('<h1>Ошибка в работе сайта 7</h1>');
		return ;
	}
	
	// Получаем статьи для Админ-панели.
	public function getArticlesAdm($numPage = 0, $lang = ""){
		if(empty($lang)) $lang = $this->_lang;
		$lang = $this->prepareStringFull($lang);
		$numPage = ($numPage > 0) ? $numPage-1 : 0;
		$numPage = $numPage * $_SESSION['maxArticles'];
		$maxArticles = $_SESSION['maxArticles'];
		$query = "SELECT id, data, title, img, text, vis FROM msf_articles WHERE lang = '$lang' ORDER BY data DESC LIMIT $numPage, $maxArticles";
		return $this->getSearchArray($query);
	}
	
	// Изменяем статью в базе
	public function changeArticles($id, $vis, $data, $title, $text){
		$vis = $this->prepareIntFull($vis);
		$id = $this->prepareIntFull($id);
		$data = $this->prepareIntFull($data);
		$title = $this->prepareStringFull($title);
		$text = $this->prepareStringMin($text);
		$query = "UPDATE msf_articles SET vis = '$vis', data = '$data', title = '$title', text =  '$text' WHERE id = '$id' ";
		return $this->query($query);
	}
	
	//Добавляем информацию о изображении в статью
	public function addPicArticles($id, $imgArticles){
		$id = $this->prepareIntFull($id);
		$imgArticles = $this->prepareStringFull($imgArticles);
		$query = "UPDATE msf_articles SET img = '$imgArticles' WHERE id = '$id' ";
		return $this->query($query);
	}
	
//
//   --------------------   Методы для работы со Слайдером   --------------------
//
  // Получаем изображения для слайдера или заголовка
  public function getSliderImg($type, $limit = 0){
    $type = $this->prepareIntFull($type);
    $limit = $this->prepareIntFull($limit);
    if($limit == 0){
      $query = "SELECT id, link, img, text FROM msf_slider WHERE vis = '1' AND type = '$type' AND img != '' ORDER BY RAND()";
    }else {
      $query = "SELECT id, link, img, text FROM msf_slider WHERE vis = '1' AND type = '$type' AND img != '' ORDER BY RAND() LIMIT $limit";
    }
    return $this->getSearchArray($query);
  }
  
  // Добавляем новый слайдера в базу
  public function addSlider($type = 1) {
    $type = $this->prepareIntFull($type);
    $vis = 0;
    if($type == 2) $vis = 1;
    $query = "INSERT INTO msf_slider (type, vis) VALUES ('$type', '$vis')";
    if(!($result = $this->query($query))) die('<h1>Ошибка в работе сайта 8</h1>');
    return ;
  }
  
  // Получаем изображения для Админ-панели.
  public function getSliderId($id){
    $id = $this->prepareIntFull($id);
    $query = "SELECT id, type, link, img, text, vis FROM msf_slider WHERE id = '$id' ";
    return $this->getSearchArray($query);
  }
  
  // Получаем изображения для Админ-панели.
  public function getSliderAdm(){
    $query = "SELECT id, type, link, img, text, vis FROM msf_slider ORDER BY type ";
    return $this->getSearchArray($query);
  }
  
  // Изменяем запись изображение слайдера в базе
  public function changeSlider($id, $vis, $link, $text){
    $vis = $this->prepareIntFull($vis);
    $id = $this->prepareIntFull($id);
    $link = $this->prepareIntFull($link);
    $text = $this->prepareStringMiddle($text);
    $query = "UPDATE msf_slider SET vis = '$vis', link = '$link', text =  '$text' WHERE id = '$id' ";
    return $this->query($query);
  }
  
  //Добавляем информацию о изображении в слайдер
  public function addPicSlider($id, $img){
    $id = $this->prepareIntFull($id);
    $img = $this->prepareStringFull($img);
    $query = "UPDATE msf_slider SET img = '$img' WHERE id = '$id' ";
    return $this->query($query);
  }
  
  //Удаляем запись слайдера из базы
  public function delSlider($id){
    $id = $this->prepareIntFull($id);
    $query = "DELETE FROM msf_slider WHERE id = '$id' ";
    return $this->query($query);
  }
  
//
//   --------------------   Методы для работы с Вопрос - Ответ   --------------------
//
	//Получаем количество вопросов-ответов для страницы вопросов в базе
	public function getFaqCount($vis=0){
    $vis = $this->prepareIntFull($vis);
    if($vis){
      $query = "SELECT COUNT(*) FROM msf_faq WHERE vis = '1'";
    }else{
      $query = "SELECT COUNT(*) FROM msf_faq";
    }
		$result = $this->getSearchArray($query);
		$count = $result[0]['COUNT(*)'];
		return $count;
	}	
	// Получаем вопросы-ответы для страницы вопросов
	public function getFaq($numPage = 0){
		$numPage = ($numPage > 0) ? $numPage-1 : 0;
		$numPage = $numPage * $_SESSION['maxFaq'];
		$maxFaq = $_SESSION['maxFaq'];
		$query = "SELECT id, data, question, answer FROM msf_faq WHERE vis = '1' ORDER BY data DESC LIMIT $numPage, $maxFaq";
		return $this->getSearchArray($query);
	}
	
		// Получаем вопросы-ответы для Админ-панели.
	public function getFaqAdm($numPage = 0){
		$numPage = ($numPage > 0) ? $numPage-1 : 0;
		$numPage = $numPage * $_SESSION['maxFaq'];
		$maxFaq = $_SESSION['maxFaq'];
		$query = "SELECT id, data, name, email, phone, question, answer, vis FROM msf_faq ORDER BY data DESC LIMIT $numPage, $maxFaq";
		return $this->getSearchArray($query);
	}

	// Изменяем вопрос в базе
	public function changeFaq($id, $vis, $userEmail, $userName, $userPhone, $userMessage, $userAnswer){
		
		$vis = $this->prepareIntFull($vis);
		$id = $this->prepareIntFull($id);
		$userEmail = $this->prepareStringFull($userEmail);
		$userName = $this->prepareStringFull($userName);
		$userPhone = $this->prepareStringFull($userPhone);
		$userMessage = $this->prepareStringFull($userMessage);
		$userAnswer = $this->prepareStringFull($userAnswer);
		$query = "UPDATE msf_faq SET vis = '$vis', name = '$userName', email = '$userEmail', phone = '$userPhone', question =  '$userMessage', answer = '$userAnswer' WHERE id = '$id' ";
		$result = $this->query($query);
		return $result;
	}

	// Добавляем вопрос в базу.
	public function addFaq($userEmail, $userName, $userPhone, $userMessage){
		$userEmail = $this->prepareStringFull($userEmail);
		$userName = $this->prepareStringFull($userName);
		$userPhone = $this->prepareStringFull($userPhone);
		$userMessage = $this->prepareStringFull($userMessage);
		$userDate = time();
		$query = "INSERT INTO msf_faq (data, name, email, phone, question) VALUES ('$userDate', '$userName', '$userEmail', '$userPhone', '$userMessage')";
		$result = $this->query($query);
		return $result;
	}

	// Добавляем сообщение в базу.
	public function addMes($userEmail, $userName, $userMessage){
		
		$userDate = time();
		$query = "INSERT INTO msf_msg (data, name, email, message) VALUES ('$userDate', '$userName', '$userEmail', '$userMessage')";
		$result = $this->query($query);
		return $result;
	}
	
//
//   --------------------   Методы для работы с Вопрос - Ответ   --------------------
//
	// Получаем массив записей для фотогаллереи
	public function getGalleryArray($numPage = 0){
		$numPage = ($numPage > 0) ? $numPage-1 : 0;
		$numPage = $numPage * $_SESSION['maxGallery'];
		$maxGallery = $_SESSION['maxGallery'];
		$query = "SELECT id, tag, name, title, descript, data FROM msf_gallery ORDER BY data DESC LIMIT $numPage, $maxGallery";
		return $this->getSearchArray($query);
	}
	
	// Получаем случайные записи для выборочной фотогалереи
	public function getSelectGalleryArray(){
		$selectId = 4;  //Количество записей в выборке фотогалереи
		$query = "SELECT id, tag, name, title, descript, data FROM msf_gallery ORDER BY RAND() LIMIT $selectId";
		return $this->getSearchArray($query);
	}
	

	// Добавляем запись в фотогалерею
	public function addGalleryItem($data = 0) {
		if($data == 0) $data = mktime(0,0,0);
		$data = $this->prepareIntFull($data);
		$query = "INSERT INTO msf_gallery (data) VALUES ('$data')";
		if(!($result = $this->query($query))) die('<h1>Ошибка в работе сайта 9</h1>');
		return ;
	}

	// Изменяем запись в фотогалерее
	public function changeGallery($id, $tag, $name, $title, $descript) {
		$id = $this->prepareIntFull($id);
		$tag = $this->prepareStringFull($tag);
		$name = $this->prepareStringFull($name);
		$title = $this->prepareStringFull($title);
		$descript = $this->prepareStringFull($descript);
		$data = time();
		$query = "UPDATE msf_gallery SET tag = '$tag', name = '$name', title = '$title', descript = '$descript', data = '$data' WHERE id = '$id' ";
		return $this->query($query);
	}
	
	// Удаляем запись в фотогалерее
	public function delGalleryItem($id) {
		$id = $this->prepareIntFull($id);
		$query = "DELETE FROM msf_gallery WHERE id = '$id' ";
		if(!($result = $this->query($query))) die('<h1>Ошибка в работе сайта 10</h1>');
		return ;
	}
	
	
	// Получаем полный массив фотографий для фотогаллереи
	public function getGalleryPicArray() {
		$query = "SELECT id_gallery, jpgsmall, jpgbig, jpgtitle FROM msf_gallery_pic";
		return $this->getSearchArray($query);
	}
	
	// Получаем массив фотографий для элемента фотогаллереи по его id
	public function getGalleryItemPic($id_gallery) {
		$id_gallery = $this->prepareIntFull($id_gallery);
		$query = "SELECT id, id_gallery, jpgsmall, jpgbig, jpgtitle FROM msf_gallery_pic WHERE id_gallery = '$id_gallery' ";
		return $this->getSearchArray($query);
	}
	
	// Удаляем записи фотографий для елемента галереи по его id
	public function delGalleryItemPic($id_gallery) {
		$id_gallery = $this->prepareIntFull($id_gallery);
		$query = "DELETE FROM msf_gallery_pic WHERE id_gallery = '$id_gallery' ";
		if(!($result = $this->query($query))) die('<h1>Ошибка в работе сайта 11</h1>');
		return ;
	}
	
	// Изменяем описание изображения по его id
	public function changePicTitle($id, $jpgtitle) {
		$id = $this->prepareIntFull($id);
		$jpgtitle = $this->prepareStringFull($jpgtitle);
		$query = "UPDATE msf_gallery_pic SET jpgtitle = '$jpgtitle' WHERE id = '$id' ";
		return $this->query($query);
	}
	
	// Добавляет изображение в элементу галлереи по его id
	public function addPicGallery($id_gallery, $jpgbig, $jpgsmall){
		$id_gallery = $this->prepareIntFull($id_gallery);
		$jpgtitle = "";
		$query = "INSERT INTO msf_gallery_pic (id_gallery, jpgbig, jpgsmall, jpgtitle) VALUES ('$id_gallery', '$jpgbig', '$jpgsmall', '$jpgtitle')";
		$this->query($query);
		return $this->insert_id;
	}
	
	// Получаем данные изображения по его id
	public function getPicItem($id) {
		$id = $this->prepareIntFull($id);
		$query = "SELECT id, id_gallery, jpgsmall, jpgbig, jpgtitle FROM msf_gallery_pic WHERE id = '$id' LIMIT 1";
		return $this->getSearchArray($query);
	}
	
	// Удаляем изображение по его id
	public function delPicItem($id) {
		$id = $this->prepareIntFull($id);
		$query = "DELETE FROM msf_gallery_pic WHERE id = '$id' ";
		return $this->query($query);
	}
	
//
//   --------------------   Методы работы с конфигурационными параметрами сайта   --------------------
//
	// Заполняем SESSION даными из конфига.
	public function setInfoSession(){
//		if(!isset($_SESSION['config'])){
			$query = "SELECT param_name, param_value, param_int FROM config";
			if(!($result = $this->query($query))) die('<h1>Ошибка в работе сайта</h1>');
			WHILE($res = $result->fetch_assoc()){
				if($res['param_int']) $res['param_value'] = $this->prepareIntFull($res['param_value']);
				$_SESSION[$res['param_name']] = $res['param_value'];
			}
			$result->close();
			$_SESSION['config'] = 1;
//		}
	}
	
	// Получаем все параметры, или один - при приходе id
	public function getConfig($id = 0){
		$id = $this->prepareIntFull($id);
		if($id > 0){
			$query = "SELECT id, param_text, param_name, param_value, param_int FROM config WHERE id = '$id'";
		}else{
			$query = "SELECT id, param_text, param_name, param_value, param_int FROM config";
		}
		return $this->getSearchArray($query);
	}
	
	// Изменяем конфигурационный параметр 
	public function setConfig($id, $value, $param_int) {
		$id = $this->prepareIntFull($id);
		if($param_int){
			$value = $this->prepareIntFull($value);
		}else{
			$value = $this->prepareStringMiddle($value);
		}
		$query = "UPDATE config SET param_value = '$value' WHERE id = '$id' ";
		return $this->query($query);
	}
	
//
//   --------------------   Методы для работы с Пользователями   --------------------
//
	//Поиск пользователя в базе по е-маил и паролю. Возвращает массив с данными пользователя
	public function findUser($userMail, $userPas){
		
		$userMail = $this->prepareStringFull($userMail);
		$userPas = $this->prepareStringFull(sha1($userPas));
		
		$query = "SELECT date_register, email, name, surname, skype, phone, code FROM msf_users WHERE email = '$userMail' AND password = '$userPas' AND del = 0 LIMIT 1";
		if(!($result = $this->query($query))) die('<h1>Ошибка в работе сайта</h1>');
		//Задержим скрипт на секунду для задержки перебора
		sleep(1);
		return $result->fetch_assoc();
		
	}
	
//
//   --------------------   Методы для работы с Администраторами   --------------------
//
	//Поиск администратора в базе по е-маил и паролю. Возвращает массив с данными администратора
	public function findAdmin($adminMail, $adminPas){
		
		$adminMail = $this->prepareStringFull($adminMail);
		
		$query = "SELECT date_register, email, name, surname, skype, phone FROM msf_admins WHERE email = '$adminMail' AND password = '$adminPas' AND del = 0 LIMIT 1";
		if(!($result = $this->query($query))) die('<h1>Ошибка в работе сайта</h1>');
		//Задержим скрипт на секунду для задержки перебора
		sleep(1);
		return $result->fetch_assoc();
	}
	
	// Изменение данных администратора при его наличии
	public function setAdminDat($email, $name, $surname, $phone, $skype, $passOld = '', $passNew = ''){
		$email = $this->prepareStringFull($email);
		$name = $this->prepareStringFull($name);
		$surname = $this->prepareStringFull($surname);
		$phone = $this->prepareStringFull($phone);
		$skype = $this->prepareStringFull($skype);
		if(!empty($passOld)){
			if($this->findAdmin($email, $passOld)){
				$query = "UPDATE msf_admins SET name = '$name', surname = '$surname', phone = '$phone', skype = '$skype', password = '$passNew' WHERE email = '$email' ";
			}else{
				return false;
			}
		}else{
			$query = "UPDATE msf_admins SET name = '$name', surname = '$surname', phone = '$phone', skype = '$skype' WHERE email = '$email' ";
		}
		return $this->query($query);
	}
	
//
//   --------------------   Методы для работы с Категориями каталога   --------------------
//
	// Получаем перечень категорий
	public function getCategory($type = 0, $vis = 0, $lang = ""){
		if(empty($lang)) $lang = $this->_lang;
		$lang = $this->prepareStringFull($lang);
    $type = $this->prepareIntFull($type);
		if( $vis ){
      if($type){
        $query = "SELECT id, type, name, translit FROM msf_category WHERE type = '$type' AND lang = '$lang' ORDER BY name";
      }else{
        $query = "SELECT id, type, name, translit FROM msf_category WHERE lang = '$lang' ORDER BY name";
      }
		}else{
      if($type){
        $query = "SELECT id, type, name, translit FROM msf_category WHERE type = '$type' AND translit != '' AND lang = '$lang' ORDER BY name";
      }else{
        $query = "SELECT id, type, name, translit FROM msf_category WHERE translit != '' AND lang = '$lang' ORDER BY name";
      }
		}
		return $this->getSearchArray($query);
	}
	
	// Возвращает id категории по ее транслитерации или false при ее отсутствии
	public function searchCategoryId($translit, $lang = ""){
		if(empty($lang)) $lang = $this->_lang;
		$lang = $this->prepareStringFull($lang);
		$translit = $this->prepareStringFull($translit);
		$query = "SELECT id FROM msf_category WHERE translit = '$translit' AND lang = '$lang' LIMIT 1";
		if(!($result = $this->query($query))) die('<h1>Ошибка в работе сайта</h1>');
		$idCategory = $result->fetch_assoc();
		if($idCategory){
			return($idCategory['id']);
		}else{
			return(false);
		}
	}
	
	// Добавляем новую категорию
	public function addCategory($lang = ""){
		if(empty($lang)) $lang = $this->_lang;
		$lang = $this->prepareStringFull($lang);
		$query = "INSERT INTO msf_category (lang) VALUES ('$lang')";
		return $this->query($query);
	}
	
	// Изменяем существующую категорию
	public function changeCategory($id, $type, $name, $lang = ""){
		if(empty($lang)) $lang = $this->_lang;
		$lang = $this->prepareStringFull($lang);
		$id = $this->prepareIntFull($id);
    $type = $this->prepareIntFull($type);
		$name = $this->prepareStringFull($name);
		$translit = Lang::translit($name);
		$translit = $this->prepareStringFull($translit);
		$query = "UPDATE msf_category SET type = '$type', name = '$name', translit = '$translit' WHERE id = '$id' ";
		return $this->query($query);
	}
	
	// Удаляем существующую категорию
	public function delCategory($id, $lang = ""){
		if(empty($lang)) $lang = $this->_lang;
		$lang = $this->prepareStringFull($lang);
		$id = $this->prepareIntFull($id);
		$query = "DELETE FROM msf_category WHERE id = '$id' AND lang = '$lang'";
		return $this->query($query);
	}
	
	
//
//   --------------------   Методы для работы с Каталогом продукции   --------------------
//
	
	//Получаем перечень продукции при необходимости - по категориям и постранично - только видимые на сайте
	public function getCatalog($category, $type, $numPage, $lang = ""){
		if(empty($lang)) $lang = $this->_lang;
		$lang = $this->prepareStringFull($lang);
		$category = $this->prepareStringFull($category);
		$type = $this->prepareIntFull($type);
    $numPage = $this->prepareIntFull($numPage);
		$numPage = ($numPage > 0) ? $numPage-1 : 0;
		$numPage = $numPage * $_SESSION['maxCatalog'];
		$maxCatalog = $_SESSION['maxCatalog'];
		if(empty($category)){
			$query = "SELECT * FROM msf_catalog WHERE type = '$type' AND lang = '$lang' AND vis = '1' ORDER BY name LIMIT $numPage, $maxCatalog";
		}else{
			$id_category = $this->searchCategoryId($category);
			if($id_category){
				$query = "SELECT * FROM msf_catalog WHERE id_category = '$id_category' AND lang = '$lang' AND vis = '1' ORDER BY name LIMIT $numPage, $maxCatalog";
			}else{
				//Если пришла не отсутствующая в базе категория - возвращаем пустой массив
				return(array());
			}
		}
		return $this->getSearchArray($query);
	}
	
	
	//Получаем перечень продукции по id категории при необходимости постранично или все
	public function getPriceCategoryId($categoryId, $num = 0 ,$lang = ""){
		if(empty($lang)) $lang = $this->_lang;
		$lang = $this->prepareStringFull($lang);
		$categoryId = $this->prepareIntFull($categoryId);
		$num = $this->prepareIntFull($num);
		
		if($num > 0){
			$numPage = ($num - 1) * $_SESSION['maxCatalog'];
			$maxCatalog = $_SESSION['maxCatalog'];
			$query = "SELECT * FROM msf_catalog WHERE id_category = '$categoryId' AND lang = '$lang' ORDER BY name LIMIT $numPage, $maxCatalog";
		}else{
			$query = "SELECT * FROM msf_catalog WHERE id_category = '$categoryId' AND lang = '$lang' ORDER BY name";
			
		}
		return $this->getSearchArray($query);
	}
	
	
	//Получаем выборочный перечень продукции.
	public function getViewedCatalog($type, $countViewed, $lang = ""){
		if(empty($lang)) $lang = $this->_lang;
		$lang = $this->prepareStringFull($lang);
		$countViewed = $this->prepareIntFull($countViewed);
    $type = $this->prepareIntFull($type);
		$query = "SELECT * FROM msf_catalog WHERE type = '$type' AND viewed = '1' AND vis = '1' AND lang = '$lang' ORDER BY RAND() LIMIT $countViewed";
		return $this->getSearchArray($query);
	}
	
// Удаляем перечень продукции по id категории
	public function delPriceCategoryId($categoryId, $lang = ""){
		if(empty($lang)) $lang = $this->_lang;
		$lang = $this->prepareStringFull($lang);
		$categoryId = $this->prepareIntFull($categoryId);
		$query = "DELETE FROM msf_catalog WHERE id_category = '$categoryId' AND lang = '$lang'";
		return $this->query($query);
	}
	
// Удаляем единицу продукции по id
	public function delPriceId($id, $lang = ""){
		if(empty($lang)) $lang = $this->_lang;
		$lang = $this->prepareStringFull($lang);
		$id = $this->prepareIntFull($id);
		$query = "DELETE FROM msf_catalog WHERE id = '$id'";
		return $this->query($query);
	}
	
	
	//Подсчитывает количество продукции в категории. Или всей продукции, если пришло ""
	public function getCatalogCount($type, $category = "", $lang = ""){
		if(empty($lang)) $lang = $this->_lang;
		$lang = $this->prepareStringFull($lang);
		$category = $this->prepareStringFull($category);
    $type = $this->prepareIntFull($type);
		if(empty($category)){
			$query = "SELECT COUNT(*) FROM msf_catalog WHERE type = '$type' AND lang = '$lang' AND vis = '1'";
		}else{
			$id_category = $this->searchCategoryId($category);
			if($id_category){
				$query = "SELECT COUNT(*) FROM msf_catalog WHERE id_category = '$id_category' AND lang = '$lang' AND vis = '1'";
			}else{
				//Если пришла не отсутствующая в базе категория - возвращаем пустой массив
				return(0);
			}
		}
		$result = $this->getSearchArray($query);
		$count = $result[0]['COUNT(*)'];
		return $count;
	}
	
		//Подсчитывает количество продукции в категории. Или всей продукции, если пришло 0
	public function getCatalogCountNum($categoryId = 0, $lang = ""){
		if(empty($lang)) $lang = $this->_lang;
		$lang = $this->prepareStringFull($lang);
		$categoryId = $this->prepareIntFull($categoryId);
		if($categoryId){
			$query = "SELECT COUNT(*) FROM msf_catalog WHERE id_category = '$categoryId' AND lang = '$lang'";
		}else{
			$query = "SELECT COUNT(*) FROM msf_catalog WHERE lang = '$lang'";
		}
		$result = $this->getSearchArray($query);
		$count = $result[0]['COUNT(*)'];
		return $count;
	}
	
	// Получаем информацию о продукте по его id
	public function getPriceId($id, $lang = ""){
		if(empty($lang)) $lang = $this->_lang;
		$lang = $this->prepareStringFull($lang);
		$id = $this->prepareIntFull($id);
		$query = "SELECT * FROM msf_catalog WHERE id = '$id' AND lang = '$lang' LIMIT 1";
		return $this->getSearchArray($query);
	}
	
	// Добавляет продукт в каталог по id категории. Возврат - id добавленного продукта
	public function addPriceItem($id_category = 0, $type = 0, $lang = ""){
		if(empty($lang)) $lang = $this->_lang;
		$lang = $this->prepareStringFull($lang);
		$id_category = $this->prepareIntFull($id_category);
    $type = $this->prepareIntFull($type);
		$data = time();
		$query = "INSERT INTO msf_catalog (id_category, type, data, lang) VALUES ('$id_category', '$type', '$data', '$lang')";
		$this->query($query);
		return $this->insert_id;
	}
	
	// Добавляем информацию о изображении в продукт по id
	public function addPicPrice($id, $imgBig, $imgSmall, $lang = ""){
		if(empty($lang)) $lang = $this->_lang;
		$lang = $this->prepareStringFull($lang);
		$id = $this->prepareIntFull($id);
		$imgBig = $this->prepareStringFull($imgBig);
		$imgSmall = $this->prepareStringFull($imgSmall);
		$query = "UPDATE msf_catalog SET img_big = '$imgBig', img_small = '$imgSmall' WHERE id = '$id' AND lang = '$lang'";
		return $this->query($query);
	}
	
	
	// Изменяем существующий продукт по id
	public function changePrice($id, $vis, $viewed, $name, $descript, $like, $video, $lang = ""){
		if(empty($lang)) $lang = $this->_lang;
		$lang = $this->prepareStringFull($lang);
		$id = $this->prepareIntFull($id);
		$vis = $this->prepareIntFull($vis);
		$viewed = $this->prepareIntFull($viewed);
		$name = $this->prepareStringFull($name);
		$descript = $this->prepareStringFull($descript);
    $video = $this->prepareStringFull($video);
		$like = $this->prepareIntFull($like);
		$query = "UPDATE `msf_catalog` SET `vis` = '$vis', `viewed` = '$viewed', `name` = '$name', `descript` = '$descript', `like` = '$like', `video` = '$video' WHERE `id` = '$id' ";
		return $this->query($query);
	}
	
  // Получаем массив фотографий для элемента каталога по его id
  public function getPriceItemPic($id_catalog) {
    $id_catalog = $this->prepareIntFull($id_catalog);
    $query = "SELECT id, id_catalog, jpgsmall, jpgbig, jpgtitle FROM msf_catalog_pic WHERE id_catalog = '$id_catalog' ";
    return $this->getSearchArray($query);
  }
  
  // Добавляет изображение в элемент каталога по его id
  public function addPicPriceCatalog($id_catalog, $jpgbig, $jpgsmall){
    $id_catalog = $this->prepareIntFull($id_catalog);
    $jpgtitle = "";
    $query = "INSERT INTO msf_catalog_pic (id_catalog, jpgbig, jpgsmall, jpgtitle) VALUES ('$id_catalog', '$jpgbig', '$jpgsmall', '$jpgtitle')";
    $this->query($query);
    return $this->insert_id;
  }

  // Изменяем описание изображения по его id в галерее каталога
  public function changePicTitlePrice($id, $jpgtitle) {
    $id = $this->prepareIntFull($id);
    $jpgtitle = $this->prepareStringFull($jpgtitle);
    $query = "UPDATE msf_catalog_pic SET jpgtitle = '$jpgtitle' WHERE id = '$id' ";
    return $this->query($query);
  }

  // Получаем данные изображения по его id  в галерее каталога
  public function getPicItemPrice($id) {
    $id = $this->prepareIntFull($id);
    $query = "SELECT id, id_catalog, jpgsmall, jpgbig, jpgtitle FROM msf_catalog_pic WHERE id = '$id' LIMIT 1";
    return $this->getSearchArray($query);
  }
  
  // Удаляем изображение по его id в галерее каталога
  public function delPicItemPrice($id) {
    $id = $this->prepareIntFull($id);
    $query = "DELETE FROM msf_catalog_pic WHERE id = '$id' ";
    return $this->query($query);
  }

  // Увеличиваем кол-вл лайков товара по его id
  public function addLikeCatalog($id) {
    $id = $this->prepareIntFull($id);
    $query = "UPDATE msf_catalog SET `like` = `like`+1 WHERE id = '$id'";
    return $this->query($query);
  }

  
}