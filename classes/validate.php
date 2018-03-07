<?php
class Validate{
	private $_passed = false,
			$_errors = array(),
			$_db = null;

	public function __construct(){
		$this->_db = DB::getInstance();
	}
	public function check($method,$items = array()){
		foreach($items as $item => $rules){
			foreach($rules as $rule => $rule_value){
				$value = $method[$item];
				if($rule == 'require' && empty($value)){
					$this->setErrors("{$item} is required");
				} elseif(!empty($value)){
					switch($rule){
						case 'min':
							if(strlen($value) < $rule_value){
								$this->setErrors("{$item} length can't be less than {$rule_value} chatcaters");
							}
						break;
						case 'max':
								if(strlen($value) > $rule_value){
								$this->setErrors("{$item} length can't be more than {$rule_value} chatcaters");
							}
						break;
						case 'matches':
							if($value != $method[$rule_value]){
								$this->setErrors("{$item} must matches {$rule_value}");
							}
						break;
						case 'unique':
							$check = $this->_db->select('username','users','username','=',$value);
							if($check->count()){
								$this->setErrors("this {$item} is already exist");
							}
						break;
					}
				}
			}
		}
		if(empty($this->_errors)){
			$this->_passed = true;
		}
		return $this;
	}
	private function setErrors($error){
		$this->_errors[] = $error;
	}	
	public function getErrors(){
		return $this->_errors;
	}	
	public function getPassed(){
		return $this->_passed;
	}
}