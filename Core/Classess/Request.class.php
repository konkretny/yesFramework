<?php
namespace Core\Classess;

class Request{
    
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
        else{
            $result = $_GET;
        }
        return $result;
    }
    
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
        else{
            $result = $_POST;
        }
        return $result;
    }
    
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
        else{
            $result = $_SESSION;
        }
        return $result;
    }
    
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
        else{
            $result = $_SERVER;
        }
        return $result;
    }
}
?>
