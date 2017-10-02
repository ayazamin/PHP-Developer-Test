<?php

require "Database.php";

$database = new \database\Database();
$insertData = $database->insertData();

if($insertData){
    $searchSpec = json_decode(file_get_contents("php://input"), true);
    $data = $database->fetchData($searchSpec);
    header('Content-Type: application/json');
    $data = json_encode($data);
    echo $data;
}

