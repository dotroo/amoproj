<!DOCTYPE html>
<html>
    <head>
        <title>Output</title>
    </head>
    <body>
        <?php

            $request = new ApiRequest($_REQUEST['method'], $_REQUEST['url'], $_REQUEST['data']);        

        ?>
    </body>
</html>