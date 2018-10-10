<?php

include_once '../classes/API.class.php';

if (isset($_GET["id"]) && isset($_GET["orgname"]) && isset($_GET["orgtype"]) && isset($_GET["INN"]) && isset($_GET["number"]) && isset($_GET["email"])) {
    $edit_class = new API();
    if ($_GET["orgtype"] == 'IP') {
        $edit = $edit_class->edit_org($_GET["id"], $_GET["orgname"], "IP", $_GET["INN"], NULL, $_GET["number"], $_GET["email"]);
    } elseif ($_GET["orgtype"] == 'UL') {
        $edit = $edit_class->edit_org($_GET["id"], $_GET["orgname"], "UL", $_GET["INN"], $_GET["KPP"], $_GET["number"], $_GET["email"]);
    }
    echo $edit;

} else {
    echo 'Переданы не все параметры запроса!';
}
