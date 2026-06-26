<?php

declare(strict_types=1);

namespace yesFramework\App\Controllers;

use yesFramework\Core\Classes\Base;
use yesFramework\App\Models\Hello;
use yesFramework\Core\Classes\Db;

class WelcomeController
{
    private ?Db $db;

    public function __construct(?Db $db = null)
    {
        $this->db = $db;
    }

    public function index(): void
    {
        $data_from_class = Hello::hello_user('Stranger');

        $data_to_body = [
            "header" => "example header",
            "data_from_class" => $data_from_class,
            "footer" => "example footer"
        ];

        //load view
        Base::load_view('template.php', 'content.php', $data_to_body);
    }
}
