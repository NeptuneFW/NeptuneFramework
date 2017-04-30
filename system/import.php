<?php

class Import
{
    public static function view($fileName, $data = array()){
        $file = ROOT . DS . 'resources\view' . DS . $fileName . '.view.php';
        if (file_exists($file)) {
            if ($data == true) {
                extract($data);
            }
            require $file;
        }else {
            echo ErrorHandler::show('Belirtilen görüntü dosyası yüklenemedi.');
        }
    }
    public static function model($modelName){
        $file = ROOT . DS . 'database\model' . DS . $modelName . '.php';
        if (file_exists($file)) {
            require $file;
            return new $modelName();
        }else {
            echo ErrorHandler::show('Belirtilen model bulunamadı.');
        }
    }
}