<?php
class PageController implements IController {

// Формируем страницу для вывода
	public function viewAction() {
		$fc = FrontController::getInstance();
		$db = DbMysqli::getInstance();
		$params = $fc->getParams();
		$viewPage = new ViewPage();
		$admin = new AdminAction();
		
		//При необходимости - если пришел GET-параметр lang или язык не установлен - изменяем-устанавливаем язык для сайта
		Lang::change();
		
		// проверяем наличие параметра имени - URL страницы
		if (!empty($params[Config::$namePage])){
			$namePage = $params[Config::$namePage];
			//Если пришла страница входа в админ-панели - пробуем авторизовать администратора.
			if($params[Config::$namePage] == Config::$adminLogin) $admin->validAdmin();
			
			//Исключение!
			//Если приходит logout - убираем авторизацию администратора и выводим вход в админпанель
			if ( $params[Config::$namePage] == Config::$adminLogout ) {
				$admin->unsetAdmin();
				// перенаправляем на страницу авторизации
				$url_login=ROOT.Config::$adminLogin ;
				header ("Location: $url_login", true, 302);
				exit();
			}
			
			//Если страница существует
			if($result = $db->getPageName($namePage)){
				
				// Если администратор не авторизован - страницы админ-панели не выводим
				if ( $result[0]['admin_page'] && !$admin->isSetAdmin() ) {
					$namePage = Config::$page404;
					$result = $db->getPageName($namePage);
				}
			}else{
				$namePage = Config::$page404;
				$result = $db->getPageName($namePage);
			}
		} else{
			$namePage = Config::$page404;
			$result = $db->getPageName($namePage);
		}
		
		$viewPage->namePageId = $result[0]['name'];
		$viewPage->classPage = $result[0]['class'];
		$viewPage->namePage = $result[0]['page_name'];
		$viewPage->idPage = $result[0]['id'];
		$viewPage->commentPage = $result[0]['comment'];
		$viewPage->titlePage = $result[0]['title'];
		$viewPage->descriptPage = $result[0]['descript'];
		$viewPage->keywordPage = $result[0]['keyword'];
		$viewPage->crumbPage = $result[0]['crumb'];
		
		$fc->setBody($viewPage->render());
	}
	
	
	// По данным _POST получаем редактируемый блок
	public function getmainblockAction(){
		// Проверка авторизации
		if ( !AdminAction::isSetAdmin() ) die(Config::$autorizErrorMes);
		
		$fc = FrontController::getInstance();
		$db = DbMysqli::getInstance();
		
		$params = $fc->getParams();
		$itog = '';
		// При наличии параметров - получаем редактируемый блок
		if(isset($_POST['id'], $_POST['page'],$_POST['sesID']) && (session_id() == $_POST['sesID'])){
			$itog = $db->getMainBlock($_POST['page']);
		}
		$fc->setBody($itog);
	}
	
	// Изменяем редактируемый блок
	public function changemainblockAction(){
		// Проверка авторизации
		if ( !AdminAction::isSetAdmin() ) die(Config::$autorizErrorMes);
		
		$fc = FrontController::getInstance();
		$db = DbMysqli::getInstance();
		$params = $fc->getParams();
		$itogArr['err'] = 1;
		$itogArr['mess'] = Lang::_('badBase');
		
		// Если пришли данные для изменения
		if(isset($_POST['id'], $_POST['page'], $_POST['mainblock'], $_POST['sesID']) && $_POST['sesID'] === session_id()){
			if($db->setMainblock($_POST['page'], $_POST['mainblock'])){
				$itogArr['err'] = 0;
				$itogArr['mess'] = Lang::_('goodBase');
			}
		}
		$fc->setBody(json_encode($itogArr));
	}
	
	//Добавляем новую страницу сайта
	public function addAction() {
		
		// Проверка авторизации
		if ( !AdminAction::isSetAdmin() ) die(Config::$autorizErrorMes);
		
		$fc = FrontController::getInstance();
		$db = DbMysqli::getInstance();
		
		$params = $fc->getParams();
		//Если пришло имя страницы
		if (!empty($params['name'])){
			// Проверяем нет страницы с таким именем
			if (!$db->getPageName($params['name'])){
				
				$newPage = $db->prepareStringFull($params['name']);
				$query = "INSERT INTO msf_pages (name) VALUES ('$newPage')";
				$db->query($query);
				
				if($newPageId = $db->insert_id){
					if (!empty($params['admin']) && $params['admin'] == 1){
						$query = "INSERT INTO msf_page_block (activ_block, name_block, range_block, required_block, tpl_block) 
																	(SELECT activ_block, name_block, range_block, required_block, tpl_block FROM msf_block_inst WHERE admin_page = 1)";
					}else{
						$query = "INSERT INTO msf_page_block (activ_block, name_block, range_block, required_block, tpl_block) 
											(SELECT activ_block, name_block, range_block, required_block, tpl_block FROM msf_block_inst WHERE admin_page = 0)";
					}
					
					$db->query($query);
					
					$query = "UPDATE msf_page_block SET id_page = '$newPageId' WHERE id_page = 0";
					$db->query($query);
				}
				
				$itog = 'Страница добавлена! <br>';
			}else{
				
				$itog = 'Не добавлено! Проехали! <br>';
			}
		}
		$fc->setBody($itog);
		return;
	}
	
	
}
