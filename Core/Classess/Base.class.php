<?php
namespace Core\Classess;

class Base{
    
	static public function load_view($file,$var = array()){
		extract($var);
		$view = new View();
		ob_start();
		include ('../Apps/Views/'.$file);
		$render = ob_get_clean();
		echo $render;
	}
	
	static public function send_service_email($toemail,$title,$message_body){
		$header = "Content-type: text/html; charset=UTF-8"."\r\n"."From: ".NAME."<".MAIN_EMAIL.">";
		mail(trim($toemail), $title, $message_body, $header);
	}
        
	static public function send_mail($fromemail,$toemail,$title,$message_body){
		$header = "Content-type: text/html; charset=UTF-8"."\r\n"."From: ".NAME."<".$fromemail.">";
		mail(trim($toemail), $title, $message_body, $header);
	}

	
}
?>