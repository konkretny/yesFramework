<?php
namespace Core\Classess;

/**
 * Validation class
 */
class Validator{
	
        /**
         * Validates e-mail
         * @param string $email
         */
	public static function check_email($email){
            if(check_email($email)==0){echo 'Error validate e-mial';exit;}
	}
	
        /**
         * Validates IP
         * @param string $ip
         */
        public static function check_ip($ip){
            if(check_ip($ip)==0){echo 'Error validate ip';exit;}
	}
	
        /**
         * Validates int
         * @param mixed $data
         * @param int $param
         */
        public static function check_integer($data,$param){
            if(check_integer($data,$param)==0){echo 'Error validate integer';exit;}
	}
        
        /**
         * Check empty var
         * @param mixed[] $array
         * @param mixed[] $param
         */
        public static function rule_no_empty($array=array(),$param=array()){
            if(rule_no_empty($array,$param)==0){echo 'Error validate empty value';exit;}
        }
        
        /**
         * Check integer in array
         * @param int $param
         * @param mixed[] $array
         */
        public static function check_integer_in_array($param, $array = array()){
            if(check_integer_in_array($param, $array)==0){echo 'Error validate integer in array';exit;}
        }
}
?>