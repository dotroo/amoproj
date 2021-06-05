<?php

class ApiClient {
    private $access_token;
    private $refresh_token;
    private $expires;

    public function setAccessToken($token){
        this->access_token = $token;
    }

    public function getAccessToken(){
        return this->access_token;
    }

    public function setRefreshToken($token){
        this->refresh_token = $token;
    }

    public function getRefreshToken(){
        return this->refresh_token;
    }

    public function setExpires($timestamp){
        this->expires = $timestamp;
    }

    public function getExpires(){
        return this->expires;
    }
}

?>