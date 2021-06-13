<?php

use Classes\API\ApiClient;
use Classes\API\OauthApiClient;
use Classes\DB\OAuthTable;

$apiClient = new ApiClient;
$getTokenFromDB = new OauthTable;

switch ($_POST['method']) {
    case "GET":
        $method = "GET";
        $apiClient->curlRequest($_POST['url'], );
        break;
    case "POST":
        break;
    case "PATCH":
        break;
    case "DELETE":
        break;
    default:
        break;
}
    
?>