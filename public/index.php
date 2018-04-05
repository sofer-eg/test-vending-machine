<?php
session_start();

chdir(dirname(__DIR__));
require 'helper/functions.php';
require 'vendor/autoload.php';

try {

    $config = require 'config/config.php';
    \App\Config::init($config);
    \App\Database::init();
    \App\Model::init(\App\Database::getInstance());
    \App\Request::init($_GET, $_POST, \App\Database::getInstance());

    $App = new \App\Application(\App\Route::getInstance(), \App\View::getInstance(), \App\Model::getInstance());
    $App->run();
} catch (\Exception $e) {
    echo $e->getMessage();
}