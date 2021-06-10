<?php 

namespace Classes\DB;

class OAuthTable
{
    public function request(string $sql, array $params = NULL) {
        try {
            $connect = BaseDB::getInstance(DBHOST, DBNAME, DBCHARSET, DBLOGIN, DBPASS);
            if (!isset($params)) {
                $stmt = $connect->query($sql);
            } else {
               $stmt = $connect->prepare($sql);
               $stmt->execute($params);
            }
        }
        catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
                die();
        }
        return $stmt;
    }        
}