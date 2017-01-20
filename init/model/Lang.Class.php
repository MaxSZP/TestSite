<?php
class Lang{
	
	static $params = array(); //Массив значений для страниц сайта на нужном языке
	
	// Устанавливает язык сайта. При наличии GET-параметра lang - изменяем язык сайта.
	static function change() {
		//Период действия куки с выбранным языком - 30 дней
		$date = time() + 30*24*60*60;
		if(!isset($_SESSION['lang']) && !isset($_COOKIE['lang'])){
			$_SESSION['lang'] = Config::$defLang ;
			setcookie('lang',trim($_SESSION['lang']),$date);
		}else if(isset($_SESSION['lang']) && !isset($_COOKIE['lang'])) {
			setcookie('lang',trim($_SESSION['lang']),$date);
		}else if(!isset($_SESSION['lang']) && isset($_COOKIE['lang'])) {
			if(isset(Config::$langList[$_COOKIE['lang']])){
				$_SESSION['lang'] = $_COOKIE['lang'] ;
			}else{
				$_SESSION['lang'] = Config::$defLang ;
				setcookie('lang',trim($_SESSION['lang']),$date);
			}
		}
		if(isset($_GET['lang']) && isset(Config::$langList[$_GET['lang']])){
				$_SESSION['lang'] = $_GET['lang'];
				setcookie('lang',trim($_SESSION['lang']),$date);
			}
		return ;
	}
	
	// При наличии в $params переданного ключа - возвращает его значение, иначе - сам отсутствующий ключ
	static function _($key) {
		if(count(self::$params) == 0){
			$db = DbMysqli::getInstance();
			$tmpParams = $db->getBlockParamsAll($_SESSION['lang']);
			foreach($tmpParams as $tmpKey){
				self::$params[$tmpKey['param_name']] = $tmpKey['param_value'];
			}
			unset($tmpKey, $tmpParams);
		}
		
		if(isset(self::$params[$key])){
			return self::$params[$key];
		}else{
			return trim(htmlspecialchars($key));
		}
		
	}
	
	// Возвращает текущий язык для сайта
	static function getLang() {
		if(isset($_SESSION['lang'])){
			return($_SESSION['lang']);
		}else{
			return(Config::$defLang);
		}
	}
	
	// Транслитерация с текущего языка на английский для формирования ссылок на страницы
	static function translit($data) {
		$enLang = "en";
		if(isset($_SESSION['lang'])){
			$lang = $_SESSION['lang'];
		}else{
			$lang = Config::$defLang;
		}
		if($lang = "ru"){
			//На всякий случай добавляем украинские буквы
			$translit = array(
				'а' => 'a',   'б' => 'b',   'в' => 'v',
				'г' => 'g',   'д' => 'd',   'е' => 'e',
				'ё' => 'yo',   'ж' => 'zh',  'з' => 'z',
				'и' => 'i',   'й' => 'j',   'к' => 'k',
				'л' => 'l',   'м' => 'm',   'н' => 'n',
				'о' => 'o',   'п' => 'p',   'р' => 'r',
				'с' => 's',   'т' => 't',   'у' => 'u',
				'ф' => 'f',   'х' => 'x',   'ц' => 'c',
				'ч' => 'ch',  'ш' => 'sh',  'щ' => 'shh',
				'ь' => '',    'ы' => 'y',    'ъ' => '',
				'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
				'А' => 'A',   'Б' => 'B',   'В' => 'V',
				'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
				'Ё' => 'YO',   'Ж' => 'Zh',  'З' => 'Z',
				'И' => 'I',   'Й' => 'J',   'К' => 'K',
				'Л' => 'L',   'М' => 'M',   'Н' => 'N',
				'О' => 'O',   'П' => 'P',   'Р' => 'R',
				'С' => 'S',   'Т' => 'T',   'У' => 'U',
				'Ф' => 'F',   'Х' => 'X',   'Ц' => 'C',
				'Ч' => 'CH',  'Ш' => 'SH',  'Щ' => 'SHH',
				'Ь' => '  ',  'Ы' => 'Y',    'Ъ' => '',
				'Э' => 'E',   'Ю' => 'YU',  'Я' => 'YA',
				'є' => 'e',  'Є' => 'E',  'і' => 'i', 
				'І' => 'I',  'ї' => 'i',  'Ї' => 'I',
				' ' => '-',
			);
			 // транслитерация.
			$itog = strtr($data, $translit);
		}elseif($lang = $enLang){
			// Для Английского обеспечить замену пробелов на -
			$translit = array(
				' ' => '-',
			);
			 // транслитерация.
			$itog = strtr($data, $translit);
		}
		$itog = strtolower($itog);
		return($itog);
	}
	
	
	
}