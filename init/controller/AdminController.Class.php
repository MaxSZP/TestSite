<?php
class AdminController implements IController {

	//Проверяем и изменяем информацию о текущем Администраторе
	public function changeinfoAction() {
		$fc = FrontController::getInstance();
		$db = DbMysqli::getInstance();
		$params = $fc->getParams();
		$itogArr['err'] = 1;
		$itogArr['mess'] = Lang::_('badBase');
		
		// Если администратор авторизован
		if ( AdminAction::isSetAdmin() ) {
			// Если пришли данные для изменения
			if(isset($_POST['name'], $_POST['surname'], $_POST['phone'], $_POST['skype'], $_POST['pass'], $_POST['pass1'], $_POST['pass2'], $_POST['sesID']) && $_POST['sesID'] === session_id()){
				$admin = new AdminAction();
				if(!empty($_POST['pass']) && !empty($_POST['pass1']) && !empty($_POST['pass2'])){
					// Если пришли данные для изменения пароля
					if($_POST['pass1'] === $_POST['pass2']){
						$passOld = $admin->getPass($_POST['pass']) ;
						$passNew = $admin->getPass($_POST['pass1']) ;
						if($db->setAdminDat($_SESSION['adminEmail'], $_POST['name'], $_POST['surname'], $_POST['phone'], $_POST['skype'], $passOld, $passNew)){
							$admin->checkAdmin($_SESSION['adminEmail'], $passNew);
							$itogArr['err'] = 0;
							$itogArr['mess'] = Lang::_('goodBase');
						}
					}
				}else{
					// Если меняем только данные пользователя
					$db->setAdminDat($_SESSION['adminEmail'], $_POST['name'], $_POST['surname'], $_POST['phone'], $_POST['skype']);
					$_SESSION['adminName'] = $db->prepareStringFull($_POST['name']);
					$_SESSION['adminSurname'] = $db->prepareStringFull($_POST['surname']);
					$_SESSION['adminSkype'] = $db->prepareStringFull($_POST['phone']);
					$_SESSION['adminPhone'] = $db->prepareStringFull($_POST['skype']);
					$itogArr['err'] = 0;
					$itogArr['mess'] = Lang::_('goodBase');
				}
			}
		}
		$fc->setBody(json_encode($itogArr));
	}
	
	
	
}
