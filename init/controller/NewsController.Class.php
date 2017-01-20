<?php
class NewsController implements IController {
	//Длинна полей в базе
	private $_maxTitle = 500;
	private $_maxText = 10000;
	
	
	// Добавляет новую новость в базу
	public function addAction() {
		
				// Проверка авторизации
		if ( !AdminAction::isSetAdmin() ) die(Config::$autorizErrorMes);
		
		$db = DbMysqli::getInstance();
		
		$db->addNews();
		
		$url = ROOT . "adminnews";
		Config::location($url);
		die;
	}
	
	// Изменяет существующую новость в базе
	public function changeAction() {
			// Проверка авторизации
		if ( !AdminAction::isSetAdmin() ) die(Config::$autorizErrorMes);
		
		$fc = FrontController::getInstance();
		
		$itog = 0;
		if(isset($_POST['id'], $_POST['data'], $_POST['vis'], $_POST['title'], $_POST['text'], $_POST['sesID']) && (session_id() == $_POST['sesID'])){
			$db = DbMysqli::getInstance();
			$dateArray = explode(".", $_POST['data']);
			if(count($dateArray) == 3 && checkdate($dateArray[1], $dateArray[0], $dateArray[2])){
				$date = new DateTime($_POST['data']);
				$data = $date->format('U');
				$title = substr(trim($_POST['title']), 0, $this->_maxTitle);
				$text = substr(trim($_POST['text']), 0, $this->_maxText);
				if($db->changeNews($_POST['id'], $_POST['vis'], $data, $title, $text)){
					$itog = 1;
				}
			}
		}
		$fc->setBody($itog);
		return;
	}
	
	


	// Обрабатывает и добавляет полученное изображение в новость по id
	public function addpicnewsAction() {
		// Проверка авторизации
		if ( !AdminAction::isSetAdmin() ) die(Config::$autorizErrorMes);
		$fc = FrontController::getInstance();
		$params = $fc->getParams();
		//Если данные не полные - в addFileError прийдет 1
		$itog = '
			<script type="text/javascript">
				parent.window.addFileError = 1;
			</script>
		';
		
		if( isset($params['id'], $_FILES['upload_file']) && $params['id']>0 && $_FILES['upload_file']['size']>0 && ($_FILES["upload_file"]["error"] == 0)){
			//При остальных ошибках в addFileError - 2 и получим id блока
			$db = DbMysqli::getInstance();
			$id = $db->prepareIntFull($params['id']);
			$itog = '
				<script type="text/javascript">
					parent.window.addFileError = 2;
					parent.window.addFileId = '.$id.';
					var elm=parent.window.document.getElementById("result-'.$id.'");
					elm.innerHTML="Ошибка при загрузке";
				</script>
			';
			//обрабатываем и копируем в галерею полученное изображение
			
			if(is_file($_FILES["upload_file"]["tmp_name"])){
				$fileDat = getimagesize($_FILES["upload_file"]["tmp_name"]);
				$fileEnd = "" ;
				if($fileDat[2] == IMAGETYPE_JPEG || $fileDat[2] == IMAGETYPE_JPEG2000){
					$fileEnd = ".jpg";
				}elseif($fileDat[2] == IMAGETYPE_PNG) {
					$fileEnd = ".png";
				}elseif($fileDat[2] == IMAGETYPE_BMP) {
					$fileEnd = ".bmp";
				}elseif($fileDat[2] == IMAGETYPE_GIF) {
					$fileEnd = ".gif";
				}
				if($fileEnd != ""){
					$uploads_dir = ROOT_DIR . Config::$newsImg ;
					$nameFile = "p" . $id . "-" . time() ;
					$nameFull = $uploads_dir . $nameFile . $fileEnd ;
					$tmp_name = $_FILES["upload_file"]["tmp_name"];
					
					if (move_uploaded_file($tmp_name, $nameFull)){
						// Ресайзим файлы по нужным параметрам
						$nameFile = $nameFile . "-n.jpg" ;
						
						include_once( dirname(__FILE__) . Config::$imageResize );
						
						img_resize($nameFull, $uploads_dir . $nameFile, Config::$widthNews, Config::$heightNews,  90, 0xFFFFFF, 0);
						unlink($nameFull);
						
						$newsDat = $db->getNewsId($id, $nameFile);
						$db->addPicNews($id, $nameFile);
						//Передаем данных в переменную в JS
						$fullJpgNews = ROOT . Config::$newsImg . $nameFile;
						$itog = '
							<script type="text/javascript">
							var elm=parent.window.document.getElementById("result-'.$id.'");
							elm.innerHTML="Файл '.$_FILES["upload_file"]["name"].' успешно загружен";
							parent.window.filePicNews = "'.$fullJpgNews.'";
							parent.window.addFileError = 0;
							</script>
						';
						if(isset( $newsDat[0]['img_news']) && is_file(ROOT_DIR . Config::$newsImg . $newsDat[0]['img_news']) ){
							unlink(ROOT_DIR . Config::$newsImg . $newsDat[0]['img_news']);
						}
					}
				}
			}
		}
		$fc->setBody($itog);
		return;
	}
	
	// Удаляет изображение по его id
	public function delpictureAction() {
		// Проверка авторизации
		if ( !AdminAction::isSetAdmin() ) die(Config::$autorizErrorMes);
		$fc = FrontController::getInstance();
		$itog = 0;
		if(isset($_POST['id'], $_POST['sesID']) && (session_id() == $_POST['sesID'])){
			$db = DbMysqli::getInstance();
			if( $newsDat = $db->getNewsId($_POST['id']) ){
				if( ($newsDat[0]['img_news'] != "") && is_file(ROOT_DIR.Config::$newsImg.$newsDat[0]['img_news']) ){
					unlink(ROOT_DIR.Config::$newsImg.$newsDat[0]['img_news']);
				}
				$db->addPicNews($_POST['id'], "");
				$itog = 1;
			}
		}
		$fc->setBody($itog);
		return;
	}
	
}