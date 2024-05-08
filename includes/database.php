<?php

$db = mysqli_connect(
    $_ENV['DB_HOST'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS'],
    $_ENV['DB_NAME'],
);
<<<<<<< HEAD
//
=======
//$db = mysqli_connect('localhost', 'root', 'aranibar', 'APPSALON_MVC');

>>>>>>> e797c2c6889738b5cac5b1d7cfcc57b692dfbbb4
$db->set_charset('utf8');

if (!$db) {
    echo "Error: No se pudo conectar a MySQL.";
    echo "errno de depuración: " . mysqli_connect_errno();
    echo "error de depuración: " . mysqli_connect_error();
    exit;
}
