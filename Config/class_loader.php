<?php
function class_loader($class_name) {
    $class_file = __DIR__ . "/classes/" . $class_name . ".php";
    
    if (file_exists($class_file)) {
        require_once $class_file;
        echo "Works <br>";
    }
}
echo "test";
spl_autoload_register('class_loader');
?>