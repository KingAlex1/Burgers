<?php
function pdoConnect()
{
    static $pdo;
    if ($pdo === null) {
        $dsn = "mysql:host=localhost;dbname=test;charset=utf8";
        $pdo = new PDO($dsn, 'root', 'mars100');
    }
    return $pdo;
}