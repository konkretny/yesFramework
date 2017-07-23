<?php

/**
 * Check if a variable is an e-mail. Returns 1 (true) or 0 (false).
 * @param string $email
 * @return int
 */
function check_email($email){
	if (filter_var($email, FILTER_VALIDATE_EMAIL)){$result = 1;}else{$result = 0;}
	return $result;
	}
	
/**
 * Checks whether a variable is the IP number. Returns 1 or 0.
 * @param string $ip
 * @return int
 */
function check_ip($ip){
	if (filter_var($ip, FILTER_VALIDATE_IP)){$result = 1;}else{$result = 0;}
	return $result;
}
	
/**
 * Check if a variable is an integer. The variable $param allows you to set whether checked integer can be negative or not. 
 * $param = 0 - non-negative variable, $param = 1 variable may be negative. Returns 1 or 0.
 * @param mixed $data
 * @param int $param
 * @return int
 */
function check_integer($data,$param){
	if($param==0){ //only +
		if (preg_match ('/^[0-9]+$/', $data)){$result = 1;}else{$result = 0;}
	}
	elseif($param==1){ //with sub
		if (preg_match ('/^-?[0-9]+$/', $data)){$result = 1;}else{$result = 0;}
	}
	return $result;
}	

/**
 * Cleans array with special characters. Returns purified variable.
 * @param mixed $data
 * @return string
 */
function secure_input($data){
	$data = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $data);
	$data = strip_tags($data);
	$data = htmlspecialchars($data);
	return $data;
}

/**
 * Redirects the user to the specified variable path.
 * @param string $url
 */
function redirect($url){
	header('Location: '.$url);
	exit;
}

/**
 * Encryption function - Rijandel-256.
 * @param mixed $key
 * @param mixed $value
 * @return string
 */
function encrypt($key,$value){
    $mopen = mcrypt_module_open('rijndael-256', '', 'ecb', '');
    $iv_size = mcrypt_enc_get_iv_size($mopen);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);  
    mcrypt_generic_init($mopen, $key, $iv);
    
    return base64_encode(trim(mcrypt_generic($mopen, base64_encode($value))));
}

/**
 * Decryption function - Rijandel-256.
 * @param mixed $key
 * @param mixed $value
 * @return string
 */
function decrypt($key,$value){
    $mopen = mcrypt_module_open('rijndael-256', '', 'ecb', '');
    $iv_size = mcrypt_enc_get_iv_size($mopen);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);  
    mcrypt_generic_init($mopen, $key, $iv);
    
    return @trim(base64_decode(mdecrypt_generic($mopen, base64_decode($value))));
}

/**
 * Makes a call CURL as GET, it returns the response was received from the specified URL. 
 * As the second parameter is the time TIMEOUT in seconds. The third parameter - check the certificate.
 * @param string $url
 * @param int $time
 * @param int $cert_verify
 * @return string
 */
function curl_get($url,$time,$cert_verify)
{
    $c = curl_init();  
    curl_setopt($c,CURLOPT_URL,$url);
    
    if(isset($time) && is_numeric($time)){
        curl_setopt($c, CURLOPT_TIMEOUT, $time);
    }
    if(isset($cert_verify) && is_numeric($time)){
        if($cert_verify==1){
            curl_setopt($c, CURLOPT_SSL_VERIFYPEER, true);
        }else{
            curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);            
        }
    }
    
    curl_setopt($c,CURLOPT_RETURNTRANSFER,true);
    $output=curl_exec($c);
    if($output === false){$output='curl error';}
    curl_close($c);
  
    return $output;
}

/**
 * Makes a call CURL as POST. The variable $param should include variables to send. Variable $time is TIMEOUT in seconds.
 * The third parameter - check the certificate. Fourth - POST array.
 * @param string $url
 * @param int $time
 * @param int $cert_verify
 * @param mixed[] $params
 * @return string
 */
function curl_post($url,$time,$cert_verify,$params=array())
{
  $postData = '';
   foreach($params as $k => $v) 
   { 
      $postData .= $k . '='.$v.'&'; 
   }
   rtrim($postData, '&');
   
    $c = curl_init();  
    curl_setopt($c,CURLOPT_URL,$url);
    
    if(isset($time) && is_numeric($time)){
        curl_setopt($c, CURLOPT_TIMEOUT, $time);
    }
    if(isset($cert_verify) && is_numeric($time)){
        if($cert_verify==1){
            curl_setopt($c, CURLOPT_SSL_VERIFYPEER, true);
        }else{
            curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);            
        }
    }
    
    curl_setopt($c,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($c,CURLOPT_HEADER, false); 
    curl_setopt($c, CURLOPT_POST, count($postData));
    curl_setopt($c, CURLOPT_POSTFIELDS, $postData);    
    $output=curl_exec($c);
    if($output === false){$output='curl error';}
    curl_close($c);
    
	return $output;
}

/**
 * Get IP address
 * @return string
 */
function getIP(){
    return $_SERVER['REMOTE_ADDR'];
}

/**
 * Get server IP address.
 * @return string
 */
function getServIP(){
    return $_SERVER['HTTP_REFERER'];
}

/**
 * Generates a list of hours as <option>.
 * @return string
 */
function genHoursOptionsList(){
    $i=0;
    $result='';
    while($i<24){
        if($i<10){$i='0'.$i;}
        $result .= '<option value="'.$i.'">'.$i.'</option>';
        $i++;
    }
    return $result;
}

/**
 * Generates a list minute as <option>.
 * @return string
 */
function genMinutesOptionsList(){
    $i=0;
    $result='';
    while($i<60){
        if($i<10){$i='0'.$i;}
        $result .= '<option value="'.$i.'">'.$i.'</option>';
        $i++;
    }
    return $result;
}

/**
 * Gets the security code before the attack CSRF.
 * @return string
 */
function getCSRF(){
    return $_SESSION['csrf'];
}

/**
 * Function checks whether the array $array does not include the blank keys, which are indicated in $param.
 * Ff it is ok = returns 1, and if it finds an empty element, returns 0.
 * @param mixed[] $array
 * @param mixed[] $param
 * @return int
 */
function rule_no_empty($array=array(),$param=array()){
    
    //param='ALL'
    if($param[0]=='ALL'){
        $param=array();
        foreach($array as $key=>$value){
            $param[]=$key;
        }
    }
    
    //check key exists
    $result=1;
    foreach($param as $value){
        if(!array_key_exists($value, $array)){
            $result=0;
        }
    }
    //check value is empty
    foreach($array as $key=>$value){
            if(empty(trim($value)) && in_array($key, $param)){
                $result=0;
                break;
            }
    }
    
    return $result;
}


/**
 * Checks whether the variable is really empty. If it returns 1, if anything contains 0, then returns 0.
 * @param string $data
 * @return int
 */
function true_empty($data){
    if($data===0){
        return 0;
    }elseif(empty(trim($data))){
        return 1;
    }
    else{
        return 0;
    }
}

?>