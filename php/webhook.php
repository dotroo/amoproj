<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include_once '../vendor/autoload.php';

use Classes\DB\DB;
use Classes\API\ApiClientController;
use Classes\Logger\Logger;

Logger::$PATH = __DIR__ . "/../logs";
Logger::getLogger("OAuthTokens")->log("ia rodilsia");
//function logNikita(string $string): void {
//    file_put_contents(__DIR__ . "/../logs/name.log", $string . "\n", 8);
//}

/* получаем данные из конфигурационного файла приложения */
$appConf = parse_ini_file(__DIR__ . "/../configs/app_config.ini");
Logger::getLogger("OAuthTokens")->log("ia rodilsia2");
/* Если хук пришел, записываем данные в переменную и выставляем куку base_domain для дальнейшего поиска ключей авторизации в базе */
if (isset($_GET['code'])) { 
    Logger::getLogger("OAuthTokens")->log("got code");
    $webhookData = [
        'code'    => $_GET['code'],
        'referer' => $_GET['referer'],
    ];
    if (isset($_SERVER['HTTP_REFERER'])) {
        setcookie('base_domain', $_GET['referer'], time()+86400*90);
    }
} else {
    Logger::getLogger("hook")->log("nothing here");
}

/* собираем данные для обмена кода авторизации */
$data = [
    'client_id'     => $appConf['CLIENT_ID'],
    'client_secret' => $appConf['SECRET'],
    'grant_type'    => "authorization_code",
    'code'          => $webhookData['code'],
    'redirect_uri'  => $appConf['REDIRECT_URI']
];

/* обмениваем код */
$oAuthClient = new ApiClientController();
//try 
//{
    Logger::getLogger("OAuthTokens")->log("start send hui");
    $response = $oAuthClient->getTokenByCode($webhookData['referer'] . '/oauth2/access_token', $data);
    Logger::getLogger("OAuthTokens")->log("Successfully exchanged tokens");
    Logger::getLogger("OAuthTokens")->log(json_encode($response));
//}
//catch (\Exception $e)
//{
//    Logger::getLogger("OAuthTokens")->log($e->getMessage());
//}

/* проверяем, есть ли запись в таблице по base_domain */
$sqlData =
[ 
    'access_token'  => $response['access_token'],
    'refresh_token' => $response['refresh_token'],
    'expires'       => $response['expires_in'] + time(),
    'base_domain'   => $webhookData['referer'],
    'client_id'     => $data['client_id']
];
Logger::getLogger("OAuthTokens")->log($sqlData); 

$dbConf = parse_ini_file(__DIR__ . '/../configs/db_config.ini'); //получаем конфиг БД из файла
DB::getInstance($dbConf);
Logger::getLogger("OAuthTokens")->log("connected to db"); 
$select = "SELECT * FROM OAuthKeys WHERE base_domain = :base_domain";
$result = DB::request($select, ['base_domain' => $sqlData['base_domain']]);
Logger::getLogger("OAuthTokens")->log("ran select"); 

/* Если селект ничего не нашел, добавляем запись в таблицу, иначе - обновляем запись */
if ($result->fetch() === false) {
    $insert = "INSERT INTO OAuthKeys(access_token, refresh_token, expires, base_domain, client_id) VALUES (:access_token, :refresh_token, :expires, :base_domain, :client_id)";
    DB::request($insert, $sqlData);
    Logger::getLogger("OAuthTokens")->log("DB insert ok");
} else { 
    $update = "UPDATE OAuthKeys SET access_token = :access_token, refresh_token = :refresh_token, expires = :expires WHERE base_domain = :base_domain)";
    DB::request($update, $sqlData);
    Logger::getLogger("OAuthTokens")->log("DB update ok");
}

/* Если авторизовались из кнопки, перенаправляем на сайт с формой */
if ($_GET['state'] === "state") {
    // header("Location: /front/form.html", true, 301);
}