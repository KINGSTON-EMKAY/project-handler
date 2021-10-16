<?php

// $host = "localhost";
// $username = "root";
// $password = "";
// $dbname = "projects";

// try {
//     $conn = new PDO( "mysql:host=$host;dbname=$dbname", $username, $password );
//     // set the PDO error mode to exception
//     $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
//     // echo "Connected successfully";
// } catch( PDOException $e ) {
//     echo "Connection failed: " . $e->getMessage();
// }



$host = "projects-do-user-4041577-0.b.db.ondigitalocean.com";
$username = "doadmin";
$password = "BBAhTxdLFhGgAm5B";
$dbname = "defaultdb";
$port = "25060";
$sslmode = "REQUIRED";
try {
    $conn = new PDO( "mysql://doadmin:BBAhTxdLFhGgAm5B@projects-do-user-4041577-0.b.db.ondigitalocean.com:25060/defaultdb?ssl-mode=REQUIRED" );
    // set the PDO error mode to exception
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    // echo "Connected successfully";
} catch( PDOException $e ) {
    echo "Connection failed: " . $e->getMessage();
}




?>