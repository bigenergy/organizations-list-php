<?php

include_once '../classes/API.class.php';

$list_class = new API();
$list = $list_class->list_org($_GET['id']);

echo $list;
