<?php

Trait Database{ //use for connect database and run queries.

    protected function connect(){ //create DB connection
        $string = "mysql:hostname=".DBHOST.";dbname=".DBNAME;
        try {
            $conn = new PDO($string,DBUSER,DBPASS); //handle database connection using PDO 
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            error_log('Database connection error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function query($query,$data = []){

        try {
            $conn = $this->connect();
            $stm=$conn->prepare($query);

            $check = $stm->execute($data);
            if($check){
                $result = $stm->fetchAll(PDO::FETCH_OBJ);
                if(is_array($result) && count($result)){
                    return $result;
                }
            }
        } catch (PDOException $e) {
            error_log('Database query error: ' . $e->getMessage());
            error_log('Query: ' . $query);
            error_log('Data: ' . print_r($data, true));
            
            // Store error for debugging
            if (property_exists($this, 'errors')) {
                $this->errors['database'] = $e->getMessage();
            }
            
            throw $e;
        }

        return false;
    }

    public function getRow($query,$data = []){
        
        $conn = $this->connect();
        $stm=$conn->prepare($query);

        $check = $stm->execute($data);
        if($check){
            $result = $stm->fetchAll(PDO::FETCH_OBJ);
            if(is_array($result) && count($result)){
                return $result[0];
            }
        }

        return false;
    }


}



// $string = "mysql:hostname=localhost;dbname=my_db";
// $con = new PDO($string,'root','');

// show($conn);
