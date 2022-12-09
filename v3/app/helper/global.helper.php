<?php
function url(?string $i = null)
{
    global $config;
    return $config['app']['url'] . $i;
}
function redirect(?string $location = null, int $delay = 0) {
    if($delay == 0) return header("Location: $location");
    return print "<meta http-equiv=\"refresh\" content=\"$delay;url=$location\">";
}
function flashdata(array $data) {
    global $_SESSION;
    $_SESSION['result'] = $data;
    return $_SESSION['result'];
}
function alert() {
    global $_SESSION;
    if (isset($_SESSION['result']) AND is_array($_SESSION['result'])) {
        print "<div class=\"alert alert-" . $_SESSION['result']['alert'] . " alert-dismissible fade show\" role=\"alert\" id=\"alert\">
            <strong>" . $_SESSION['result']['title'] . "</strong> <hr> " . $_SESSION['result']['msg'] . "
            <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
        </div>";
        unset($_SESSION['result']);
    }
}
function currency($value = 0){
    $value = ceil($value);
    return number_format($value, 0, ".", ".");
}
function cors() {
    global $_SERVER;
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }
}
function response($response = [], $code = 200){
    print json_encode($response);
    exit;
}
function isMethod($method = '')
{
    global $_SERVER;
    $method = strtolower($method);
    $request_method = strtolower($_SERVER['REQUEST_METHOD']);
    if ($method === $request_method) {
        return true;
    }
    return false;
}
function getURI()
{
    global $_SERVER, $config;
    $request = $_SERVER['REQUEST_URI'];
    if ($_SERVER['env'] === 'development') $request = str_replace($config['app']['path'], '', $request);
    return $request;
}
function render($path)
{
    global $config;
    ob_start();
    include($path);
    $var = ob_get_contents(); 
    ob_end_clean();
    return $var;
}