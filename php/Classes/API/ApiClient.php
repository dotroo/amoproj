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
        $this->expires = $timestamp;
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
}
?>