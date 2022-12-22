<?php
require_once '../../init.php';
header('Content-type: application/json');

return response([
    'status' => true,
    'msg' => 'Data produk berhasil di dapatkan!.',
    'data' => $config['products']
]);

