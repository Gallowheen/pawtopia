<?php
    function execute_prepared_query($q, $params){

        $host = '127.0.0.1';
        $db   = 'pawtopia';
        $charset = 'utf8mb4';
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $user = "root";
        $pwd = "";

        $pdo = new PDO($dsn, $user, $pwd);

        $query = $pdo->prepare($q);
        $query->execute($params);

        $result = $query->fetchAll();

        return $result;
    }
?>