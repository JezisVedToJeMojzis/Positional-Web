<?php
header('Content-Type: application/json; charset=utf-8');
include_once "../PDOLocationsManager.php";

$config = require_once "../config.php";
$serverName = $config["host"];
$userName = $config["username"];
$userPassword = $config["password"];
$databaseName = $config["dbname"];

if (isset($_GET['currentLongitude']) && isset($_GET['currentLatitude'])) {
    $pdoLocationsManager = new PDOLocationsManager($serverName, $userName, $userPassword, $databaseName);
    $result = $pdoLocationsManager->getWebsitesByCoordinates($_GET['currentLongitude'], $_GET['currentLatitude']);
    if ($result != null) {
        header("HTTP/1.1 200 OK");
        echo json_encode($result);
    } else {
        header("HTTP/1.1 404 Not Found");
        echo "{} 404";
    }
} else {
    header("HTTP/1.1 404 Not Found");
    echo "{} unset";
}