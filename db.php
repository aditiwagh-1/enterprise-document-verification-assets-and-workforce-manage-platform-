<?php

$conn = mysqli_connect("localhost","root","","enterprise_db");

if(!$conn){
    die("Database connection Failed!:".mysqli_connect_error());

}
?>