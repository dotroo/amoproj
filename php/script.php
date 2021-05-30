<!DOCTYPE html>
<html>
    <head>
        <title>Output</title>
    </head>
    <body>
        <?php

        $reqMethod = $_REQUEST['method'];
        $reqURL = $_REQUEST['url'];

        echo $reqMethod . ' ' . $reqURL;
        ?>
    </body>
</html>