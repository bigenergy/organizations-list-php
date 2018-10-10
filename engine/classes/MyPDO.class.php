<?php
/**
 * Created by PhpStorm.
 * User: Big_Energy
 * Date: 09.10.2018
 * Time: 23:59
 */

class MyPDO extends PDO
{

    public function __construct($options = [])
    {
        $configs = include('../config.php');
        $default_options = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];
        $options = array_merge($default_options, $options);
        $dsn = "mysql:host=$configs->host;dbname=$configs->database;charset=utf8";
        parent::__construct($dsn, $configs->username, $configs->pass, $options);

    }
    public function run($sql, $args = NULL)
    {
        if (!$args)
        {
            return $this->query($sql);
        }
        $stmt = $this->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }
}