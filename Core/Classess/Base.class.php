<?php
namespace Core\Classess;

/**
 * The base class
 */
class Base{
    
        /**
         * Function load view
         * @param string $file
         * @param mixed[] $var
         */
	static public function load_view($file,$var = array()){
		extract($var);
		$view = new View();
		ob_start();
		include ('../Apps/Views/'.$file);
		$render = ob_get_clean();
		echo $render;
	}
	
        /**
         * Funkcja send service HTML e-mail
         * @param string $toemail
         * @param string $title
         * @param string $message_body
         */
	static public function send_service_email($toemail,$title,$message_body){
		$header = "Content-type: text/html; charset=UTF-8"."\r\n"."From: ".NAME."<".MAIN_EMAIL.">";
		mail(trim($toemail), $title, $message_body, $header);
	}
        
        /**
         * Function send HTML e-mail
         * @param string $fromemail
         * @param string $toemail
         * @param string $title
         * @param string $message_body
         */
	static public function send_mail($fromemail,$toemail,$title,$message_body){
		$header = "Content-type: text/html; charset=UTF-8"."\r\n"."From: ".NAME."<".$fromemail.">";
		mail(trim($toemail), $title, $message_body, $header);
	}

	
}
?>