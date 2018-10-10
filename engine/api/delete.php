<?php

include_once '../classes/API.class.php';

// показ ошибок
//ini_set('display_errors',1);
//error_reporting(E_ALL);


// начало формы
if (isset($_GET["id"])) {

    $remove_class = new API();
    $remove = $remove_class->remove_org($_GET["id"]);

    echo $remove;

    //echo json_encode($result, JSON_UNESCAPED_UNICODE);
}