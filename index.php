<?php
session_start();
$conn = new mysqli("localhost", "root", "", "roncelli_gym");
if ($conn->connect_error) {
    die("Errore connessione");
}
