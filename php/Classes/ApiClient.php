<?php 

class BaseApiRequest {
    protected $url;
    protected $method;
    protected $data;
    protected $response;
    protected $headers;

    public function getResponse(){
        return $this->response;
    }

    public function __construct($reqUrl, $reqMethod, $reqData){
        $this->url = $reqUrl;
        $this->method = $reqMethod;
        $this->data = $reqData;
    }

    public function initRequest($userAgent, $headers){
        $curl = curl_init(); //Сохраняем дескриптор сеанса cURL
        /** Устанавливаем необходимые опции для сеанса cURL  */
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl,CURLOPT_USERAGENT, $userAgent);
        curl_setopt($curl,CURLOPT_URL, $url);
        curl_setopt($curl,CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl,CURLOPT_HEADER, false);
        curl_setopt($curl,CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
        $out = curl_exec($curl); //Инициируем запрос к API и сохраняем ответ в переменную
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        /** Теперь мы можем обработать ответ, полученный от сервера. Это пример. Вы можете обработать данные своим способом. */
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
    }
}
?>