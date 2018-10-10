<?php

include_once '../classes/API.class.php';

// показ ошибок
//ini_set('display_errors',1);
//error_reporting(E_ALL);

$_GET["email"] = NULL;

if (isset($_GET["orgname"]) && isset($_GET["orgtype"]) && isset($_GET["INN"]) && isset($_GET["number"])) {

    $add_class = new API();
    if ($_GET["orgtype"] == 'IP') {
        $add = $add_class->add_org($_GET["orgname"], "IP", $_GET["INN"], NULL, $_GET["number"], $_GET["email"]);
    } elseif ($_GET["orgtype"] == 'UL') {
        $add = $add_class->add_org($_GET["orgname"], "UL", $_GET["INN"], $_GET["KPP"], $_GET["number"], $_GET["email"]);
    }
    echo $add;

} else {
    echo 'Переданы не все параметры запроса';
}
