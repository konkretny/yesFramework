<?php

namespace yesFramework\Core\Classess;

interface BaseInterface
{
        public static function load_view(string $template, string $content, array $var = []): void;
        public static function send_service_email(string $toemail, string $title, string $message_body): void;
        public static function send_mail(string $fromemail, string $toemail, string $title, string $message_body): void;
}



/**
 * The base class
 */
class Base implements BaseInterface
{

        /**
         * Function load view
         * @param string $template
         * @param string $content
         * @param mixed[] $var
         */
        public static function load_view(string $template, string $content, array $var = []): void
        {
                extract($var, EXTR_SKIP);
                $view = new View();
                ob_start();
                require_once(__DIR__ . '/../../App/Views/' . $content);
                $body_content = ob_get_clean();
                require_once(__DIR__ . '/../../App/Views/' . $template);
        }

        /**
         * Funkcja send service HTML e-mail
         * @param string $toemail
         * @param string $title
         * @param string $message_body
         */
        public static function send_service_email(string $toemail, string $title, string $message_body): void
        {
                $header = "Content-type: text/html; charset=UTF-8" . "\r\n" . "From: " . NAME . "<" . MAIN_EMAIL . ">";
                mail(trim($toemail), $title, $message_body, $header);
        }

        /**
         * Function send HTML e-mail
         * @param string $fromemail
         * @param string $toemail
         * @param string $title
         * @param string $message_body
         */
        public static function send_mail(string $fromemail, string $toemail, string $title, string $message_body): void
        {
                $header = "Content-type: text/html; charset=UTF-8" . "\r\n" . "From: " . NAME . "<" . $fromemail . ">";
                mail(trim($toemail), $title, $message_body, $header);
        }
}
