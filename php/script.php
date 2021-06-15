<?php

use Classes\API\ApiClient;
use Classes\API\OauthApiClient;

/* получаем ключи авторизации из таблицы OauthKeys */
$connect = DB::getInstance();
$select = "SELECT access_token, refresh_token, expires, client_id, base_domain FROM OauthKeys WHERE base_domain=?";
$result = $connect->request($select, $_COOKIE['base_domain']);
$result->fetch();


$apiClient = new ApiClient;
$apiClient->setAccessToken($result['access_token'])
          ->setRefreshToken($result['refresh_token'])
          ->setExpires($result['expires'])
          ->setBaseDomain($result['base_domain']);

/* если access token истек, получаем новый по refresh токену */
if ($apiClient->getExpires() > time()) {
    $oAuth = new OauthApiClient;
    $url = $apiClient->getBaseDomain() . '/oauth2/access_token';
    $exchange = $oAuth->getTokenByRefreshToken($apiClient, $url);
    $apiClient->setAccessToken($exchange['access_token'])
              ->setRefreshToken($exchange['refresh_token'])
              ->setExpires($exchange['expires']);
    
    /* обновляем данные в таблице */
    $update = "UPDATE OauthKeys SET access_token = :access_token, refresh_token = :refresh_token, expires = :expires WHERE base_domain = :base_domain)";
    $sqlData = [
        'access_token'  => $apiClient->getAccessToken(),
        'refresh_token' => $apiClient->getRefreshToken(),
        'expires'       => $apiClient->getExpires(),
        'base_domain'   => $apiClient->getBaseDomain()
    ];
    $connect->request($update, $sqlData);
}

$headers = [ //заголовки для API запросов
    'Content-Type:application/json',
    'Authorization: Bearer ' . $apiClient->getAccessToken()
];
/* проверяем, что пришло с фронта запросом POST */
switch ($_POST['method']) {
    case "GET":
        $apiClient->curlRequest($_POST['url'], $headers, $_POST['method']);
        break;
    case "POST":
    case "PATCH":
        $apiClient->curlRequest($_POST['url'], $headers, $_POST['method'], $data);
        break;
    case "DELETE":
        break;
    default:
        echo "Unsupported method";
        break;
}