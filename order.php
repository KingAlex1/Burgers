<?php
include_once('api.php');

$data = clearDataBeforeInsert($_POST);
$id = authorization($data['email'], "SELECT email, id FROM clients ", "SELECT id FROM clients WHERE email = :email");
$prepare = pdoQuery("INSERT INTO clients (name, email,phone) VALUES (:name, :email,:phone )", ['name' => $data['name'], 'email' => $data['email'], 'phone' => $data['phone']]);

$arrID = checkId($id, "SELECT id FROM clients WHERE email = :email", ['email' => $data['email']]);
$arrID ? $id = $arrID : null;
$arrID ? $newId = $arrID : null;

$prepare = pdoQuery("INSERT INTO burgers (user_id, street, home,part,aprt,floor,comment,payment,callback) 
VALUES (:user_id, :street, :home, :part, :aprt, :floor, :comment, :payment, :callback)",
    ['user_id' => $id, 'street' => $data['street'], 'home' => $data['home'], 'part' =>
        $data['part'], 'aprt' => $data['aprt'], 'floor' => $data['floor'], 'comment' =>
        $data['comment'], 'payment' => $data['payment'], 'callback' => $data['callback']]);
$prepare = pdoQuery("SELECT id , street , home , part , aprt , floor  FROM burgers WHERE user_id = :user_id ", ['user_id' => $id]);
$dataClient = $prepare->fetchAll(PDO::FETCH_ASSOC);
$data = $dataClient[count($dataClient) - 1];

$prepare = pdoQuery("SELECT user_id FROM burgers WHERE user_id = $id");
$orders = $prepare->fetchAll(PDO::FETCH_ASSOC);
$numOrders = count($orders);

isset($newId) ? $str = 'Спасибо это Ваш первый заказ' : $str = "Спасибо, это уже $numOrders заказ";

$subject = "order";
$message = "
  <p>Заказ номер {$data['id']} </p>
  <p>Ваш заказ будет доставлен по адресу: улица: {$data['street']} дом: {$data['home']} корпус:  {$data['part']} квартира: {$data['aprt']} этаж: {$data['floor']}  </p>
  <p>DarkBeefBurger за 500 рублей, 1 шт</p> 
  <p>$str</p>   
";
echo $message;
mail($data['email'], $subject, $message);

$prepare = pdoQuery("SELECT id FROM clients ");
$clients = $prepare->fetchAll(PDO::FETCH_ASSOC);
$numClients = count($clients);
$prepare = pdoQuery("SELECT id FROM burgers");
$burgs = $prepare->fetchAll(PDO::FETCH_ASSOC);
$allBurgers = count($burgs);

echo "Всего клиентов: " . $numClients . "<br>";
echo "Всего заказов:" . $allBurgers . "<br>";







