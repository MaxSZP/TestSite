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
	

  
}