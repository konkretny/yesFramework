<?php

use yesFramework\Core\Classess\Base; //Basic model supports load_view function
use yesFramework\App\Models\Hello; //example model
use yesFramework\Core\Classess\Db;

$data_from_class = Hello::hello_user('Stranger'); //check Models/Hello.php

$data_to_body = array(
	"header" => "example header",
	"data_from_class" => $data_from_class,
	"footer" => "example footer"
);

//load view
Base::load_view('template.php', 'content.php', $data_to_body);
