<?php
CONST APP_PATH = 'app';
$config = [
    'app' => [
        'url' => 'http://localhost/learn/tugas-final/v3/',
        'name' => 'TUGAS FINAL',
        'meta' => [
            'author' => '',
            'description' => ''
        ],
        'path' => '/learn/tugas-final/v3/'
    ],
    'products' => json_decode(file_get_contents('http://localhost/learn/tugas-final/v3/json/product.json'), true),
];