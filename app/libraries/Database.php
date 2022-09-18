<?php

/**
 * PDO Database Adapter class
 * Connects to the database
 * Create prepared statements
 * Bind values
 * Return rows and results
 */

class Database
{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASSWORD;
    private $dbname = DB_NAME;

    private $dbh;
    private $stmt;
    private $error;

    public function __construct()
    {
        // SET DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => true,
        );

        //Create PDO instance
        try {
            //code...
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
            $this->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            //throw $th
            $this->error = $e->getMessage();
            echo $this->error;
            exit();
        }
    }

    //Prepare statements with query
    public function query($sql)
    {
        $this->stmt = $this->dbh->prepare($sql);
    }

    // Bind Values
    public function bind($parameter, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
                    break;
            }
        }

        $this->stmt->bindValue($parameter, $value, $type);
    }

    //Execute the prepared statement
    public function execute()
    {
        return $this->stmt->execute();
    }

    // Get result set as array of object
    public function resultSet()
    {

        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Get single record as object
    public function single()
    {

        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    // Get row count
    public function rowCount()
    {
        return $this->stmt->rowCount();
    }
}
