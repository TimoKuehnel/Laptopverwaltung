<?php

class Datenbank
{

    private $host;
    private $dbname;
    private $adminname;
    private $adminpassword;

    public function __construct(string $configFile = __DIR__ . '/utility/dbConfig.php')
    {
        $config = require $configFile;
        $this->host = $config['host'];
        $this->dbname = $config['dbname'];
        $this->adminname = $config['adminname'];
        $this->adminpassword = $config['adminpassword'];
    }

    public function connect(): PDO
    {
        $dns = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        try {
            $pdo = new PDO($dns, $this->adminname, $this->adminpassword, $options);
            return $pdo;
        } catch (PDOException $e) {
            die("Verbindung fehlgeschlagen: " . $e->getMessage());
        }
    }
}

?>