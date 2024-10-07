<?php

class Autoloader {
    public static function autoload($class_name) {
        require 'view/'. $class_name . '.php';
    }
}