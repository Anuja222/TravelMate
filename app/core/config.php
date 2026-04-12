<?php

$serverName = $_SERVER['SERVER_NAME'] ?? 'localhost';

if($serverName == 'localhost' || $serverName == '127.0.0.1'){
    $port = $_SERVER['SERVER_PORT'] ?? '80';
    $hostWithPort = $port === '80' ? 'localhost' : 'localhost:' . $port;
    define('DBNAME','travelmate');
    define('DBHOST','localhost');
    define('DBUSER','root');
    define('DBPASS','');
    define('DBDRIVER', '');
    define('ROOT','http://' . $hostWithPort . '/TravelMate/public');
}
else{
    define('DBNAME', 'travelmate');
    define('DBHOST', 'localhost');
    define('DBUSER', 'root');
    define('DBPASS', '');
    define('DBDRIVER', '');
    define('BASE_URL', 'https://yourdomain.com/public');
}

