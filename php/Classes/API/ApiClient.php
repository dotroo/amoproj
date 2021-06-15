<?php 

namespace Classes\API;

use Classes\DB\OAuthTable;

class ApiClient {
    private $access_token;
    private $refresh_token;
    private $expires;
    private $base_domain;

    public function setAccessToken(string $token)
    {
        $this->access_token = $token;
    }

    public function getAccessToken()
    {
        return $this->access_token;
    }

    public function setRefreshToken(string $token)
    {
        $this->refresh_token = $token;
    }

    public function getRefreshToken()
    {
        return $this->refresh_token;
    }

    public function setExpires(int $timestamp)
    {
        $this->expires = time()+$timestamp;
    }

    public function getExpires() {
        return $this->expires;
    }

    public function setBaseDomain(string $base_domain)
    {
        $this->base_domain = $base_domain;
    }

    public function getBaseDomain()
    {
        return $this->base_domain;
    }

    public function curlRequest(string $url, array $headers, string $method, string $userAgent = "amoCRM-API-client/1.0", array $data = []) :array
    {
        $curl = curl_init(); //Сохраняем дескриптор сеанса cURL
        /** Устанавливаем необходимые опции для сеанса cURL  */
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl,CURLOPT_USERAGENT, $userAgent);
        curl_setopt($curl,CURLOPT_URL, $url);
        curl_setopt($curl,CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl,CURLOPT_HEADER, false);
        curl_setopt($curl,CURLOPT_CUSTOMREQUEST, $method);
        if ($method === "POST" || $method === "PATCH") {
            curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($data));
        }        
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
        $out = curl_exec($curl); //Инициируем запрос к API и сохраняем ответ в переменную
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        /** Теперь мы можем обработать ответ, полученный от сервера. */
        $code = (int)$code;
        $errors = [
        	400 => 'Bad request',
        	401 => 'Unauthorized',
        	403 => 'Forbidden',
        	404 => 'Not found',
        	500 => 'Internal server error',
        	502 => 'Bad gateway',
        	503 => 'Service unavailable',
        ];

        try
        {
        	/** Если код ответа не успешный - возвращаем сообщение об ошибке  */
        	if ($code < 200 || $code > 204) {
        		throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undefined error', $code);
        	}
        }
        catch(\Exception $e)
        {
        	die('Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode());
        }

        $response = json_decode($out, true);
        return $response;
    }
}
?>