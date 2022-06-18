<?php
header('Content-Type: application/json; charset=utf-8');
require_once "../PDOLocationsManager.php";

$config = require_once "../config.php";
$serverName = $config["host"];
$userName = $config["username"];
$userPassword = $config["password"];
$databaseName = $config["dbname"];

if (isset($_GET['name'])) {
    $pdoLocationsManager = new PDOLocationsManager($serverName, $userName, $userPassword, $databaseName);
    $result = $pdoLocationsManager->getWebsitesByName($_GET['name']);
    if ($result != null) {
        header("HTTP/1.1 200 OK");
        echo json_encode($result);
    } else {
        header("HTTP/1.1 404 Not Found");
        echo "{} 404";
    }
}else {
    header("HTTP/1.1 404 Not Found");
    echo "{}";
}



