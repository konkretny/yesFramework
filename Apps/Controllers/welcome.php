<?php
use Core\Classess\Base; //Basic model supports load_view function
use Apps\Models\Hello; //user model

$data ='<h2>yesFramework has been installed correctly (probably :))</h2>';
$data .= Hello::hello_user('Stranger'); //check Models/Hello.class.php
$data .= $lang['hi']; //check Core/Language/en_EN.php





$data_to_body = array (
	"header" => "example header",
	"body" => $data,
	"footer" => "example footer"
	);
	
Base::load_view('body.php',$data_to_body);

?>