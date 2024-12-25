<?php

class Database
{
    private $db_host = 'localhost';
    private $db_user = 'root';
    private $db_password = '';
    private $db_name = 'todo_db';
    public $conn;

    public function connect()
    {
        $this->conn = null;

        // Enable mysqli exceptions for better error handling
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            // Create connection
            $this->conn = new mysqli($this->db_host, $this->db_user, $this->db_password, $this->db_name);
        } catch (mysqli_sql_exception $error) {
            // Log or handle the error without stopping the execution
            echo "Database connection failed: " . $error->getMessage();
        }

        return $this->conn;
    }
}
