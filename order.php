<?php
$dsn = "mysql:host=localhost;dbname=test;charset=utf8";
$pdo = new PDO($dsn, 'root', 'mars100');

$name = $_POST['name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$street = $_POST['street'];
$home = $_POST['home'];
$part = $_POST['part'];
$aprt = $_POST['aprt'];
$floor = $_POST['floor'];
$comment = $_POST['comment'];
$payment = $_POST['payment'] == "on" ? "Потребуется сдача" : "Оплата картой";
$callback = isset($_POST['callback']) ? "Не перезвонить" : "Перезвонить";

//if (!empty($email)) {
//    $prepare = $pdo->prepare("select email , id from clients");
//    $prepare->execute([]);
//    $findMail = $prepare->fetchAll(PDO::FETCH_ASSOC);
//    foreach ($findMail as $item) {
//        echo "<pre>";
////        print_r($item);
//        foreach ($item as $key) {
//
////            if ($key == $email) {
////                echo $key;
////                echo "Вы уже зарегистрированы";
////                $prepare = $pdo->prepare("select id from clients WHERE email = :email");
////                $prepare->execute(['email' =>$key]);
////                $findId = $prepare->fetch(PDO::FETCH_ASSOC);
////                $id = $findId['id'];
////                break 2;
////            }else {
////                $prepare = $pdo->prepare("INSERT INTO clients (name, email,phone) VALUES (:name, :email,:phone )");
////                $prepare->execute(['name' => $name, 'email' => $email, 'phone' => $phone]);
////                $prepare = $pdo->prepare("select id from clients WHERE email = :email");
////                $prepare->execute(['email' =>$key]);
////                $findId = $prepare->fetch(PDO::FETCH_ASSOC);
////                $id = $findId['id'];
////            }
//
//        }
//    }
//}



$prepare = $pdo->prepare("INSERT INTO clients (name, email,phone) VALUES (:name, :email,:phone )");
$prepare->execute(['name' => $name, 'email' => $email, 'phone' => $phone]);
$prepare = $pdo->prepare("INSERT INTO burgers (street, home,part,aprt,floor,comment,payment,callback) 
VALUES (:street, :home, :part, :aprt, :floor, :comment, :payment, :callback)");
$prepare->execute([ 'street' => $street, 'home' => $home, 'part' => $part, 'aprt' => $aprt, 'floor' => $floor, 'comment' => $comment, 'payment' => $payment, 'callback' => $callback]);
$prepare = $pdo->prepare("select id , street , home , part , aprt , floor  from burgers WHERE home = :home ");
$prepare->execute(['home' => $home]);
$data = $prepare->fetch(PDO::FETCH_ASSOC);

$arr = './order.csv';
file_put_contents('order.csv', implode(";", $data));

$mail = "alex-sert2010@mail.ru";
$subject = "order";
$message = "
<html>

<body>
  <p>Заказ номер $id </p>
  <p>Ваш заказ будет доставлен по адресу: </p>
  <p>DarkBeefBurger за 500 рублей, 1 шт</p> 
  <p>Спасибо - это ваш первый заказ</p> 
  
</body>
</html>
";
mail($mail, $subject, $message);






