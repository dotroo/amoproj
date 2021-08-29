<?php

include_once '../vendor/autoload.php';

use Classes\API\ApiClient;
use Classes\API\ApiClientController;
use Classes\API\CurlRequest;
use Classes\DB\DB;
use Classes\Logger\Logger;

Logger::$PATH = __DIR__ . "/../logs";


/* получаем ключи авторизации из таблицы OauthKeys */
$dbConf = parse_ini_file(__DIR__ . '/../configs/db_config.ini');
DB::getInstance($dbConf);

if (!isset($_COOKIE['base_domain'])) {
    echo "Authorize the integration";
    die();
}

$select = "SELECT access_token, refresh_token, expires, client_id, base_domain FROM OAuthKeys WHERE base_domain=?";
$result = DB::request($select, [$_COOKIE['base_domain']]);
$data = $result->fetch();

Logger::getLogger("db_access")->log("connected to DB on script.php");
Logger::getLogger("db_access")->log("Got data:\n" . json_encode($data));

$apiClient = new ApiClient;
$apiClient->setAccessToken($data['access_token']);
$apiClient->setRefreshToken($data['refresh_token']);
$apiClient->setExpires($data['expires']);
$apiClient->setBaseDomain($data['base_domain']);

Logger::getLogger("OAuthTokens")->log("built API Client");

/* если access token истек, получаем новый по refresh токену */
if ($apiClient->getExpires() <= time()) {
    Logger::getLogger("OAuthTokens")->log("Exchanging old access token on script.php");

    $oAuth = new ApiClientController($apiClient);
    $tokenUrl = $apiClient->getBaseDomain() . '/oauth2/access_token';
    $exchange = $oAuth->getTokenByRefreshToken($tokenUrl);
    
    $apiClient->setAccessToken($exchange['access_token']);
    $apiClient->setRefreshToken($exchange['refresh_token']);
    $apiClient->setExpires($exchange['expires_in'] ?? 0);
    
    Logger::getLogger('OAuthTokens')->log("Exchange ok");

    /* обновляем данные в таблице */
    $update = "UPDATE OAuthKeys SET access_token = :access_token, refresh_token = :refresh_token, expires = :expires WHERE base_domain = :base_domain";
    $sqlData = [
        'access_token'  => $apiClient->getAccessToken(),
        'refresh_token' => $apiClient->getRefreshToken(),
        'expires'       => $apiClient->getExpires() + time(),
        'base_domain'   => $apiClient->getBaseDomain()
    ];
    DB::request($update, $sqlData);

    Logger::getLogger("db_access")->log("DB token update ok");

}

$headers = [ //заголовки для API запросов
    'Content-Type:application/json',
    'Authorization: Bearer ' . $apiClient->getAccessToken()
];
/* проверяем, что пришло с фронта запросом POST */
$response = '';
switch ($_POST['method']) {
    case 'GET':
        $response = CurlRequest::curlRequest($apiClient->getBaseDomain() . $_POST['url'], $headers, $_POST['method']);
        break;
    case 'POST':
    case 'PATCH':
        $response = CurlRequest::curlRequest($apiClient->getBaseDomain() . $_POST['url'], $headers, $_POST['method'], json_decode($_POST['body']));
        break;
    case 'DELETE':
        break;
    default:
        $response = "Unsupported method";

}

/* отправляем ответ на запрос в form_submit.js */
echo json_encode($response);