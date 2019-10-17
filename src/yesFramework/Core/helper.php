<?php

/**
 * Check if a variable is an e-mail. Returns 1 (true) or 0 (false).
 * @param string $email
 * @return int
 */
function check_email(string $email): bool
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

/**
 * Checks whether a variable is the IP number. Returns 1 or 0.
 * @param string $ip
 * @return int
 */
function check_ip(string $ip): bool
{
    if (filter_var($ip, FILTER_VALIDATE_IP)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}


/**
 * Check if a variable is an integer. The variable $param allows you to set whether checked integer can be negative or not. 
 * $param = 0 - non-negative variable, $param = 1 variable may be negative. Returns 1 or 0.
 * @param mixed $data
 * @param int $param
 * @return int
 */
function check_integer($data, bool $param = false): bool
{
    if ($param === false) { //only +
        if (preg_match('/^[0-9]+$/', $data)) {
            $result = true;
        } else {
            $result = false;
        }
    } elseif ($param === true) { //with sub
        if (preg_match('/^-?[0-9]+$/', $data)) {
            $result = true;
        } else {
            $result = false;
        }
    }
    return $result;
}

/**
 * Checks whether the array elements are integers. If so, returns true.
 * The function accepts param as 0 for positive numbers and 1 for numbers also negative
 * @param mixed $data
 * @param int $param
 * @return int
 */
function check_integer_in_array(bool $param = false, array $array = []): bool
{

    $true_result = 0;
    $false_result = 0;

    foreach ($array as $value) {
        if (check_integer($value, $param)) {
            $true_result++;
        } else {
            $false_result++;
        }
    }

    if ($false_result === 0) {
        $result = true;
    } else {
        $result = false;
    }

    return $result;
}

/**
 * Cleans array with special characters. Returns purified variable.
 * @param mixed $data
 * @return string
 */
function secure_input(string $data): string
{
    $data = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $data);
    $data = strip_tags($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Redirects the user to the specified variable path.
 * @param string $url
 */
function redirect(string $url, $redirect_301 = false): void
{
    if ($redirect_301) {
        header("HTTP/1.1 301 Moved Permanently");
    }
    header('Location: ' . $url);
    exit;
}


/**
 * Makes a call CURL as GET, it returns the response was received from the specified URL. 
 * As the second parameter is the time TIMEOUT in seconds. The third parameter - check the certificate.
 * @param string $url
 * @param int $time
 * @param int $cert_verify
 * @return string
 */
function curl_get(string $url, int $time, bool $cert_verify = true): string
{
    $c = curl_init();
    curl_setopt($c, CURLOPT_URL, $url);

    if (isset($time) && is_numeric($time)) {
        curl_setopt($c, CURLOPT_TIMEOUT, $time);
    }
    if (isset($cert_verify) && is_numeric($time)) {
        if ($cert_verify === true) {
            curl_setopt($c, CURLOPT_SSL_VERIFYPEER, true);
        } else {
            curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        }
    }

    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($c);

    //catch http code other than 200
    if (curl_getinfo($c, CURLINFO_HTTP_CODE) !== 200) {
        $output = curl_getinfo($c, CURLINFO_HTTP_CODE);
    }

    if ($output === false) {
        $output = 'curl error';
    }
    curl_close($c);

    return $output;
}

/**
 * Makes a call CURL as GET with custom HEADER, it returns the response was received from the specified URL. 
 * As the second parameter is the time TIMEOUT in seconds. The third parameter - check the certificate. The fourth parameter is the headers
 * @param string $url
 * @param int $time
 * @param int $cert_verify
 * @return string
 */
function curl_get_header(string $url, int $time, bool $cert_verify = true, array $headers): string
{

    $c = curl_init();
    curl_setopt($c, CURLOPT_URL, $url);

    if (isset($time) && is_numeric($time)) {
        curl_setopt($c, CURLOPT_TIMEOUT, $time);
    }
    if (isset($cert_verify) && is_numeric($time)) {
        if ($cert_verify === true) {
            curl_setopt($c, CURLOPT_SSL_VERIFYPEER, true);
        } else {
            curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        }
    }
    curl_setopt($c, CURLOPT_ENCODING, "");
    curl_setopt($c, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($c);

    //catch http code other than 200
    if (curl_getinfo($c, CURLINFO_HTTP_CODE) !== 200) {
        $output = curl_getinfo($c, CURLINFO_HTTP_CODE);
    }

    if ($output === false) {
        $output = 'curl error';
    }
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
function curl_post(string $url, int $time, bool $cert_verify = true, array $params = [])
{

    $c = curl_init();
    curl_setopt($c, CURLOPT_URL, $url);

    if (isset($time) && is_numeric($time)) {
        curl_setopt($c, CURLOPT_TIMEOUT, $time);
    }
    if (isset($cert_verify) && is_numeric($time)) {
        if ($cert_verify === true) {
            curl_setopt($c, CURLOPT_SSL_VERIFYPEER, true);
        } else {
            curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        }
    }

    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($c, CURLOPT_HEADER, false);
    curl_setopt($c, CURLOPT_POST, count($params));
    curl_setopt($c, CURLOPT_POSTFIELDS, http_build_query($params));
    $output = curl_exec($c);

    //catch http code other than 200
    if (curl_getinfo($c, CURLINFO_HTTP_CODE) !== 200) {
        $output = curl_getinfo($c, CURLINFO_HTTP_CODE);
    }

    if ($output === false) {
        $output = 'curl error';
    }
    curl_close($c);

    return $output;
}


/**
 * Makes a call CURL as POST with custom HEADER. The variable $param should include variables to send. Variable $time is TIMEOUT in seconds.
 * The third parameter - check the certificate. Fourth - POST array. Last - headers array.
 * @param string $url
 * @param int $time
 * @param int $cert_verify
 * @param mixed[] $params
 * @return string
 */
function curl_post_header(string $url, int $time, bool $cert_verify = true, array $params = [], array $headers): string
{

    $c = curl_init();
    curl_setopt($c, CURLOPT_URL, $url);

    if (isset($time) && is_numeric($time)) {
        curl_setopt($c, CURLOPT_TIMEOUT, $time);
    }
    if (isset($cert_verify) && is_numeric($time)) {
        if ($cert_verify === true) {
            curl_setopt($c, CURLOPT_SSL_VERIFYPEER, true);
        } else {
            curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        }
    }

    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($c, CURLOPT_HEADER, false);
    curl_setopt($c, CURLOPT_POST, count($params));
    curl_setopt($c, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($c, CURLOPT_HTTPHEADER, $headers);
    $output = curl_exec($c);

    //catch http code other than 200
    if (curl_getinfo($c, CURLINFO_HTTP_CODE) !== 200) {
        $output = curl_getinfo($c, CURLINFO_HTTP_CODE);
    }

    if ($output === false) {
        $output = 'curl error';
    }
    curl_close($c);

    return $output;
}

/**
 * Makes a call CURL as POST in JSON format. The variable $param should include variables to send. Variable $time is TIMEOUT in seconds.
 * The third parameter - check the certificate. Fourth - JSON string.
 * @param string $url
 * @param int $time
 * @param int $cert_verify
 * @param mixed[] $params
 * @return string
 */
function curl_json_post(string $url, int $time, bool $cert_verify = true, string $params): string
{

    $c = curl_init();
    curl_setopt($c, CURLOPT_URL, $url);

    if (isset($time) && is_numeric($time)) {
        curl_setopt($c, CURLOPT_TIMEOUT, $time);
    }
    if (isset($cert_verify) && is_numeric($time)) {
        if ($cert_verify == 1) {
            curl_setopt($c, CURLOPT_SSL_VERIFYPEER, true);
        } else {
            curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        }
    }

    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($c, CURLOPT_HEADER, false);
    curl_setopt($c, CURLOPT_HTTPHEADER, [
        'Content-Type' => 'application/json'
    ]);
    curl_setopt($c, CURLOPT_POST, 1);
    curl_setopt($c, CURLOPT_POSTFIELDS, 'json=' . $params);
    $output = curl_exec($c);

    //catch http code other than 200
    if (curl_getinfo($c, CURLINFO_HTTP_CODE) !== 200) {
        $output = curl_getinfo($c, CURLINFO_HTTP_CODE);
    }

    if ($output === false) {
        $output = 'curl error';
    }
    curl_close($c);

    return $output;
}


function yesCurl(string $url, string $method = "GET", int $timeout = 10, bool $cert_verify = true, array $params = [], array $headers = []): array
{
    $c = curl_init();
    curl_setopt($c, CURLOPT_URL, $url);
    curl_setopt($c, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($c, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, $cert_verify);

    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($c, CURLOPT_HEADER, false);
    curl_setopt($c, CURLOPT_POST, count($params));
    curl_setopt($c, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($c, CURLOPT_HTTPHEADER, $headers);
    $output = curl_exec($c);
    $http_code = curl_getinfo($c, CURLINFO_HTTP_CODE);
    curl_close($c);
    return [$output, ['httpCode' => $http_code]];
}


/**
 * Get IP address
 * @return string
 */
function getIP(): string
{
    return $_SERVER['REMOTE_ADDR'];
}

/**
 * Get server IP address.
 * @return string
 */
function getServIP(): string
{
    return $_SERVER['HTTP_REFERER'];
}

/**
 * Generates a list of hours as <option>.
 * @return string
 */
function genHoursOptionsList(): string
{
    $i = 0;
    $result = '';
    while ($i < 24) {
        if ($i < 10) {
            $i = '0' . $i;
        }
        $result .= '<option value="' . $i . '">' . $i . '</option>';
        $i++;
    }
    return $result;
}

/**
 * Generates a list minute as <option>.
 * @return string
 */
function genMinutesOptionsList(): string
{
    $i = 0;
    $result = '';
    while ($i < 60) {
        if ($i < 10) {
            $i = '0' . $i;
        }
        $result .= '<option value="' . $i . '">' . $i . '</option>';
        $i++;
    }
    return $result;
}

/**
 * Gets the security code before the attack CSRF.
 * @return string
 */
function getCSRF(): string
{
    return $_SESSION['csrf'];
}

/**
 * Function checks whether the array $array does not include the blank keys, which are indicated in $param.
 * Ff it is ok = returns 1, and if it finds an empty element, returns 0.
 * @param mixed[] $array
 * @param mixed[] $param
 * @return int
 */
function rule_no_empty(array $array = [], array $param = []): bool
{

    //param='ALL'
    if ($param[0] === 'ALL') {
        $param = array();
        foreach ($array as $key => $value) {
            $param[] = $key;
        }
    }

    //check key exists
    $result = true;
    foreach ($param as $value) {
        if (!array_key_exists($value, $array)) {
            $result = false;
        }
    }
    //check value is empty
    foreach ($array as $key => $value) {
        if (empty(trim($value)) && in_array($key, $param)) {
            $result = false;
            break;
        }
    }

    return $result;
}


/**
 * Link friendly base64 encode
 * @param type $string
 * @return string
 */
function url_base64_encode(string $string): string
{
    return str_replace(array("+", "/", "-", "="), array("__P__", "__S__", "__M__", "__E__"), base64_encode($string));
}

/**
 * Link friendly base64 decode
 * @param string $string
 * @return string
 */
function url_base64_decode(string $string): string
{
    return base64_decode(str_replace(array("__P__", "__S__", "__M__", "__E__"), array("+", "/", "-", "="), $string));
}


/**
 * Checks whether the variable is really empty. If it returns 1, if anything contains 0, then returns 0.
 * @param string $data
 * @return int
 */
function true_empty($data): bool
{
    if ($data === 0 || $data === '0') {
        return false;
    } elseif (empty(trim($data))) {
        return true;
    } else {
        return false;
    }
}

/**
 * Return new converted array
 */
function secure_array(array $array): array
{
    $newarray = array();
    foreach ($array as $key => $value) {
        $newarray[$key] = secure_input($value);
    }
    return $newarray;
}

/**
 * Check JSON file
 */
function check_json(string $json): bool
{
    $check = json_decode($json);
    if ($check === null) {
        return false;
    } else {
        return true;
    }
}

/**
 * Fix
 */
function CORSHeaders()
{
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: *');
    header('Access-Control-Allow-Headers: Content-Type');
}

function _t(string $string)
{
    return $string;
}

/**
 * Return json result
 */
function json_result(string $status, string $message, string $message_type): string
{
    return json_encode([
        "status" => $status,
        "payload" => [
            'message' => _t($message),
            'messageType' => $message_type
        ]
    ]);
}

/**
 * Calcluate pagination
 */
function pagiantionCalc(int $page_number, int $number_rows_on_page): array
{
    if (check_integer($page_number) && check_integer($number_rows_on_page)) {
        if ($page_number === 1) {
            $from = 0;
            $to = $number_rows_on_page;
        } else {
            $from = ($page_number * $number_rows_on_page) - $number_rows_on_page;
            $to = $number_rows_on_page;
        }
    } else {
        return [0, 0];
    }
    return [$from, $to];
}
