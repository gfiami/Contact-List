<?php function autoload($className){
    $file = __DIR__.'/class/'.$className.'.php';
    if(is_file($file)){
        require_once($file);
    }
}

spl_autoload_register('autoload');
