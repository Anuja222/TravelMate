<?php 

class App{

    private $controller = "Home";
    private $method = "index";
    private $subdirs = ['Admin', 'Traveller', 'AccommodationProvider', 'TransportProvider', 'Shared'];

    private function splitURL(){
        $URL = $_GET['url'] ?? 'home';
        $URL = explode("/",trim($URL,"/"));
        return $URL;
    }

    public function loadController(){
        $URL = $this->splitURL();

        $filename = "../app/controllers/".ucfirst($URL[0]).".php";
        $controllerFound = false;
        
        // Check in main controllers directory first
        if(file_exists($filename)){
            require $filename;
            $this->controller = ucfirst($URL[0]);
            $controllerFound = true;
            unset($URL[0]);
        }
        else{
            // Check in subdirectories
            foreach($this->subdirs as $subdir){
                $filename = "../app/controllers/{$subdir}/".ucfirst($URL[0]).".php";
                if(file_exists($filename)){
                    require $filename;
                    $this->controller = ucfirst($URL[0]);
                    $controllerFound = true;
                    unset($URL[0]);
                    break;
                }
            }
            
            // If still not found, load 404
            if(!$controllerFound){
                $filename = "../app/controllers/_404.php";
                if(file_exists($filename)){
                    require $filename;
                    $this->controller = "_404";
                }
            }
        }
        
        $controller = new $this->controller;
        if(!empty($URL[1])){
            if(method_exists($controller,$URL[1])){
                $this->method = $URL[1];
                unset($URL[1]);
            }
        }
        call_user_func_array([$controller,$this->method],$URL);
    }

}

?>