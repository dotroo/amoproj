<?php
$secret = "koMh8vMa4MU9aVQOKYgy5DrLM8zYII8QFPqn1DmAN441pGjmhaW3Uw6enXEclOIM";
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

$oauthCodeExchange = new Classes\OauthRequest($webhookData['referer'] . "/oauth2/access_token", "POST", $data);

$oauthCodeExchange->initRequest();

$response = $oauthCodeExchange->getResponse();

$apiClient = new Classes\ApiClient;
$apiClient->setAccessToken($response['access_token'])
          ->setRefreshToken($response['refresh_token'])
          ->setExpires($response['expires_in']);
$dbData = [
    'access_token' => $apiClient->getAccessToken(),
    'refresh_token' => $apiClient->getRefreshToken(),
    'expires' => $apiClient->getExpires()
];
$addDataToDB = new DBRequest("localhost", "root", "", "amocrm-api");
$addDataToDB->insert("oauth", $dbData);     
?>