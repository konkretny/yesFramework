<?php
namespace Core\Classess;

class Validator{
	
	public function check_email($email){
		if(check_email($email)==0){echo 'Error validate e-mial';exit;}
	}
	
		public function check_ip($email){
		if(check_ip($email)==0){echo 'Error validate ip';exit;}
	}
	
		public function check_integer($data,$param){
		if(check_integer($data,$param)==0){echo 'Error validate integer';exit;}
	}
	
}
?>