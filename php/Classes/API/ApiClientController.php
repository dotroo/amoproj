<?php 

namespace Classes\API;

use Classes\API\ApiClient;
use Classes\API\CurlRequest;

class ApiClientController 
{
    private $apiClient;

    public function __construct(ApiClient $apiClient = null)
    {
        if ($apiClient != null){
            $this->apiClient = $apiClient;
        } else {
            return;
        }
    }

    public function getTokenByCode(string $url, array $data) :array
    {
        $headers = ['Content-Type:application/json'];
        $response = CurlRequest::curlRequest($url, $headers, 'POST', $data, 'amoCRM-oAuth-client/1.0');
        return $response;
    }

    public function getTokenByRefreshToken(string $url) :array
    {
        $appConf = parse_ini_file(__DIR__ . "/../../../configs/app_config.ini");
        $headers = ['Content-Type:application/json'];
        $data = [
            'client_id'     => $appConf['CLIENT_ID'],
            'client_secret' => $appConf['SECRET'],
            'grant_type'    => "refresh_token",
            'refresh_token' => $this->apiClient->getRefreshToken(),
            'redirect_uri'  => $appConf['REDIRECT_URI']
        ];
        $response = CurlRequest::curlRequest($url, $headers, 'POST', $data, 'amoCRM-oAuth-client/1.0');
        return $response;
    }
}

?>