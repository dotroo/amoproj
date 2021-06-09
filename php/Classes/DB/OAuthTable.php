<?php

namespace Classes\DB;

class OAuthTable extends BaseDB
{
    public static function getAll() {
        $sql = "SELECT * FROM oauth";
        return self::$instance->query($sql);
    }
    
    public static function getByID($id) {
        $sql = "SELECT * FROM oauth WHERE id= ?";
        return self::$instance->prepare($sql)->execute([$id])->fetch();
    }
}