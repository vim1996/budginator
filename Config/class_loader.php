<?php
function class_loader($class_name) {
    //$class_file = __DIR__ . "/classes/" . $class_name . ".php";
    //$class_file = BASE_URL . "/Config/classes/" . $class_name . ".php";
    $class_file = $_SERVER['DOCUMENT_ROOT'] . "/Budget/Config/classes/" . $class_name . ".php";
    
    if (file_exists($class_file)) {
        require_once $class_file;
    }
}

spl_autoload_register('class_loader');
?>