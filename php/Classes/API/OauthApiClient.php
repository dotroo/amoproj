<?php 

namespace Classes\API;

use Classes\API\ApiClient;

class OauthApiClient 
{
    public function getTokenByCode($url, $data) {
        $request = New ApiClient;
        $headers = ['Content-Type:application/json'];
        $response = $request->curlRequest('amoCRM-oAuth-client/1.0', $url, $headers, 'POST', $data);
        return $response;
    }

    public function getTokenByRefreshToken() {
        // tba
    }
}

?>