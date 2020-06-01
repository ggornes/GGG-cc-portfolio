<?php
/*******************************************************
 * Project:     ggg-cc-portfolio
 * File:        Database.php
 * Author:      GerardoG
 * Date:        2020-05-31
 * Version:     1.0.0
 * Description:
 *******************************************************/

/**
 * Class Database
 */
class Database

{

    private $dbType = 'mysql';
    private $dbName = 'cc_store';
    private $dbHost = 'localhost';
    private $dbPort = '3306';
    private $dbCharSet = 'utf8';
    private $dbUser = 'cc_store_user';
    private $dbPassword = 'Secret1';
    private $dsn = '';
    public $connection;

    /**
     * Database Consturctor
     */
    public function __construct()
    {
        $this->dsn = "{$this->dbType}:dbname={$this->dbName};"."host={$this->dbHost};port={$this->dbPort}};charset={$this->dbCharSet}";
    }

    /**
     * Create a DB connection
     * @return PDO
     */
    public function getConnection()
    {
        $this->connection = new PDO($this->dsn, $this->dbUser, $this->dbPassword);

        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        return $this->connection;
    }


}