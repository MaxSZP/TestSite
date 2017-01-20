<?php
$error = "";
$msg = "";
$fileElementName = 'fileToUpload';
$i = 0;
$files_count = sizeof($_FILES[$fileElementName]["name"]);



for ($i = 0; $i < $files_count-1; $i++) {	
	if(!empty($_FILES[$fileElementName]['error'][$i]))
	{
		switch($_FILES[$fileElementName]['error'][$i])
		{

			case '1':
				$error = 'размер загруженного файла превышает размер установленный параметром upload_max_filesize  в php.ini ';
				break;
			case '2':
				$error = 'размер загруженного файла превышает размер установленный параметром MAX_FILE_SIZE в HTML форме. ';
				break;
			case '3':
				$error = 'загружена только часть файла ';
				break;
			case '4':
				$error = 'файл не был загружен (Пользователь в форме указал неверный путь к файлу). ';
				break;
			case '6':
				$error = 'неверная временная дирректория';
				break;
			case '7':
				$error = 'ошибка записи файла на диск';
				break;
			case '8':
				$error = 'загрузка файла прервана';
				break;
			case '999':
			default:
				$error = 'No error code avaiable';
		}
	}elseif(empty($_FILES[$fileElementName]['tmp_name'][$i]) || $_FILES[$fileElementName]['tmp_name'][$i] == 'none')
	{
		$error = 'Нечего загружать..';
	}else 
	{
			if (strstr($_FILES[$fileElementName]['name'][$i], ".doc")) {
			$new_name = "price.doc"; } else {
			$new_name = "price.xls";	
			}
			$new_name_tmp = $_FILES[$fileElementName]['tmp_name'][$i];
			if (file_exists("../../price/".$_POST['dir'].$new_name)){
      			@unlink("../../price/".$_POST['dir'].$new_name);
				$msg .= $_POST['dir'].$new_name;
				move_uploaded_file($new_name_tmp, "../../price/".$_POST['dir'].$new_name);
			
			}
    		else{
    		//	mkdir("../../price", 0755);
				$msg .= $_POST['dir'].$new_name;
    			//$msg .= " File Temp Name: " . $_FILES['fileToUpload']['tmp_name'] . "<br/>";
				move_uploaded_file($new_name_tmp, "../../price/".$_POST['dir'].$new_name);
			}
			//for security reason, we force to remove all uploaded file
			@unlink($_FILES[$fileElementName][$i]);		
	}		                      
	echo "<input name='file' type='text'  size='100' value='".$msg."' />";
}
?>