<?php
//manage database connection details
if($_SERVER['SERVER_NAME'] == 'localhost'){ //check whether project is run on a local server or a live server
    define('DBNAME','travelmate');
    define('DBHOST','localhost');
    define('DBUSER','root');
    define('DBPASS','');
    define('DBDRIVER', '');
    define('ROOT','http://localhost/TravelMate/public');
}
else{
    define('DBNAME', 'travelmate');
    define('DBHOST', 'localhost');
    define('DBUSER', 'root');
    define('DBPASS', '');
    define('DBDRIVER', '');
    define('BASE_URL', 'https://yourdomain.com/public');
}

