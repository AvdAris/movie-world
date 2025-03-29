<?php

$request = $_SERVER['REQUEST_URI'];
$request = parse_url($request, PHP_URL_PATH);
$filePath = __DIR__ . $request;

if ($request !== '/' && file_exists($filePath)) {
    return false;
}

switch ($request) {
    case '/':
        require 'login.php';
        break;
    
    default:
        require 'login.php';
        break;
}
