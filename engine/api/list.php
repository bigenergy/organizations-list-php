<?php
// ajax обработчик списка всех | by Big_Energy

// показ ошибок
//ini_set('display_errors',1);
//error_reporting(E_ALL);
// подключаем конфиг бд
$configs = include('../config.php');

$getid = $_GET['id'];

// подключаемся к базе

$dsn = "mysql:host=$configs->host;dbname=$configs->database;charset=utf8";

// Параметры задают что в качестве ответа получаем ассоциативный массив
$opt = array(
    PDO::ATTR_ERRMODE  => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
);

// Проверка корректности подключения
try { $pdo = new PDO($dsn, $configs->username, $configs->pass, $opt); }
catch (PDOException $e) { die('Подключение не удалось: ' . $e->getMessage()); }


if($getid != NULL) {
    $array = $pdo->query("SELECT * FROM organizations WHERE id = '$getid'")->fetchAll(PDO::FETCH_ASSOC);
} else {
    $array = $pdo->query("SELECT * FROM organizations")->fetchAll(PDO::FETCH_ASSOC);
}

echo json_encode($array,JSON_UNESCAPED_UNICODE);

?>
