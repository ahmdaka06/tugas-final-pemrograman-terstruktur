<?php
CONST APP_PATH = 'app';
$config = [
    'app' => [
        'url' => 'http://localhost/learn/tugas-final/latest/',
        'name' => 'TUGAS FINAL',
        'meta' => [
            'author' => '',
            'description' => ''
        ],
        'path' => '/learn/tugas-final/latest/'
    ],
    'products' => json_decode(file_get_contents('http://localhost/learn/tugas-final/latest/json/product.json'), true),
];