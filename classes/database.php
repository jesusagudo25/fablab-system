<?php

    class Database{
        private $host;
        private $db;
        private $user;
        private $password;
        private $charset;

        public function __construct(){
            $this->host = constant('HOST');
            $this->db = constant('DB');
            $this->user = constant('USER');
            $this->password = constant('PASSWORD');
            $this->charset = constant('CHARSET');
        }

        public function connect(){
            try {
                $connection = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
                $options=[
                    PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => FALSE
                ];

                $PDO = new PDO($connection,$this->user,$this->password,$options);

                return $PDO;

            } catch (PDOException $e) {
                echo('Error connection: '. $e->getMessage());
            }
        }
    }
?>