<?php
spl_autoload_register(function($class){
    $path = str_replace('\\','/',$class);
    $path = str_replace('Data', '', $path);
    require_once($_SERVER['DOCUMENT_ROOT'] . '/src' . $path.'.php');
});