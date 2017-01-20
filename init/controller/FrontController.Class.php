<?php
class FrontController {
	protected $_controller, $_action, $_params, $_body;
	static $_instance;

	public static function getInstance() {
		if(!(self::$_instance instanceof self)) 
			self::$_instance = new self();
		return self::$_instance;
	}
	private function __construct(){
		$request = trim($_SERVER['REQUEST_URI'], " \t\n\r\0\x0B/" );
		$request = preg_replace('/\.html$/', '', $request);
		$request = preg_replace('/\.php$/', '', $request);
		
		$request = substr($request, strlen(SUBDIR) );

		$splits = explode('/', $request);
		
//		echo $request, "<br>";
//		var_dump($splits);
//		exit;
		
		// если uri пустой или один параметр - предполагаем отображение страницы
		if (empty($splits[0])){
			$splits[0] = Config::$defController;
			$splits[1] = Config::$defAction;
			$splits[2] = Config::$defParamName;
			$splits[3] = Config::$defPage;
		}elseif (empty($splits[1])){
			$splits[1] = Config::$defAction;
			$splits[2] = Config::$defParamName;
			$splits[3] = strtolower($splits[0]);
			$splits[0] = Config::$defController;

		}

		//Controller
		$this->_controller = ucfirst($splits[0]).'Controller';
		//Action
		$this->_action = $splits[1].'Action';
		//Если есть параметры - значения
		if(!empty($splits[2])){
			$keys = $values = array();
				for($i=2, $cnt = count($splits); $i<$cnt; $i++){
					if($i % 2 == 0){
						//Чётное = ключ (параметр)
						$keys[] = $splits[$i];
					}else{
						//Значение параметра;
						$values[] = $splits[$i];
					}
				}
			if(count($keys) != count($values)) unset($keys[count($keys)-1]); //если есть ключ без параметра - убираем
			$this->_params = array_combine($keys, $values);
		}
		// Проверяем наличие controller и action
		
//		echo "Splits - ";
//		print_r($splits);
//		echo "<br>";
		
		$flag = 1;
		if(class_exists($this->getController())) {
			$rc = new ReflectionClass($this->getController());
			if($rc->implementsInterface(Config::$IControllerName)) {
				if($rc->hasMethod($this->getAction())) {
					$flag = 0;
				}
			}
		}
		if($flag){
			$this->_controller = ucfirst(Config::$defController).'Controller';
			$this->_action = Config::$defAction.'Action';
			unset($this->_params);
			$this->_params[Config::$defParamName] = Config::$page404;
		}
	}
	
	private function __clone(){
			return self::$_instance;
	}
	
	public function route() {
		if(class_exists($this->getController())) {
			$rc = new ReflectionClass($this->getController());
			if($rc->implementsInterface(Config::$IControllerName)) {
				if($rc->hasMethod($this->getAction())) {
					$controller = $rc->newInstance();
					$method = $rc->getMethod($this->getAction());
					$method->invoke($controller);
				} else {
					die('<h2>Ошибка в работе сайта</h2>');
				}
			} else {
				die('<h2>Ошибка в работе сайта</h2>');
			}
		} else {
			die('<h2>Ошибка в работе сайта</h2>');
		}
	}
	public function getParams() {
		return $this->_params;
	}
	public function getController() {
		return $this->_controller;
	}
	public function getAction() {
		return $this->_action;
	}
	public function getBody() {
		return $this->_body;
	}
	public function setBody($body) {
		$this->_body = $body;
	}
}	