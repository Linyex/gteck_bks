<?php
class ValidateLibrary {
	public function firstname($firstname) {
		/*
			Разрешенные символы: А-Я а-я
			Длина: 2-16
		*/
		return preg_match("/^([А-ЯЁ]{1})([а-яё]{1,15})$/u", $firstname);
	}
	
	public function lastname($lastname) {
		/*
			Разрешенные символы: А-Я а-я
			Длина: 2-16
		*/
		return preg_match("/^([А-ЯЁ]{1})([а-яё]{1,15})$/u", $lastname);
	}

	public function email($email) {
		return preg_match("/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/", $email);
	}
    
	public function password($password) {
		/*
			Разрешенные символы: A-Z a-z 0-9
			Длина: 6-32
		*/
		return preg_match("/^[a-zA-Z0-9,\.!?_-]{6,32}$/", $password);
	}
	
	public function accesslevel($accesslevel) {
		if(0 > $accesslevel || $accesslevel > 3)
			return false;
		else
			return true;
	}
	
	public function userstatus($status) {
		if(0 > $status || $status > 1)
			return false;
		else
			return true;
	}
	
	public function category($category) {
		if(0 > $category || $category > 3)
			return false;
		else
			return true;
	}

    public function year($year) {
        return preg_match("/^\d+$/", $year);
    }

	public function ip($ip) {
		/*
			Разрешенные символы: 0-9 и .
			Длина: 1
		*/
		return preg_match("/^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$/", $ip);
	}
}
?>
