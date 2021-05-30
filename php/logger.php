<?php 
    
    function logger($message, array $data, $logFile = "runtime.log"){
        foreach ($data as $key => $val) {
            $message = str_replace("%{$key}%", $val, $message);
        }
        $message .= PHP_EOL;
        return file_put_contents($logFile, $message, FILE_APPEND);

    }
    
?>