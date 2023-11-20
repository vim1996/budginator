<?php
echo __DIR__ . "<br>";

function class_loader($class_name) {
    $class_file = __DIR__ . "/classes/" . $class_name . ".php";

    
    if (file_exists($class_file)) {
        require_once $class_file;
    }
}

spl_autoload_register('class_loader');
?>