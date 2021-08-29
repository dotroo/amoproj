<?php

if (isset($_COOKIE['base_domain'])) {
    header("Location: /front/form.php", true, 301);
    exit();
} else {
    print('Please, authorize the app<br>
           <!doctype html>
           <html lang="en">
           <head>
           <title>Auth
           </title>
           <meta charset="utf-8">
           </head>
           <body>
               <script
                   class="amocrm_oauth"
                   charset="utf-8"
                   data-client-id="37859936-9fa2-4b88-b4de-a60d75ba7209"
                   data-title="Button"
                   data-compact="false"
                   data-class-name="className"
                   data-color="default"
                   data-state="state"
                   data-error-callback="functionName"
                   data-mode="popup"
                   src="https://www.amocrm.ru/auth/button.min.js"
            ></script>'
    );
}