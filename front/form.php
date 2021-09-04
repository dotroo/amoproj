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
    <script src="entityCheck.js"></script>
</head>
<body>
    <div id="api_form">
        <form class="my_form">
            <div>
                <select name="method" size="1">
                    <option value="GET">GET</option>
                    <option value="POST">POST</option>
                    <option value="PATCH">PATCH</option>
                    <option value="DELETE">DELETE</option>
                </select>
                <select name="entity" size="1" onchange="entityCheck(this);">
                    <option value="/api/v4/account">account</option>
                    <option value="/api/v4/leads">leads</option>
                    <option value="/api/v4/contacts">contacts</option>
                    <option value="/api/v4/companies">companies</option>
                    <option value="/api/v4/customers">customers</option>
                    <option value="/api/v4/catalogs">catalogs</option>
                    <option value="/api/v4/tasks">tasks</option>
                    <option value="/api/v4/events">events</option>
                    <option value="/api/v4/notes">notes</option>
                    <option value="/api/v4/users">users</option>
                    <option value="custom">custom</option>
                </select>
                <div id="customInput" style="display:none;">
                    <input name="url" type="text" size=50 placeholder="request uri">
                </div>
                <div id="entitySelected" style="display:inline;">
                    <input name="params" type="text" size=50 placeholder="request params">
                </div><br>
                <label>Request body</label><br>
                <textarea rows="10" cols="70" name="body"></textarea><br>
                <input type="submit" value="Send">
            </div>
        </form>
        
        <label>Response</label><br>
        <div id="textarea_response"></div>
            <textarea class="response" name="response" cols="66" rows="10" readonly=true>

            </textarea>
        </div>
    </div>
</body>
</html>