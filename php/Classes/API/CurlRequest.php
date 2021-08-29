<?php

namespace Classes\API;

include_once '../../../vendor/autoload.php';
use Classes\Logger\Logger;

class CurlRequest
{
    public static function curlRequest(string $url, array $headers, string $method, array $data = [], string $userAgent = "amoCRM-API-client/1.0") :array
    {
        $url = "https://" . $url;
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
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, false);
        //curl_setopt($curl, CURLOPT_VERBOSE, true);
        //$stream = fopen('php://temp', 'w+');
        //curl_setopt($curl, CURLOPT_STDERR, $stream);
        $out = curl_exec($curl); //Инициируем запрос к API и сохраняем ответ в переменную
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlError = curl_error($curl);
        /* логируем запрос */
        //rewind($stream);
        //$verboseCurlLog = stream_get_contents($stream);
        //Logger::getLogger('curl_requests')->log($verboseCurlLog);
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
        		throw new \Exception($out, $code);
        	}
         }
        catch(\Exception $e)
         {
        	 die('Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode() . "\n" . $curlError);
         }

        $response = json_decode($out, true);
        return $response;
    }
}