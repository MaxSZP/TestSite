<?php
session_name("SiteMSFID");
session_start();

/* убрать или изменить после отладки */
ini_set('display_errors',1);
error_reporting(E_ALL);
//ini_set('display_errors',0);
//error_reporting( E_ERROR );
/* убрать или изменить после отладки */

// Полный абсолютный путь к корню сайта
define("ROOT_DIR", dirname(__FILE__).'/');

//Если сайт в поддиректории - указываем его здесь.
define("SUBDIR", '');

// Полный относительный путь к корню сайта
define("ROOT", '/');


// Подключаем автозагрузчик классов
require_once "init/autoload.php";
spl_autoload_register('classLoader');

// Полный относительный путь к файлам админ-панели сайта
define("ROOT_ADM", ROOT.Config::$adminPath.'/');

// Инициализация синглтона для работы с базой
$db = DbMysqli::getInstance();
// Заполнили данными сессию
$db->setInfoSession();
// Инициализация фронт-контроллера
$front = FrontController::getInstance();

$front->route();
// Вывод результата
echo $front->getBody();

?>