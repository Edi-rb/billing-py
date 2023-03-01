<?php
namespace Classes\Conexiones;

    class ConexionSAT{
        const USERNAME="root";
        const PASSWORD="";
        const HOST="localhost";
        const DB="sat";

        public $connection;

        function __construct()
        {
            $this->connection = $this->getConnection();
        }

        private function getConnection(){
            $username = self::USERNAME;
            $password = self::PASSWORD;
            $host = self::HOST;
            $db = self::DB;
            $connection = new \PDO("mysql:dbname=$db;host=$host", $username, $password);
            return $connection;
        }

        public function queryList($sql, $args){
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($args);
            $json = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return json_encode($json);
        }
        
    }
?>
