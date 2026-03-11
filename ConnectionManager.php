<?php
class ConnectionManager
{
    private $connectionString = "mysql:host=localhost;dbname=phpfinal";
    private $username = "YOUR_DB_USERNAME";
    private $password = "YOUR_DB_PASSWORD";
    private $conn;

    //Overwrite the constructor for the object using __construct
    public function __construct()
    {
        $this->conn = null;
    }

    //Create a new connection using PDO, enabling the ability to read out errors messages and exception values
    public function getConnection()
    {
        $this->conn = new PDO($this->connectionString, $this->username, $this->password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $this->conn;
    }

    //Close the connection when done
    public function closeConnection()
    {
        $this->conn = null;
    }
}
