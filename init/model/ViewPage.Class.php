<?php
class ViewPage{
	
	public $idPage;
	public $namePage;
	public $commentPage;
	public $titlePage;
	public $descriptPage;
	public $keywordPage;
	public $crumbPage;
	public $namePageId;
	public $classPage;
	
//Формирует страницу для вывода на экран.
	public function render() {
		$paramArray = array();
		
		$fc = FrontController::getInstance();
		$db = DbMysqli::getInstance();
		$params = $fc->getParams();
		
		//Получаем параметры для title, description, Keywords
		$pageTitle = $this->titlePage;
		$pageDescription = $this->descriptPage;
		$pageKeywords = $this->keywordPage;
		
		//Если запрос на вывод новости - используем данные заголовка для title etc
		if( isset($params['name'], $params['newsId']) && $params['name'] === "news" && $params['newsId'] > 0 ){
			if( $newsDat = $db->getNewsId($params['newsId']) ){
				$pageTitle = Config::$titleNews.$newsDat[0]['title'];
				$pageDescription = Config::$titleNews.$newsDat[0]['title'];
				$pageKeywords = Config::$titleNews.$newsDat[0]['title'];
			}
		}
		
		//Получаем перечень блоков для выводимой страницы по id страницы
		$result = $db->getBlocksPage($this->idPage);
		
		//выводим в буфер блоки выводимой страницы
		ob_start();
		// перебираем блоки для текущей страницы
		foreach($result as $blockArray){
			$block_id = trim(str_ireplace(Config::$end_tpl,  '', $blockArray['tpl_block']));
			
			//Если меню - вытягиваем массив меню.
			if ($block_id === Config::$blockMenu){
				$navArray = $db->getNavArray();
			}
			//Если редактируемая страница - получаем содержимое из базы
			if ($block_id === Config::$blockMain){
				$mainBlock = $db->getMainBlock($this->namePageId);
			}
			
			// Параметры тянуться из единого массива через класс Lang
			
			include(dirname(__FILE__) . Config::$dir_tpl . trim($blockArray['tpl_block']));
			
		}
		return ob_get_clean();
	}
	
	
	//Формирует блок картинок элемента галлереи по его ID для админ-панели
	public function getGalleryPic($id) {
		
		$db = DbMysqli::getInstance();
		
		//Получаем массив картинок из базы по id
		$picArray = $db->getGalleryItemPic($id);
		
		ob_start();
		
		include(dirname(__FILE__) . Config::$dir_tpl . Config::$editGalleryPic . Config::$end_tpl);
		
		return ob_get_clean();
	}
	
	
	//Формирует блок каталога для админ-панели по ID категории и номеру страницы
	public function getPrice($idCategory, $num, $categoryName) {
		
		$db = DbMysqli::getInstance();
		$categoryName = $db->prepareStringFull($categoryName);
		
		//Получаем массив продукции по ID категории и номеру страницы
		$price = $db->getPriceCategoryId($idCategory, $num);
		
		ob_start();
		
		include(dirname(__FILE__) . Config::$dir_tpl . Config::$editPrice . Config::$end_tpl);
		
		return ob_get_clean();
	}
	
	//Формирует блок пустого элемента каталога для админ-панели по ID
	public function getPriceItem($idItem) {
		
		ob_start();
		
		include(dirname(__FILE__) . Config::$dir_tpl . Config::$editPriceItem . Config::$end_tpl);
		
		return ob_get_clean();
	}
	
  //Формирует блок картинок элемента каталога по его ID для админ-панели
  public function getPricePic($id) {
    
    $db = DbMysqli::getInstance();
    
    //Получаем массив картинок из базы по id
    $picArray = $db->getPriceItemPic($id);
    
    ob_start();
    
    include(dirname(__FILE__) . Config::$dir_tpl . Config::$editPricePic . Config::$end_tpl);
    
    return ob_get_clean();
  }
	
	
	
}