<?php
function class_loader($class_name) {
    $class_name = str_replace("\\", DIRECTORY_SEPARATOR, $class_name);
    $class_file = __DIR__ . DIRECTORY_SEPARATOR . $class_name . ".php";
    
    if (file_exists($class_file)) {
        require_once $class_file;
    }
}

spl_autoload_register('class_loader');;
?>