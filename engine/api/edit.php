<?php
// ajax обработчик редактирования организации | by Big_Energy

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
        $result = array('empty' => 'Пустые поля');
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    } else {
        // присваиваем переменные (для удобства)

        $OrgID = $_GET["id"];
        if($OrgID == NULL) {
            $result = array('error' => 'Не указан ID организации');
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            exit();
        }
        $OrgName = $_GET["orgname"];
        $OrgType = $_GET["orgtype"];
        $OrgINN = $_GET["INN"];
        $OrgKPP = $_GET["KPP"];
        $OrgNumber = $_GET["number"];
        $OrgEmail = $_GET["email"];

        // запрос на проверку если ли организация с таким ИНН или КПП
        $checksql = "SELECT COUNT(*) FROM organizations WHERE inn = '$OrgINN' ";

        $checksql_kpp = "SELECT COUNT(*) FROM organizations WHERE kpp = '$OrgKPP' ";

        $sql_get_info = "SELECT * FROM organizations WHERE id = '$OrgID'";
        $result_info = $pdo->query($sql_get_info);

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

            if($OrgType == 'IP') {
                // для ИП, у него нет КПП
                if(strlen($OrgINN) == 12 && strlen($OrgKPP) == 0 ) {
                    // проверяем, введено ли 12 символов
                    while ($row = $result_info->fetch()) {
                        if ($OrgName != $row["name"]) {
                            $sql_upd_name = "UPDATE `organizations` SET `name` = '$OrgName' WHERE `id` = '$OrgID'";
                            $pdo->query($sql_upd_name);
                        }
                        if ($OrgType != $row["type"]) {
                            $sql_upd_type = "UPDATE `organizations` SET `type` = '$OrgType' WHERE `id` = '$OrgID'";
                            $pdo->query($sql_upd_type);
                        }
                        if ($OrgINN != $row["inn"]) {
                            if($num_rows == 0) {
                                $sql_upd_inn = "UPDATE `organizations` SET `inn` = '$OrgINN' WHERE `id` = '$OrgID'";
                                $pdo->query($sql_upd_inn);
                            } else {
                                    // есть совпадения по базе
                                    $result = array('already' => 'Организация с таким ИНН уже зарегистрирована в базе');
                                    echo json_encode($result, JSON_UNESCAPED_UNICODE);
                                    die();
                            }
                        }
                        if ($OrgNumber != $row["phone"]) {
                            $sql_upd_number = "UPDATE `organizations` SET `phone` = '$OrgNumber' WHERE `id` = '$OrgID'";
                            $pdo->query($sql_upd_number);
                        }
                        if ($OrgEmail != $row["email"]) {
                            $sql_upd_email = "UPDATE `organizations` SET `email` = '$OrgEmail' WHERE `id` = '$OrgID'";
                            $pdo->query($sql_upd_email);
                        }
                    }

                    $result = array('success' => 'Информация об ИП организации обновлена');
                } else {
                    $result = array('error' => 'ИНН должен составлять 12 символов! КПП для ИП не заполняется!');
                }
            } elseif($OrgType == 'UL') {
                // для ЮЛ, с КПП
                if(strlen($OrgINN) == 10 && strlen($OrgKPP) == 9) {
                    // проверяем, введено ли 10 символов
                    while ($row = $result_info->fetch()) {
                        if ($OrgName != $row["name"]) {
                            $sql_upd_name = "UPDATE `organizations` SET `name` = '$OrgName' WHERE `id` = '$OrgID'";
                            $pdo->query($sql_upd_name);
                        }
                        if ($OrgType != $row["type"]) {
                            $sql_upd_type = "UPDATE `organizations` SET `type` = '$OrgType' WHERE `id` = '$OrgID'";
                            $pdo->query($sql_upd_type);
                        }
                        if ($OrgINN != $row["inn"]) {
                            if($num_rows == 0) {
                                $sql_upd_inn = "UPDATE `organizations` SET `inn` = '$OrgINN' WHERE `id` = '$OrgID'";
                                $pdo->query($sql_upd_inn);
                            } else {
                                // есть совпадения по базе
                                $result = array('already' => 'Организация с таким ИНН уже зарегистрирована в базе');
                                echo json_encode($result, JSON_UNESCAPED_UNICODE);
                                die();
                            }
                        }
                        if ($OrgKPP != $row["kpp"]) {
                            if($num_rows_kpp == 0) {
                                $sql_upd_kpp = "UPDATE `organizations` SET `kpp` = '$OrgKPP' WHERE `id` = '$OrgID'";
                                $pdo->query($sql_upd_kpp);
                            } else {
                                // есть совпадения по базе
                                $result = array('already' => 'Организация с таким КПП уже зарегистрирована в базе');
                                echo json_encode($result, JSON_UNESCAPED_UNICODE);
                                die();
                            }
                        }
                        if ($OrgNumber != $row["phone"]) {
                            $sql_upd_number = "UPDATE `organizations` SET `phone` = '$OrgNumber' WHERE `id` = '$OrgID'";
                            $pdo->query($sql_upd_number);
                        }
                        if ($OrgEmail != $row["email"]) {
                            $sql_upd_email = "UPDATE `organizations` SET `email` = '$OrgEmail' WHERE `id` = '$OrgID'";
                            $pdo->query($sql_upd_email);
                        }
                        $result = array('success' => 'Информация об ЮЛ организации обновлена');
                    }
                } else {
                    $result = array('error' => 'ИНН должен составлять 10 символов, а КПП должен составлять 9!');
                    echo json_encode($result, JSON_UNESCAPED_UNICODE);
                }
            }
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}
