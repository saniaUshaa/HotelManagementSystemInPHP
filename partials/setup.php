<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 

// Create connection 
$conn = mysqli_connect($servername, $username, $password); 
// Check connection 
//if ($conn) {   echo "Connected Successfully";
//}
$database="hotelBookingSystem";
$sql = "DROP DATABASE IF EXISTS $database";
mysqli_query($conn, $sql);
$sql = "CREATE DATABASE $database"; 
mysqli_query($conn, $sql);
/*if (mysqli_query($conn, $sql)) {   
    echo "Database created successfully"; 
}*/ 
// Close the connection
mysqli_close($conn);

?>