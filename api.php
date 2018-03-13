<?php


function pdoConnect()
{

    static $pdo;
    if ($pdo === null) {
        include ('config.example.php');
        $dsn = "mysql:host={$config['hostname']};dbname={$config['dbname']};charset=utf8";
        $pdo = new PDO($dsn, $config['dbuser'], $config['dbpassword']);
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
        'payment',
        'callback'
    ];

    $result = [];
    foreach ($keys as $value) {
        if ($value == 'callback') {
            $result[$value] = (!empty($data[$value])) ? "Не перезванивать" : "Перезвонить";
        } else {
            $result[$value] = (!empty($data[$value])) ? trim($data[$value]) : null;
        }
    }
    return $result;
}

function authorization($email, $sqlMailId, $sqlId)
{
    if (!empty($email)) {
        $prepare = pdoQuery($sqlMailId);
        $findMail = $prepare->fetchAll(PDO::FETCH_ASSOC);
        foreach ($findMail as $item) {
            foreach ($item as $key) {
                if ($key == $email) {
                    $prepare = pdoQuery($sqlId, ['email' => $key]);
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