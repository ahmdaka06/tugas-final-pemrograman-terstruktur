<?php
CONST APP_PATH = 'app';
$config = [
    'app' => [
        'url' => 'http://localhost/learn/tugas-final/simple-cart/',
        'name' => 'TUGAS FINAL',
        'meta' => [
            'author' => '',
            'description' => ''
        ],
        'path' => '/learn/tugas-final/simple-cart/'
    ],
    'products' => json_decode(file_get_contents('http://localhost/learn/tugas-final/simple-cart/json/product.json'), true),
];