<?php
require_once('../Connections/connect.php');
include("img_resize.php");
$error = "";
$msg = "";
$fileElementName = 'fileToUploadfoto';
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

				if (strstr($_FILES[$fileElementName]['name'][$i], ".JPG")) {
				$new_name = str_replace(".JPG",".jpg", $_FILES[$fileElementName]['name'][$i]);
				$new_tmp_name = str_replace(".JPG",".jpg", $_FILES[$fileElementName]['tmp_name'][$i]);
				} else {
				$new_name = $_FILES[$fileElementName]['name'][$i];
				$new_tmp_name = $_FILES[$fileElementName]['tmp_name'][$i];
				}
				$size1 = getimagesize($new_tmp_name);
			if ($size1[0]<=$size1[1]) {
	//		if ($_POST['orient']==0) {
			$thumb_height = 200;
			$thumb_width = 130;
			$height = 1024;
			$width = 683;
			} else {
			$thumb_height = 100;
			$thumb_width = 140;	
			$height = 683;
			$width = 1024;
			}
	
			if (is_dir("../../files/".$_POST['id']."/thumb/")) {
				
			if (file_exists("../../files/".$_POST['id']."/" .$new_name)){
				$name1 =  str_replace(".jpg","", $new_name);

				$new_name1 = $name1."_1".".jpg";
				if (file_exists("../../files/".$_POST['id']."/" .$new_name1)){
				$new_name1 = $name1.filemtime("../../files/".$_POST['id']."/".$new_name1).".jpg";
				}
		//		move_uploaded_file($new_tmp_name, "../../".$_POST['dir']."/gallery/".$_POST['desing']."/" . $new_name1);
      			$msg .= $new_name1;
				img_resize($new_tmp_name, "../../files/".$_POST['id']."/" . $new_name1, $width, $height,  90, 0x002c59, 0);
				img_resize($new_tmp_name, "../../files/".$_POST['id']."/thumb/" . $new_name1, $thumb_width, $thumb_height,  90, 0x002c59, 0);
				$query_update = "UPDATE `catalog`  SET  `thumb`='$new_name1', `del`='0' WHERE `id`='".$_POST['id']."'";
				$update= mysql_query($query_update, $base) or die(mysql_error()); 

				
			} else {
	
				$msg .=  $new_name;
				img_resize($new_tmp_name, "../../files/".$_POST['id']."/" . $new_name, $width, $height,  90, 0x002c59, 0);
				img_resize($new_tmp_name, "../../files/".$_POST['id']."/thumb/" .$new_name, $thumb_width, $thumb_height,  90, 0x002c59, 0);
				$query_update = "UPDATE `catalog`  SET  `thumb`='$new_name', `del`='0' WHERE `id`='".$_POST['id']."'";
				$update= mysql_query($query_update, $base) or die(mysql_error()); 

			}
			} else {

			mkdir("../../files/".$_POST['id']."/", 0777);
			mkdir("../../files/".$_POST['id']."/thumb/", 0777);
			
				$msg .=  $new_name;
				img_resize($new_tmp_name, "../../files/".$_POST['id']."/" . $new_name, $width, $height,  90, 0x002c59, 0);
				img_resize($new_tmp_name, "../../files/".$_POST['id']."/thumb/" .$new_name, $thumb_width, $thumb_height,  90, 0x002c59, 0);
			$query_update = "UPDATE `catalog`  SET  `thumb`='$new_name', `del`='0' WHERE `id`='".$_POST['id']."'";
			$update= mysql_query($query_update, $base) or die(mysql_error()); 

			}
			//for security reason, we force to remove all uploaded file

			@unlink($_FILES[$fileElementName][$i]);		
	}?>		                      
	
    <?php Header("Location: ../base.php?id=9&dir=".$_POST['dir']."");?>
    
<?php }
?>