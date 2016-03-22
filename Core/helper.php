<?php

function check_email($email){
	if (filter_var($email, FILTER_VALIDATE_EMAIL)){$result = 1;}else{$result = 0;}
	return $result;
	}
	
function check_ip($ip){
	if (filter_var($ip, FILTER_VALIDATE_IP)){$result = 1;}else{$result = 0;}
	return $result;
}
	
function check_integer($data,$param){
	if($param==0){ //only +
		if (preg_match ('/^[0-9]+$/', $data)){$result = 1;}else{$result = 0;}
	}
	elseif($param==1){ //with sub
		if (preg_match ('/^-?[0-9]+$/', $data)){$result = 1;}else{$result = 0;}
	}
	return $result;
}	

function secure_input($data){
	$data = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $data);
	$data = strip_tags($data);
	$data = htmlspecialchars($data);
	return $data;
}

function redirect($data){
	header('Location: '.$data);
	exit;
}

function encrypt($key,$value){
    $mopen = mcrypt_module_open('rijndael-256', '', 'ecb', '');
    $iv_size = mcrypt_enc_get_iv_size($mopen);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);  
    mcrypt_generic_init($mopen, $key, $iv);
    
    return base64_encode(rtrim(mcrypt_generic($mopen, $value)));
}

function decrypt($key,$value){
    $mopen = mcrypt_module_open('rijndael-256', '', 'ecb', '');
    $iv_size = mcrypt_enc_get_iv_size($mopen);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);  
    mcrypt_generic_init($mopen, $key, $iv);
    
    return rtrim(mdecrypt_generic($mopen, base64_decode($value)));
}

function curl_get($url)
{
    $c = curl_init();  
    curl_setopt($c,CURLOPT_URL,$url);
    curl_setopt($c,CURLOPT_RETURNTRANSFER,true);
    $output=curl_exec($c);
    curl_close($c);
   
    return $output;
}
 
function curl_post($url,$params=array())
{
  $postData = '';
   foreach($params as $k => $v) 
   { 
      $postData .= $k . '='.$v.'&'; 
   }
   rtrim($postData, '&');
    $c = curl_init();  
    curl_setopt($c,CURLOPT_URL,$url);
    curl_setopt($c,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($c,CURLOPT_HEADER, false); 
    curl_setopt($c, CURLOPT_POST, count($postData));
    curl_setopt($c, CURLOPT_POSTFIELDS, $postData);    
    $output=curl_exec($c);
    curl_close($c);
    
	return $output;
}

function getIP(){
    return $_SERVER['REMOTE_ADDR'];
}

function getServIP(){
    return $_SERVER['HTTP_REFERER'];
}

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

function getCSRF(){
    return $_SESSION['csrf'];
}


?>