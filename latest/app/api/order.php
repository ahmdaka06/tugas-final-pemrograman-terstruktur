<?php
require_once '../../init.php';

if (isset($_GET['__m'])) {
    if ($_GET['__m'] == 'history') {
        $orderHistory = isset($_SESSION['orderHistory']) ? $_SESSION['orderHistory'] : [];
        return response([
            'status' => true,
            'data' => $orderHistory,
            'total' => count($orderHistory)
        ]);
    }
} else {
    exit(http_response_code(404));
}

