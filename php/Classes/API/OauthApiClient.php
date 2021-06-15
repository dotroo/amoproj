<?php 

namespace Classes\API;

use Classes\API\ApiClient;

class OauthApiClient 
{
    public function getTokenByCode(string $url, string $data) :array
    {
        $request = new ApiClient;
        $headers = ['Content-Type:application/json'];
        $response = $request->curlRequest($url, $headers, 'POST', 'amoCRM-oAuth-client/1.0', $data);
        return $response;
    }

    public function getTokenByRefreshToken(ApiClient $apiClient, string $url) :array
    {
        $appConf = parse_ini_file("../../../configs/app_config.ini");
        $headers = ['Content-Type:application/json'];
        $data = [
            'client_id' => $appConf['CLIENT_ID'],
            'client_secret' => $appConf['SECRET'],
            'grant_type' => "refresh_token",
            'code' => $apiClient->getRefreshToken(),
            'redirect_uri' => "http://0e3f299ae3b7.ngrok.io/php/webhook.php"
        ];
        $response = $apiClient->curlRequest($url, $headers, 'POST', 'amoCRM-oAuth-client/1.0', $data);
        return $response;
    }
}

?>