<?php

include_once '../vendor/autoload.php';
require_once __DIR__ . '/Classes/DB/DBConfig.php';

use Classes\DB\BaseDB;
use Classes\DB\OAuthTable;
use Classes\API;

/* $secret = "koMh8vMa4MU9aVQOKYgy5DrLM8zYII8QFPqn1DmAN441pGjmhaW3Uw6enXEclOIM";
$integrationId = "37859936-9fa2-4b88-b4de-a60d75ba7209";

if (isset($_GET['code'])){
    $webhookData = [
        'code' => $_GET['code'],
        'referer' => $_GET['referer'],
        'client_id' => $_GET['client_id']
    ];
}

$data = [
    'client_id' => $integrationId,
    'client_secret' => $secret,
    'grant_type' => "authorization_code",
    'code' => $webhookData['code'],
    'redirect_uri' => "http://0e3f299ae3b7.ngrok.io/php/webhook.php"
];

$authCodeExchange = new OauthApiClient;
$authCodeExchange->getTokenByCode($webhookData['referer'] . '/oauth2/access_token', $data);

$apiClient = new ApiClient;
$apiClient->setAccessToken($authCodeExchange['access_token'])
          ->setRefreshToken($authCodeExchange['refresh_token'])
          ->setExpires($authCodeExchange['expires_in']);
$sqlData = [
    $apiClient->getAccessToken(),
    $apiClient->getRefreshToken(),
    $apiClient->getExpires(),
    $webhookData['referer']
]; */

$sqlData = [
    'xxx',
    'yyy',
    '10000',
    'test.amocrm.com'
];

$values = implode(',', $sqlData);

$sql = "INSERT INTO OauthKeys(access_token, refresh_token, expires, base_domain) VALUES ({$values})";

$connect = new OAuthTable;
$connect->request($sql);

?>