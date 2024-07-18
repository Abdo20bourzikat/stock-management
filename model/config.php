<?php

    $server_name = 'localhost';
    $db_name = 'gestion_stock_db';
    $user = 'root';
    $pass = '';

    try {
        $cnx = new PDO("mysql:host=$server_name;dbname=$db_name", $user, $pass);
        $cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $cnx;
    } catch (Exception $e) {
        die("Erreur de connexion: " . $e->getMessage());
    }



?>