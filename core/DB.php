<?php

class DB
{
    public $dbh; // handle of the db connexion
    private static $instance;

    private function __construct()
    {
        // building data source name from config        
        $dsn = 'mysql:host=' . DB_HOST .
               ';dbname='    . DB_NAME .
               ';port='      . DB_PORT .
               ';connect_timeout=15';
        
        // getting DB user from config                
        $user = DB_USERNAME;
        // getting DB password from config                
        $password = DB_PASSWORD;

        $this->dbh = new PDO($dsn, $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES 'utf8'"));
        //$this->dbh = new PDO($dsn, $user, $password);
    }

    public static function getInstance()
    {
        if (!isset(self::$instance))
        {
            $object = __CLASS__;
            self::$instance = new $object;
        }
        return self::$instance;
    }

    // others global functions
}

?>
