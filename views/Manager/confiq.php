
<?php
$host="localhost";  // database server
$user="root";   // database username
$pass="";  // database password
$dbname="staff";  // database name
 
$conn = new mysqli($host, $user, $pass, $dbname);
 if($conn-> connect_error)
 {
 
    die ("Connection Fail". $conn-> connect_error) ;
 
 }
 
?>