<?php
function class_loader($class_name) {
    define('CLASSES', __DIR__ . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . $class_name . '.php');
    $class_file = CLASSES;
    
    if (file_exists($class_file)) {
        require_once $class_file;
    }
}

spl_autoload_register('class_loader');
?>