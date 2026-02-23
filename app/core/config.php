<?php

if($_SERVER['SERVER_NAME'] == 'localhost'){
    define('DBNAME','travelmate');
    define('DBHOST','localhost');
    define('DBUSER','root');
    define('DBPASS','');
    define('DBDRIVER', '');
    define('ROOT','http://localhost:8080/TravelMate/public');
}
else{
    define('DBNAME', 'travelmate');
    define('DBHOST', 'localhost');
    define('DBUSER', 'root');
    define('DBPASS', '');
    define('DBDRIVER', '');
    define('BASE_URL', 'https://yourdomain.com/public');
}

