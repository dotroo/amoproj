<?php

include_once '../vendor/autoload.php';
require_once __DIR__ . '/Classes/DB/DBConfig.php';

use Classes\DB\BaseDB;
use Classes\DB\OAuthTable;
use Classes\API;

$secret = "koMh8vMa4MU9aVQOKYgy5DrLM8zYII8QFPqn1DmAN441pGjmhaW3Uw6enXEclOIM";
$integrationId = "37859936-9fa2-4b88-b4de-a60d75ba7209";

/* Если хук пришел, записываем данные в переменную */
if (isset($_GET['code'])){ 
    $webhookData = [
        'code' => $_GET['code'],
        'referer' => $_GET['referer'],
        'client_id' => $_GET['client_id']
    ];
}

/* собираем данные для обмена кода авторизации */
$data = [
    'client_id' => $integrationId,
    'client_secret' => $secret,
    'grant_type' => "authorization_code",
    'code' => $webhookData['code'],
    'redirect_uri' => "http://0e3f299ae3b7.ngrok.io/php/webhook.php"
];

/* обмениваем код */
$OauthClient = new OauthApiClient;
$OauthClient->getTokenByCode($webhookData['referer'] . '/oauth2/access_token', $data);

/* собираем обменянные данные для записи в таблицу */
$apiClient = new ApiClient;
$apiClient->setAccessToken($OauthClient['access_token'])
          ->setRefreshToken($OauthClient['refresh_token'])
          ->setExpires($OauthClient['expires_in'])
          ->setBaseDomain($webhookData('referer'));

$sqlData = [
    'access_token' => $apiClient->getAccessToken(),
    'refresh_token' => $apiClient->getRefreshToken(),
    'expires' => $apiClient->getExpires(),
    'base_domain' => $apiClient->getBaseDomain()
];

/* проверяем, есть ли запись в таблице по base_domain */
$connect = new OAuthTable;
$select = "SELECT * FROM OAuthKeys WHERE base_domain = ':base_domain'";
$result = $connect->request($select, [':base_domain' => $sqlData['base_domain']]); // возвращаем объект PDOStatement
/* Если селект ничего не нашел, добавляем запись в таблицу */
if ($result->fetch() === false) {
    $insert = "INSERT INTO OauthKeys(access_token, refresh_token, expires, base_domain) VALUES (:access_token, :refresh_token, :expires, :base_domain)";
    $connect->request($insert, $sqlData);
} else {
    $update = "UPDATE OauthKeys SET access_token = :access_token, refresh_token = :refresh_token, expires = :expires WHERE base_domain = :base_domain)";
    $connect->request($update, $sqlData);
}