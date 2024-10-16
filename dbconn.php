<?php
function dbconn() {
    $con = new mysqli("localhost", "root", "", "systemdb");
    
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }
    
    return $con;
}
?>
