<?php
session_start();
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$_SERVER['env'] = 'development';

function _DIR_PATH_($path, $extension = 'php')
{
    global $_SERVER;
    if ($_SERVER['env'] === 'development') {
        $directory_root = (isset($_SERVER['DOCUMENT_ROOT'])) ? $_SERVER['DOCUMENT_ROOT']  . '/learn/tugas-final/v3' : $_SERVER['DOCUMENT_ROOT'] . '/learn/tugas-final/v3';
    } else {
        $directory_root = (isset($_SERVER['DOCUMENT_ROOT'])) ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['DOCUMENT_ROOT'] . '/learn/tugas-final/v3';
    }
    
    // print '<pre>' . json_encode($_SERVER, JSON_PRETTY_PRINT) .  '</pre>';
    if ($directory_root === false) die('ROOT ERROR');
    switch ($extension) {
        case 'php':
            return (stristr($path,'.php')) ? "$directory_root/$path" : "$directory_root/$path.php";
            break;
        case 'html':
            return (stristr($path,'.html')) ? "$directory_root/$path" : "$directory_root/$path.html";
            break;
        default:
            return "$directory_root/$path";
            break;
    }
}

date_default_timezone_set('Asia/Jakarta');
require_once 'config.php';
require_once 'app/helper/global.helper.php';
require_once 'app/helper/date.helper.php';
require_once 'app/helper/order.helper.php';
require_once 'app/route.php';

$get_day_indonesia = get_day_indonesia(date('l'));

if (isset($config['web']['url']) AND !is_array($config)) die('configuration not found');