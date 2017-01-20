<?php
class SliderController implements IController {
	//Длинна полей в базе
	private $_maxText = 500;
	
	
	// Добавляет новую запись в базу
	public function addAction() {
		
				// Проверка авторизации
		if ( !AdminAction::isSetAdmin() ) die(Config::$autorizErrorMes);
		
    $fc = FrontController::getInstance();
    $params = $fc->getParams();
    
		$db = DbMysqli::getInstance();
		if(!empty($params['type'])){
      $_type = $params['type'];
    }else{
      $_type = 1;
    }
		$db->addSlider($_type);
		$url = ROOT . "adminslider";
		Config::location($url);
		die;
	}
	
	// Изменяет инфо по информации о слайдера в базе
	public function changeAction() {
			// Проверка авторизации
		if ( !AdminAction::isSetAdmin() ) die(Config::$autorizErrorMes);
		
		$fc = FrontController::getInstance();
		
		$itog = 0;
		if(isset($_POST['id'], $_POST['link'], $_POST['vis'], $_POST['text'], $_POST['sesID']) && (session_id() == $_POST['sesID'])){
			$db = DbMysqli::getInstance();
      $text = substr(trim($_POST['text']), 0, $this->_maxText);
      if($db->changeSlider($_POST['id'], $_POST['vis'], $_POST['link'], $text)){
        $itog = 1;
      }
		}
		$fc->setBody($itog);
		return;
	}
	
	


	// Обрабатывает и добавляет полученное изображение запись слайдера по id
	public function addpicsliderAction() {
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
		
		if( isset($params['id'], $params['type'], $_FILES['upload_file']) && $params['id']>0 && $_FILES['upload_file']['size']>0 && ($_FILES["upload_file"]["error"] == 0)){
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
					$uploads_dir = ROOT_DIR . Config::$sliderImg ;
					$nameFile = "p" . $id . "-" . time() ;
					$nameFull = $uploads_dir . $nameFile . $fileEnd ;
					$tmp_name = $_FILES["upload_file"]["tmp_name"];
					
					if (move_uploaded_file($tmp_name, $nameFull)){
						// Ресайзим файлы по нужным параметрам
						$nameFile = $nameFile . "-n.jpg" ;
						
						include_once( dirname(__FILE__) . Config::$imageResize );
						
            if($params['type'] == 1){
              $_width = Config::$widthSlider;
              $_height = Config::$heightSlider;
            }else{
              $_width = Config::$widthTitle;
              $_height = Config::$heightTitle;
            }
            
						img_resize($nameFull, $uploads_dir . $nameFile, $_width, $_height,  90, 0xFFFFFF, 0);
						unlink($nameFull);
						
						$rezult = $db->getSliderId($id);
						$db->addPicSlider($id, $nameFile);
						//Передаем данных в переменную в JS
						$fullJpgSlider = ROOT . Config::$sliderImg . $nameFile;
						$itog = '
							<script type="text/javascript">
							var elm=parent.window.document.getElementById("result-'.$id.'");
							elm.innerHTML="Файл '.$_FILES["upload_file"]["name"].' успешно загружен";
							parent.window.filePicSlider = "'.$fullJpgSlider.'";
							parent.window.addFileError = 0;
							</script>
						';
						if(isset( $rezult[0]['img']) && is_file(ROOT_DIR . Config::$sliderImg . $rezult[0]['img']) ){
							unlink(ROOT_DIR . Config::$sliderImg . $rezult[0]['img']);
						}
					}
				}
			}
		}
		$fc->setBody($itog);
		return;
	}
	
	// Удаляет изображение и запись по его id
	public function delAction() {
		// Проверка авторизации
		if ( !AdminAction::isSetAdmin() ) die(Config::$autorizErrorMes);
		$fc = FrontController::getInstance();
		$itog = 0;
		if(isset($_POST['id'], $_POST['sesID']) && (session_id() == $_POST['sesID'])){
			$db = DbMysqli::getInstance();
			if( $rezult = $db->getSliderId($_POST['id']) ){
				if( ($rezult[0]['img'] != "") && is_file(ROOT_DIR.Config::$sliderImg.$rezult[0]['img']) ){
					unlink(ROOT_DIR.Config::$sliderImg.$rezult[0]['img']);
				}
				$db->delSlider($_POST['id']);
				$itog = 1;
			}
		}
		$fc->setBody($itog);
		return;
	}
	
}