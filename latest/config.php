<?php
CONST APP_PATH = 'app';
$config['app'] = [
    'url' => 'http://localhost/learn/tugas-final/latest/', // setting url
    'name' => 'TUGAS FINAL',
    'meta' => [
        'author' => '',
        'description' => ''
    ],
    'path' => '/learn/tugas-final/latest/'
];
$config['products'] = json_decode(file_get_contents($config['app']['url'] . 'json/product.json'), true);