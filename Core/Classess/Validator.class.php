<?php
namespace Core\Classess;
class Validator{
	
	public static function check_email($email){
            if(check_email($email)==0){echo 'Error validate e-mial';exit;}
	}
	
        public static function check_ip($ip){
            if(check_ip($ip)==0){echo 'Error validate ip';exit;}
	}
	
        public static function check_integer($data,$param){
            if(check_integer($data,$param)==0){echo 'Error validate integer';exit;}
	}
        
        public static function rule_no_empty($array=array(),$param=array()){
            if(rule_no_empty($array,$param)==0){echo 'Error validate empty value';exit;}
        }
}
?>