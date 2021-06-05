<?php 

class ApiRequest {
    private $url;
    private $method;
    private $data;

    public function getUrl(){
        return $url;
    }

    public function setUrl($reqUrl){
        $url = $reqUrl;
    }

    public function getMethod(){
        return $method;
    }

    public function setMethod($reqMethod){
        $method = $reqMethod;
    }

    public function setData($reqData){
        $data = $reqData;
    }

    public function getData(){
        return $data;
    }

    function __construct($reqUrl, $reqMethod, $reqData){
        $this->url = $reqUrl;
        $this->method = $reqMethod;
        $this->data = $reqData;
    }
}

?>