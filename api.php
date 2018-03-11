<?php

function pdoConnect()
{
    static $pdo;

    $dsn = "mysql:host=localhost;dbname=test;charset=utf8";
    $pdo = new PDO($dsn, 'root', 'mars100');
    return $pdo;
}

function pdoQuery($sql, $params = [])
{
    $pdo = pdoConnect();
    $prepare = $pdo->prepare($sql);
    $prepare->execute($params);

    return $prepare;
}