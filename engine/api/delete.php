<?php

include_once '../classes/API.class.php';

if (isset($_GET["id"])) {

    $remove_class = new API();
    $remove = $remove_class->remove_org($_GET["id"]);

    echo $remove;

}