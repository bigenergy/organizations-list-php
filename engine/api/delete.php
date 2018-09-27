<?php
// ajax обработчик удаления организации | by Big_Energy

// показ ошибок
//ini_set('display_errors',1);
//error_reporting(E_ALL);
// подключаем конфиг бд
$configs = include('../config.php');

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

$OrgID = $_GET["id"];
if($OrgID == NULL) {
    $result = array('error' => 'Не указан ID организации');
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
    exit();
}

// начало формы
if (isset($_GET["id"])) {

    // проверка на пустые поля
    if(empty($_GET["id"])) {
        $result = array('empty' => 'Укажите ID организации');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    } else {
        // присваиваем переменные (для удобства)



        $sql_upd_type = "DELETE FROM `organizations` WHERE `id` = '$OrgID'";
        $pdo->query($sql_upd_type);

        $result = array('good' => 'Организация успешно удалена');


        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    //echo json_encode($result, JSON_UNESCAPED_UNICODE);
}