<?php

if($_SERVER['SERVER_NAME'] == 'localhost'){
    define('DBNAME','my_db');
    define('DBHOST','localhost');
    define('DBUSER','root');
    define('DBPASS','');
    define('DBDRIVER', '');
    define('ROOT','http://localhost/TravelMate/public');
}
else{
    define('DBNAME', 'my_db');
    define('DBHOST', 'localhost');
    define('DBUSER', 'root');
    define('DBPASS', '');
    define('DBDRIVER', '');
    define('BASE_URL', 'https://yourdomain.com/public');
}

