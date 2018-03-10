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

if (!empty($email)) {
    $prepare = $pdo->prepare("select email, id from clients");
    $prepare->execute([]);
    $findMail = $prepare->fetchAll(PDO::FETCH_ASSOC);
    foreach ($findMail as $item) {
        echo "<pre>";
//        print_r($item);
        foreach ($item as $key) {
            if ($key == $email) {
                echo "Вы уже зарегистрированы";
                $prepare = $pdo->prepare("select id from clients WHERE email = :email");
                $prepare->execute(['email' => $key]);
                $findId = $prepare->fetch(PDO::FETCH_ASSOC);
                $id = $findId['id'];
                break 2;
            }
        }
    }
}

$prepare = $pdo->prepare("INSERT INTO clients (name, email,phone) VALUES (:name, :email,:phone )");
$prepare->execute(['name' => $name, 'email' => $email, 'phone' => $phone]);

if (!$id) {

    $prepare = $pdo->prepare("select id from clients WHERE email = :email");
    $prepare->execute(['email' => $email]);
    $findId = $prepare->fetch(PDO::FETCH_ASSOC);
    $id = $findId['id'];
    $newId = $id ;
}

$prepare = $pdo->prepare("INSERT INTO burgers (user_id, street, home,part,aprt,floor,comment,payment,callback) 
VALUES (:user_id, :street, :home, :part, :aprt, :floor, :comment, :payment, :callback)");
$prepare->execute(['user_id' => $id, 'street' => $street, 'home' => $home, 'part' =>
    $part, 'aprt' => $aprt, 'floor' => $floor, 'comment' => $comment, 'payment' => $payment, 'callback' => $callback]);
$prepare = $pdo->prepare("select id , street , home , part , aprt , floor  from burgers WHERE home = :home ");
$prepare->execute(['home' => $home]);
$data = $prepare->fetch(PDO::FETCH_ASSOC);

$order = $data['id'];
$street = $data['street'];
$home = $data['home'];
$part = $data['part'];
$aprt = $data['aprt'];
$floor = $data['floor'];

$prepare = $pdo->prepare("select user_id from burgers WHERE user_id = $id ");
$prepare->execute();
$orders = $prepare->fetchAll(PDO::FETCH_ASSOC);
$numOrders = count($orders);

isset($newId) ? $str = 'Спасибо это Ваш первый заказ' : $str = "Спасибо, это уже $numOrders заказ" ;

$subject = "order";
$message = "
  <p>Заказ номер $order </p>
  <p>Ваш заказ будет доставлен по адресу: улица: $street дом: $home корпус: $part квартира: $aprt этаж: $floor  </p>
  <p>DarkBeefBurger за 500 рублей, 1 шт</p> 
  <p>$str</p>   
";
echo $message ;
mail($email, $subject, $message);






