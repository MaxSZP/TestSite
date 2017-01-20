<?php session_start();
error_reporting(E_ALL | E_STRICT) ;
ini_set('display_errors', 'On');
define('INCLUDE_CHECK',true);

require_once("img_resize.php");
require_once('../connections/config.php'); 

$query_tech = "SELECT `email_indzakaz` FROM `tech` LIMIT 1";
$tech = mysql_query($query_tech, $base) or die(mysql_error());
$row_tech = mysql_fetch_assoc($tech);



$error = "";
$msg = "";
$fileElementName = 'fileToUpload';
$i = 0;
$pic1="";
$pic2="";
$pic3="";

$files_count = count($_FILES[$fileElementName]);


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
		$error = 'Нет файла для загрузки..';
	}else 
	{
		$new_tmp_name = $_FILES[$fileElementName]['tmp_name'][$i];
		$imageinfo = getimagesize($new_tmp_name);
		if($imageinfo["mime"] = "image/jpeg") {
			if ($imageinfo[0]<=$imageinfo[1]) {
				$height = 1024;
				$width = 768;
			} else {
				$height = 768;
				$width = 1024;
			}
			$filename = substr(md5(microtime() . rand(0, 9999)), 0, 20);
			$extension = 'jpg';
			$new_name = $filename.".".$extension;
			$dir = "../img/indzakaz/";
			if (file_exists($dir.$new_name)){
				$name1 =  str_replace(".jpg","", $new_name);
				$new_name1 = $name1."_1".".jpg";
			} else {
				$new_name1=$new_name;
			}
				// пример вызова
				img_resize($new_tmp_name, $dir.$new_name1, $width, $height,  90, 0x051320, 0);
				$tm = $i+1;
				if($i==0) {
					$pic1 =$new_name1; 
				} elseif($i==1) {
					$pic2 =$new_name1; 
				} else {
					$pic3 =$new_name1; 
				}
				
			//for security reason, we force to remove all uploaded file
			@unlink($_FILES[$fileElementName][$i]);		
	}		                      
	}

}

				$query_user = "SELECT * FROM `usver_k` WHERE `email`='".$_SESSION['email']."' and `pass`='".$_SESSION['pass']."' LIMIT 1";
				$user = mysql_query($query_user, $base) or die(mysql_error());
				$row_user = mysql_fetch_assoc($user);
				$totalRows_user = mysql_num_rows($user);
				if($totalRows_user>0) {
					$usver = $row_user['id'];
				}

$query_indzakaz = "INSERT INTO `indzakaz`  (`id`, `name`,`tel`,`city`,`event`,`weight`,`date`,`id_stuffing`,`desc`,`pic1`,`pic2`,`pic3`,`usver_id`) VALUES (NULL, '".trim(mysql_escape_string($_POST['fio']))."','".trim(mysql_escape_string($_POST['tel']))."','".trim(mysql_escape_string($_POST['city']))."','".trim(mysql_escape_string($_POST['event']))."','".trim(mysql_escape_string($_POST['weight']))."','".$_POST['date']."','".$_POST['stuffing']."','".trim(mysql_escape_string($_POST['desc']))."','".$pic1."','".$pic2."','".$pic3."','".$usver."')";
$indzakaz= mysql_query($query_indzakaz, $base) or die(mysql_error());
$_SESSION['ok']=1;


require_once('mailer/class.phpmailer.php');
//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

$mailfrom = $row_tech['email_indzakaz'];


$mail             = new PHPMailer();

$body             = "<p>Поступил индивидуальный заказ на сайте от</p> <b>ИМЯ:</b>".$_POST['fio']."<br/><b>ТЕЛЕФОН:</b>".$_POST['tel']."<br/>";
//$body             = preg_replace("[\]",'',$body);

$mail->IsSMTP(); 
$mail->Host       = "mail.ukraine.com.ua"; 
$mail->SMTPDebug  = 1;                     
$mail->SMTPAuth   = true;                 
$mail->Host       = "mail.ukraine.com.ua"; 
$mail->Port       = 25;                    
$mail->Username   = "website@stelsi.org.ua"; 
$mail->Password   = "NP4ex52s";        

$mail->SetFrom('website@stelsi.org.ua', 'website@stelsi.org.ua');

$mail->AddReplyTo('website@stelsi.org.ua','website@stelsi.org.ua');

$mail->Subject    = 'Индивидуальный заказ';


$mail->MsgHTML($body);

$address = $row_tech['email_indzakaz'];
$mail->AddAddress($address, "Стелси");

if(!$mail->Send()) {
Header("Location: http://www.stelsi.org.ua/indzakaz.html");
} else {
Header("Location: http://www.stelsi.org.ua/indzakaz.html");
}
?>  