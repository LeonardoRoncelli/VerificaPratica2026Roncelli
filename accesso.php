<?php
$host="localhost";
$user="roncelli";
$password="verifica";
$dbname="roncelli_gym";
$conn=new mysqli ($host,$user,$password,$dbname);
if($conn->connection_error){
        die("Errore connnessione");
}