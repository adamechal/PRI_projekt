<?php

$mysqli = new mysqli("db","user","pass","anishelf");
if($mysqli->connect_error){
    die("Chyba připojení: " . $mysqli->connect_error);
}


