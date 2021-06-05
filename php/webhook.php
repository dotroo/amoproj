<?php
require_once __DIR__ . "/logger.php";

if (isset($_GET['code'])){
    $webhookData = [
        'code' => $_GET['code'],
        'referer' => $_GET['referer'],
        'client_id' => $_GET['client_id']
    ];
    json_encode($webhookData);
    file_put_contents("webhook_data.txt", $webhookData);
}
else
    echo "test config";
//test

?>