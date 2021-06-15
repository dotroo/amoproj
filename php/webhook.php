<?php

include_once '../vendor/autoload.php';

use Classes\DB\DB;
use Classes\API\OauthApiClient;

/* получаем данные из конфигурационного файла приложения */
$appConf = parse_ini_file("../configs/app_config.ini");

/* Если хук пришел, записываем данные в переменную и выставляем куку base_domain для дальнейшего поиска ключей авторизации в базе */
if (isset($_GET['code'])) { 
    $webhookData = [
        'code' => $_GET['code'],
        'referer' => $_GET['referer'],
    ];
    setcookie('base_domain', $_GET['referer'], time()+86400*30);
}

/* собираем данные для обмена кода авторизации */
$data = [
    'client_id' => $appConf['CLIENT_ID'],
    'client_secret' => $appConf['SECRET'],
    'grant_type' => "authorization_code",
    'code' => $webhookData['code'],
    'redirect_uri' => "http://0e3f299ae3b7.ngrok.io/php/webhook.php"
];

/* обмениваем код */
$OauthClient = new OauthApiClient;
$OauthClient->getTokenByCode($webhookData['referer'] . '/oauth2/access_token', $data);

/* проверяем, есть ли запись в таблице по base_domain */
$sqlData =
[ 
    'access_token' => $OauthClient['access_token'],
    'refresh_token' => $OauthClient['refresh_token'],
    'expires' => $OauthClient['expires_in'],
    'base_domain' => $$webhookData('referer'),
    'client_id' => $data['client_id']
];
$dbConf = parse_ini_file('../configs/db_config.ini'); //получаем конфиг БД из файла
$connect = DB::getInstanse();
$select = "SELECT * FROM OAuthKeys WHERE base_domain = ':base_domain'";
$result = $connect->request($select, [':base_domain' => $sqlData['base_domain']]); 

/* Если селект ничего не нашел, добавляем запись в таблицу */
if ($result->fetch() === false) {
    $insert = "INSERT INTO OauthKeys(access_token, refresh_token, expires, base_domain, client_id) VALUES (:access_token, :refresh_token, :expires, :base_domain, :client_id)";
    $connect->request($insert, $sqlData);
} else { //иначе - обновляем запись
    $update = "UPDATE OauthKeys SET access_token = :access_token, refresh_token = :refresh_token, expires = :expires WHERE base_domain = :base_domain)";
    $connect->request($update, $sqlData);
}

/* Если авторизовались из кнопки, перенаправляем на сайт с формой */
if ($_GET['state'] === "state") {
    header("Location: /front/form.html", true, 301);
}