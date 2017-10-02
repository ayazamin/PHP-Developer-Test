<?php

namespace database;

require_once 'vendor/autoload.php';
require "Utils.php";

class Database
{
    private $conn;
    private $host;
    private $user;
    private $password;
    private $database;
    private $port;


    /**
     * database connection
    */
    public function __construct()
    {
        $this->conn = false;
        $this->host = 'localhost'; //hostname
        $this->user = 'root'; //username
        $this->password = 'root'; //password
        $this->database = 'assignment'; //name of your database
        $this->port = '3306';
        $this->connect();
    }


    public function __destruct()
    {
        $this->conn = null;
    }


    /**
     * connect to database
     * @return boolean returns true on success or false on failure
     */
    public function connect()
    {
        mysqli_report(MYSQLI_REPORT_STRICT);

        if (!$this->conn) {
            try {
                $this->conn = new \mysqli($this->host, $this->user, $this->password, $this->database);
                $this->conn->set_charset("utf8");

            } catch (\Exception $e) {
                die('Connection error: ' . $e->getMessage());
            }
        }
        return $this->conn;
    }


    /**
     * create the tasks table
     * @return boolean returns true on success or false on failure
     */
    public function createTaskTable()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS tasks (
                row_id     INT AUTO_INCREMENT PRIMARY KEY,
                name     VARCHAR (100)        DEFAULT NULL,
                owner     VARCHAR (100)        DEFAULT NULL,
                description VARCHAR (400)        DEFAULT NULL
            );
            ";
        return $this->conn->query($sql) or exit('creating table error: ' . $this->conn->error);
    }


    /**
     * insert api data to the tasks table
     * @return boolean returns true on success or false on failure
     */
    public function insertData()
    {
        $dbCreated = $this->createTaskTable();

        if ($dbCreated) {
            $tableData = $this->conn->query("Select * from tasks");

            if (!$tableData->num_rows) {
                try {
                    $client = new \Github\Client();
                    $response = $client->api('repository')->find('symfony');
                    $data = $response['repositories'];

                    if (is_array($data)) {
                        foreach ($data as $value) {
                            $name = trim($value['name']);
                            $owner = trim($value['owner']);
                            $description = \Utils::removeSpecialChar(trim($value['description']));
                            $data = "'$name', '$owner', '$description'";
                            $sql = "INSERT INTO tasks (name, owner, description) VALUES ($data);";

                            $this->conn->query($sql) or exit('inserting table error: ' . $this->conn->error);
                        }
                        return true;
                    }
                } catch (\Exception $e) {
                    die('error: ' . $e->getMessage());
                }
            } else {
                return true;
            }
        } else {
            return false;
        }

    }


    public function fetchData($searchSpec){

        $pageSize = $searchSpec['pageSize'];
        $offset = ($searchSpec['currentPage'] - 1) * $searchSpec['pageSize'];

        $data = [];

        $predicate = "";
        if($searchSpec['filter']){
            $predicate = "where name LIKE '%".$searchSpec['filter']."%'";
        }
        $countData = $this->conn->query("Select count(*) as count from tasks ".$predicate)->fetch_all(MYSQLI_ASSOC);
        $data['totalRecords'] = $countData[0]['count'];
        $data['data'] = $this->conn->query("Select * from tasks ".$predicate." LIMIT ".$offset.", ".$pageSize)->fetch_all(MYSQLI_ASSOC);

        return $data;

    }

}