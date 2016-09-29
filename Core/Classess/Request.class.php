<?php
namespace Core\Classess;

/**
 * Request handler class
 */
class Request{
    
    /**
     * GET Request
     * @global mixed[] $_GET
     * @param mixed[] $value
     * @return mixed[]
     */
    public static function get($value=NULL){
        global $_GET;
        if(!empty(trim($value))){
            if(isset($_GET[$value])){
                $result = $_GET[$value];
            }
            else{
                $result = NULL;
            }
        }
        elseif(isset($_GET)){
            $result = $_GET;
        }
        else{
            $result = NULL;
        }
        return $result;
    }
    
    /**
     * POST Request
     * @global mixed[] $_POST
     * @param mixed[] $value
     * @return mixed[]
     */
    public static function post($value=NULL){
        global $_POST;
        if(!empty(trim($value))){
            if(isset($_POST[$value])){
                $result = $_POST[$value];
            }
            else{
                $result = NULL;
            }
        }
        elseif(isset($_POST)){
            $result = $_POST;
        }
        else{
            $result = NULL;
        }
        return $result;
    }
    
    /**
     * SESSION Request
     * @global mixed[] $_SESSION
     * @param mixed[] $value
     * @return mixed[]
     */
    public static function session($value=NULL){
        global $_SESSION;
        if(!empty(trim($value))){
            if(isset($_SESSION[$value])){
                $result = $_SESSION[$value];
            }
            else{
                $result = NULL;
            }
        }
        elseif(isset($_SESSION)){
            $result = $_SESSION;
        }
        else{
            $result = NULL;
        }
        return $result;
    }
    
    /**
     * SERVER Request
     * @global mixed[] $_SERVER
     * @param mixed[] $value
     * @return mixed[]
     */
    public static function server($value=NULL){
        global $_SERVER;
        if(!empty(trim($value))){
            if(isset($_SERVER[$value])){
                $result = $_SERVER[$value];
            }
            else{
                $result = NULL;
            }
        }
        elseif(isset($_SERVER)){
            $result = $_SERVER;
        }
        else{
            $result = NULL;
        }
        return $result;
    }
    
    /**
     * COOKIE Request
     * @global mixed[] $_COOKIE
     * @param mixed[] $value
     * @return mixed[]
     */
    public static function cookies($value=NULL){
        global $_COOKIE;
        if(!empty(trim($value))){
            if(isset($_COOKIE[$value])){
                $result = $_COOKIE[$value];
            }
            else{
                $result = NULL;
            }
        }
        elseif(isset($_COOKIE)){
            $result = $_COOKIE;
        }
        else{
            $result = NULL;
        }
        return $result;
    }
    
}
?>
