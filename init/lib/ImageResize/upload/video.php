<?php
require_once('../Connections/connect.php');
$error = "";
$msg = "";
$fileElementName = 'fileToUploadvideo';
$i = 0;
$files_count = sizeof($_FILES[$fileElementName]["name"]);
for ($i = 0; $i < $files_count; $i++) {	
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
		$error = 'Нет файла для загрузки..';
	}else 
	{

	
			if (is_dir("../../files/".$_POST['id']."/video/")) {
				
			if (file_exists("../../files/".$_POST['id']."/" .$new_name)){
				$name1 =  str_replace(".flv","", $new_name);

				$new_name1 = $name1."_1".".flv";
				if (file_exists("../../files/".$_POST['id']."/" .$new_name1)){
				$new_name1 = $name1.filemtime("../../files/".$_POST['id']."/video/".$new_name1).".flv";
				}
		//		move_uploaded_file($new_tmp_name, "../../".$_POST['dir']."/gallery/".$_POST['desing']."/" . $new_name1);
      	//		$msg .= $new_name1;
				move_uploaded_file($new_name_tmp, "../../files/".$_POST['id']."/video/".$new_name1);
			 	$query_insert = "INSERT INTO `catalog_video` (`id`, `id_catalog`, `file`)  VALUES (NULL, '".$_POST['id']."', '".mysql_real_escape_string($new_name1)."')";
				$insert= mysql_query($query_insert, $base) or die(mysql_error());

				
			} else {
	
				move_uploaded_file($new_name_tmp, "../../files/".$_POST['id']."/video/".$new_name);
			 	$query_insert = "INSERT INTO `catalog_video` (`id`, `id_catalog`, `file`)  VALUES (NULL, '".$_POST['id']."', '".mysql_real_escape_string($new_name)."')";
				$insert= mysql_query($query_insert, $base) or die(mysql_error());

			}
			} else {

			//mkdir("../../files/".$_POST['id']."/", 0777);
			mkdir("../../files/".$_POST['id']."/video/", 0777);
			
				$msg .=  $new_name;
				move_uploaded_file($new_name_tmp, "../../files/".$_POST['id']."/video/".$new_name);
			 	$query_insert = "INSERT INTO `catalog_video` (`id`, `id_catalog`, `file`)  VALUES (NULL, '".$_POST['id']."', '".mysql_real_escape_string($new_name)."')";
				$insert= mysql_query($query_insert, $base) or die(mysql_error());

			}
			//for security reason, we force to remove all uploaded file

			@unlink($_FILES[$fileElementName][$i]);		
	}?>		                      
	
    <?php Header("Location: ../base.php?id=9&dir=".$_POST['dir']."");?>
    
<?php }
?>