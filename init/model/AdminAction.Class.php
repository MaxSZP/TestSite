<?php
class AdminAction{
	
	
	// Проверка авторизации админа и авторизация при наличии необходимых данных.
	public function validAdmin() {
		
		// По умолчанию - не авторизован
		$autoriz = 0;
		//Проверяем, не авторизован-ли уже админ в текущей сессии
		
		
		if ( self::isSetAdmin() ){
			// админ авторизован в текущей сесcии
			$autoriz = 1;
		} else {
			// админ не авторизован в текущей сесcии или проблемы с данными проверки - очищаем данные в сессии
			$this->unsetAdmin() ;
			// Если в POST пришли данные для авторизации
			if(isset($_POST['email']) && isset($_POST['pass'])) {
				// Если админ есть в базе - авторизовываем
				$admin = $this->checkAdmin($_POST['email'], $_POST['pass']);
				if ($admin) {
					$autoriz = 1;
				}
			}
		}
	return $autoriz ;
	}
	
	
	// авторизация пользователя при его наличии в базе. 
	public function checkAdmin($adminMail, $adminPas){
		$adminPas = $this->getPass($adminPas);
		$db = DbMysqli::getInstance();
		$admin = $db->findAdmin($adminMail, $adminPas);
		
		if ($admin){
			$_SESSION['adminRegister'] = 1;
			$_SESSION['adminEmail'] = $admin['email'];
			$_SESSION['adminName'] = $admin['name'];
			$_SESSION['adminSurname'] = $admin['surname'];
			$_SESSION['adminSkype'] = $admin['skype'];
			$_SESSION['adminPhone'] = $admin['phone'];
			$_SESSION['adminSessionID'] = session_id();
			$_SESSION['adminIP'] = Config::GetRealIp();
			}
		return $admin;
	}
	
	// Проверяем, авторизован-ли админ на сайте
	public static function isSetAdmin(){
		
		if ( isset($_SESSION['adminRegister'], $_SESSION['adminSessionID'], $_SESSION['adminIP']) 
																					&& ($_SESSION['adminRegister'] == 1) 
																					&& (session_id() == $_SESSION['adminSessionID'])
																					&& ($_SESSION['adminIP'] == Config::GetRealIp())
																					&& !empty($_SESSION['adminName'])
																					&& !empty($_SESSION['adminEmail']) 
																					){
			// админ авторизован в текущей сесcии
			return true ;
		} else {
			// админ НЕ авторизован в текущей сесcии
			return false ;
		}
	}
	
	// Возвращает обработанный пароль для сравнения с хранимым в базе или записи в базу
	public static function getPass($pass){
//		return hash("sha256", $pass);
		return sha1($pass);
	}
	
	
	
	
	// Удаление данных админа из сессии
	public function unsetAdmin(){
		if (isset($_SESSION['adminRegister'])) unset($_SESSION['adminRegister']) ;
		if (isset($_SESSION['adminSessionID'])) unset($_SESSION['adminSessionID']) ;
		if (isset($_SESSION['adminIP'])) unset($_SESSION['adminIP']) ;
		if (isset($_SESSION['adminName'])) unset($_SESSION['adminName']) ;
		if (isset($_SESSION['adminEmail'])) unset($_SESSION['adminEmail']) ;
		if (isset($_SESSION['adminSurname'])) unset($_SESSION['adminSurname']) ;
		if (isset($_SESSION['adminSkype'])) unset($_SESSION['adminSkype']) ;
		if (isset($_SESSION['adminPhone'])) unset($_SESSION['adminPhone']) ;
	}




}