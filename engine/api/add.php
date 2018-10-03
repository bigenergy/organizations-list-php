<?php
// ajax обработчик добавления новой организации | by Big_Energy

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

// начало формы
if (isset($_GET["orgname"]) && isset($_GET["orgtype"]) && isset($_GET["INN"]) && isset($_GET["number"]) && isset($_GET["email"])) {

    // проверка на пустые поля
    if(empty($_GET["orgname"]) || empty($_GET["orgtype"]) || empty($_GET["INN"]) || empty($_GET["number"])) {
        $result = array('empty' => 'empty');
        echo json_encode($result);
    } else {
        // присваиваем переменные (для удобства)
        $OrgName = $_GET["orgname"];
        $OrgType = $_GET["orgtype"];
        $OrgINN = $_GET["INN"];
        $OrgKPP = $_GET["KPP"];
        $OrgNumber = $_GET["number"];
        $OrgEmail = $_GET["email"];

        // запрос на проверку если ли организация с таким ИНН или КПП
        $checksql = "SELECT COUNT(*) FROM organizations WHERE inn = '$OrgINN' ";

        $checksql_kpp = "SELECT COUNT(*) FROM organizations WHERE kpp = '$OrgKPP' ";

        // выводим, если ли совпадения по запросу
        $res = $pdo->query($checksql);
        $num_rows = $res->fetchColumn();

        $res_kpp = $pdo->query($checksql_kpp);
        $num_rows_kpp = $res_kpp->fetchColumn();

        if(!preg_match("/^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/", $OrgNumber)) {
            $result = array('error' => 'Номер должен состоять из 10 символов!');
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            exit();
        }
        $ru = preg_match('~[а-яё]+~iu', $OrgName);
        $en = preg_match('~[a-z]+~i', $OrgName);

        if (! ($ru ^ $en) ) {
            $result = array('errorlang' => 'Наименование организации должно использовать или кириллицу, или латиницу!');
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            exit();
        }

        if(strlen($OrgName) > 255) {
            $result = array('error' => 'Наименование организации превышает установленный лимит в 255 символов!');
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            exit();
        }
        if($OrgEmail != NULL) {
            if (!filter_var($OrgEmail, FILTER_VALIDATE_EMAIL)) {
                // invalid emailaddress
                $result = array('error' => 'Неверный E-Mail адрес!');
                echo json_encode($result, JSON_UNESCAPED_UNICODE);
                exit();
            }
        }




        if($num_rows == 0) {
            // сопадений по бд нет, выполняем дальше
            if($OrgType == 'IP') {
                // для ИП, у него нет КПП
                if(strlen($OrgINN) == 12 ) {
                    // проверяем, введено ли 12 символов
                    $sql_add ="INSERT INTO `organizations` (`id`, `name`, `type`, `inn`, `kpp`, `phone`, `email`) VALUES (NULL, '$OrgName', '$OrgType', '$OrgINN', NULL, '$OrgNumber', '$OrgEmail');";
                    // выполнение запроса
                    $pdo->query($sql_add);
                    $result = array('success' => 'ИП организация добавлена');
                    echo json_encode($result, JSON_UNESCAPED_UNICODE);
                } else {
                    $result = array('error' => 'ИНН должен составлять 12 символов!');
                    echo json_encode($result, JSON_UNESCAPED_UNICODE);
                }

            } elseif($OrgType == 'UL') {
                // для ЮЛ, с КПП
                if(strlen($OrgINN) == 10 && strlen($OrgKPP) == 9) {
                    // проверяем, введено ли 10 символов
                    if($num_rows_kpp == 0) {
                        $sql_add = "INSERT INTO `organizations` (`id`, `name`, `type`, `inn`, `kpp`, `phone`, `email`) VALUES (NULL, '$OrgName', '$OrgType', '$OrgINN', '$OrgKPP', '$OrgNumber', '$OrgEmail');";
                        // выполнение запроса
                        $pdo->query($sql_add);
                        $result = array('success' => 'ЮЛ организация добавлена');
                        echo json_encode($result, JSON_UNESCAPED_UNICODE);
                    } else {
                        $result = array('already' => 'Организация с таким КПП уже зарегистрирована в базе');
                        echo json_encode($result, JSON_UNESCAPED_UNICODE);
                    }
                } else {
                    $result = array('error' => 'ИНН должен составлять 10 символов, а КПП должен составлять 9!');
                    echo json_encode($result, JSON_UNESCAPED_UNICODE);
                }

            }

        } else {
            // есть совпадения по базе
            $result = array('already' => 'Организация с таким ИНН или КПП уже зарегистрирована в базе');
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        }

    }

    //echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
