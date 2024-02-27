<?php
session_start();
require_once 'config/cors.php';
require_once 'config/db.php';
require_once 'autoload.php';
require_once 'config/parameters.php';

$cors = new Cors();
$cors->headers();

if(isset($_GET['controller'])){
    $controller_name = $_GET['controller'].'Controller';
}elseif(!isset($_GET['controller']) && !isset($_GET['action'])){
    $controller_name = controller_default;
}

if(class_exists($controller_name)){
    $controller = new $controller_name();
    if(isset($_GET['action']) && method_exists($controller, $_GET['action'])){
        $action = $_GET['action'];
        $controller->$action();
    }
}