<?php
if (!isset($_COOKIE['base_domain'])) {
    header("Location: /front/index.php", true, 301);
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <title>Form
    </title>
    <meta charset='utf-8'>
    <script src="jquery-3.6.0.min.js"></script>
    <script src="form_submit.js"></script>
</head>
<body>
    <div id="api_form">
        <form class="my_form">
            <select name="method" size="1">
                <option value="GET">GET</option>
                <option value="POST">POST</option>
                <option value="PATCH">PATCH</option>
                <option value="DELETE">DELETE</option>
            </select>
            <input name="url" type="text"><br>
            <label>Request body</label><br>
            <textarea rows="10" cols="70" name="body"></textarea><br>
            <input type="submit" value="Send">
        </form>
        
        <label>Response</label><br>
        <div id="textarea_response"></div>
            <textarea class="response" name="response" cols="66" rows="10" readonly=true>

            </textarea>
        </div>
    </div>
</body>
</html>