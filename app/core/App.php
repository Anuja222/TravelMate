<?php 

class App{

    private $controller = "Home"; //default controller
    private $method = "index"; // default method
    private $subdirs = ['Admin', 'Traveller', 'AccommodationProvider', 'TransportProvider', 'Shared'];

    private function splitURL(){ //split URL into segments ro identify controller , method and parameters
        $URL = $_GET['url'] ?? 'home';
        $URL = explode("/",trim($URL,"/"));
        return $URL; 
    }               

    public function loadController(){ 
        $URL = $this->splitURL(); 

        $filename = "../app/controllers/".ucfirst($URL[0]).".php"; //create a .php file
        $controllerFound = false;
        
        // check in main controllers directory
        if(file_exists($filename)){              
            require $filename; 
            $this->controller = ucfirst($URL[0]); 
            $controllerFound = true; 
            unset($URL[0]); 
        } 
        else{     
            // check in subdirectories
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
            
            // if not found, load 404
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