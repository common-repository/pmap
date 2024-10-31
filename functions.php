<?php
if(!function_exists('LoadClassPmap')) {
    function LoadClassPmap($class, $path = '') {

        if(!class_exists($class)) {

            if(!$path) {
                $classFile = $class;
                if(strpos(strtolower($classFile), PMAP_CODE) !== false) {
                    $classFile = preg_replace('/'. PMAP_CODE. '/i', '', $classFile);
                }
                $path = PMAP_CLASSES_DIR. $classFile. '.php';
            }

            // return import($path);
            include_once($path);
        
            return true;


        } else { 
            // 
        }
        
        return false;
    }
}