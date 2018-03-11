<?php
include_once('api.php');

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
    $prepare = pdoQuery("select email, id from clients");
    $findMail = $prepare->fetchAll(PDO::FETCH_ASSOC);
    foreach ($findMail as $item) {
        foreach ($item as $key) {
            if ($key == $email) {
                echo "Вы уже зарегистрированы";
                $prepare = pdoQuery("select id from clients WHERE email = :email", ['email' => $key]);
                $findId = $prepare->fetch(PDO::FETCH_ASSOC);
                $id = $findId['id'];
                break 2;
            }
        }
    }
}

$prepare = pdoQuery("INSERT INTO clients (name, email,phone) VALUES (:name, :email,:phone )", ['name' => $name, 'email' => $email, 'phone' => $phone]);

if (!$id) {
    $prepare = pdoQuery("select id from clients WHERE email = :email", ['email' => $email]);
    $findId = $prepare->fetch(PDO::FETCH_ASSOC);
    $id = $findId['id'];
    $newId = $id;
}

$prepare = pdoQuery("INSERT INTO burgers (user_id, street, home,part,aprt,floor,comment,payment,callback) 
VALUES (:user_id, :street, :home, :part, :aprt, :floor, :comment, :payment, :callback)",
    ['user_id' => $id, 'street' => $street, 'home' => $home, 'part' =>
    $part, 'aprt' => $aprt, 'floor' => $floor, 'comment' => $comment, 'payment' => $payment, 'callback' => $callback]);
$prepare = pdoQuery("select id , street , home , part , aprt , floor  from burgers WHERE user_id = :user_id ",['user_id' => $id]);
$dataClient = $prepare->fetchAll(PDO::FETCH_ASSOC);
$data = $dataClient[count($dataClient) - 1];

$order = $data['id'];
$street = $data['street'];
$home = $data['home'];
$part = $data['part'];
$aprt = $data['aprt'];
$floor = $data['floor'];

$prepare = pdoQuery("select user_id from burgers WHERE user_id = $id");
$orders = $prepare->fetchAll(PDO::FETCH_ASSOC);
$numOrders = count($orders);

isset($newId) ? $str = 'Спасибо это Ваш первый заказ' : $str = "Спасибо, это уже $numOrders заказ";

$subject = "order";
$message = "
  <p>Заказ номер $order </p>
  <p>Ваш заказ будет доставлен по адресу: улица: $street дом: $home корпус: $part квартира: $aprt этаж: $floor  </p>
  <p>DarkBeefBurger за 500 рублей, 1 шт</p> 
  <p>$str</p>   
";
echo $message;
mail($email, $subject, $message);






