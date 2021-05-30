<?php

include __DIR__ . "logger.php";

logger("wh request", $_GET);

if (isset($_GET['code'])){
    $webhookData = [
        'code' => $_GET['code'],
        'referer' => $_GET['referer'],
        'client_id' => $_GET['client_id']
    ];
    logger("Webhook data", $webhookData);
    json_encode($webhookData);
    file_put_contents("webhook_data.txt", $webhookData);
}
else
    echo "test config";
//test

?>