<?php
use Apps\Models\Hello;


$data = Hello::hello_user('Stranger');

$data_to_body = array (
	"header" => "example header",
	"body" => $data,
	"footer" => "example footer"
	);
	
$base->load_view('body.php',$data_to_body);

?>