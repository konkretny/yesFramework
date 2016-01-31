<?php
namespace Apps\Models;

class Hello{
	
	public static function hello_user($your_name){
		$data = 'Hello <b>'.$your_name.'.</b> I hope you liked this framework. All the best!';
		return $data;
	}
	
}

?>