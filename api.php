<?php
include_once 'keys.php';

function pdoConnect()
{
    static $pdo;
    if ($pdo === null) {
        $dsn = "mysql:host=localhost;dbname=test;charset=utf8";
        $pdo = new PDO($dsn, 'root', 'mars100');
    }
    return $pdo;
}

function pdoQuery($sql, $params = [])
{
    $pdo = pdoConnect();
    $prepare = $pdo->prepare($sql);
    $prepare->execute($params);
    return $prepare;
}

function clearDataBeforeInsert($data)
{
    $keys = [
        'name',
        'phone',
        'email',
        'street',
        'home',
        'part',
        'aprt',
        'floor',
        'comment',
        'payment'
    ];

    $result = [];
    foreach ($keys as $value) {
        if ($value == 'payment') {
            $result[$value] = (!empty($data[$value])) ? "Не перезванимать" : "Перезвонить";
        } elseif ($value == 'comment') {
            $result[$value] = (!empty($data[$value])) ? "Потребуется сдача" : "Оплата картой";
        } else {
            $result[$value] = (!empty($data[$value])) ? trim($data[$value]) : null;
        }
    }
    return $result;
}

function authorization($email,$sqlMailId, $sqlId )
{
    if (!empty($email)) {
        $prepare = pdoQuery($sqlMailId);
        $findMail = $prepare->fetchAll(PDO::FETCH_ASSOC);
        foreach ($findMail as $item) {
            foreach ($item as $key) {
                if ($key == $email) {
                    echo "Вы уже зарегистрированы";
                    $prepare = pdoQuery($sqlId , ['email' => $key]);
                    $findId = $prepare->fetch(PDO::FETCH_ASSOC);
                    $id = $findId['id'];
                    return $id;
                    break 2;


                }
            }
        }
    }
}

function checkId($id, $sql, $params = [])
{
    if (!$id) {
        $prepare = pdoQuery($sql, $params);
        $findId = $prepare->fetch(PDO::FETCH_ASSOC);
        $id = $findId['id'];
        return $id;

    }
}