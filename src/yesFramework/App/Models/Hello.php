<?php
namespace yesFramework\App\Models;

class Hello{
	
	public static function hello_user(string $your_name):string{
		$data = 'Hello <b>'.$your_name.'.</b> I hope you liked this framework. All the best!';
		return $data;
	}
	
}

?>