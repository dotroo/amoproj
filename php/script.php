<?php

require_once "Classes/DBRequest.php";

    $db = new DBRequest('localhost', 'amocrm-api', 'root', '');
    $result = $db->select('*', 'users');
    echo var_dump($result);
?>