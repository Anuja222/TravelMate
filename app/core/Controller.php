<?php

class Controller{ //this  is the base class of all controllers. all controllers are extend this class

    public function view($name,$data = []){
        
        // extract/convert data array to variables
        if(!empty($data)){
            extract($data);
        }
        
        $filename = "../app/views/".$name.".view.php";
        if(file_exists($filename)){
            require $filename;
        }
        else{
            $filename = "../app/views/404.view.php";
            require $filename;
        }
    }
}