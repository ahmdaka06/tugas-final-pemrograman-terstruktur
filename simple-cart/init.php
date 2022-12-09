<?php
session_start();
$_SERVER['env'] = 'development';

date_default_timezone_set('Asia/Jakarta');
require_once 'config.php';
require_once 'app/helper/global.helper.php';
require_once 'app/helper/date.helper.php';
require_once 'app/helper/order.helper.php';

$get_day_indonesia = get_day_indonesia(date('l'));

if (isset($config['web']['url']) AND !is_array($config)) die('configuration not found');