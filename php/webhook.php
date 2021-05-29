<?php

$json = file_get_contents('php://input');

if ($json != NULL)
{
    echo $json;
}
else
{
    echo "no hook";
}
?>