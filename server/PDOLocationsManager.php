<?php

class PDOLocationsManager
{
    private string $serverName;
    private string $userName;
    private string $userPassword;
    private string $databaseName;

    public function __construct($serverName, $userName, $userPassword, $databaseName)
    {
        $this->serverName = $serverName;
        $this->userName = $userName;
        $this->userPassword = $userPassword;
        $this->databaseName = $databaseName;
    }

    public function getWebsitesByCoordinates($currLongitude, $currLatitude)
    {
        try {
            $connection = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );

            $statement = $connection->prepare(
                "SELECT name, website, radius FROM locations WHERE
                                       x1_longitude < :currLongitude
                                  AND x2_longitude > :currLongitude
                                  AND y1_latitude > :currLatitude
                                  AND y2_latitude < :currLatitude
                                  ORDER BY radius + 0 ASC");
            $statement->bindParam(":currLongitude", $currLongitude);
            $statement->bindParam(":currLatitude", $currLatitude);
            $statement->execute();
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $result = $statement->fetchAll();

            if ($result == false) {
                $result = null;
            }
            $connection = null;
        } catch (PDOException $exception) {
            return null;
        }
        return $result;
    }

    public function getWebsitesByName($enteredName)
    {
        try {
            $connection = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );

            $statement = $connection->prepare("SELECT name, website, radius, x1_longitude, x2_longitude, y1_latitude, y2_latitude 
                                        FROM locations WHERE
                                        name = :enteredName");
            $statement->bindParam(":enteredName", $enteredName);
            $statement->execute();
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $result = $statement->fetch();

            if ($result == false) {
                $result = null;
            }

            //saving coordinates of found website
            $x1_long = $result['x1_longitude'];
            $x2_long = $result['x2_longitude'];
            $y1_lat = $result['y1_latitude'];
            $y2_lat = $result['y2_latitude'];

            //checking db for more sites in that location
            $statement = $connection->prepare("SELECT name, website, radius FROM locations WHERE
                                   x1_longitude >= :x1-0.0000001
                              AND x2_longitude <= :x2+0.0000001
                              AND y1_latitude <= :y1+0.0000001
                              AND y2_latitude >= :y2-0.0000001
                              ORDER BY radius + 0 DESC");
            $statement->bindParam(":x1", $x1_long);
            $statement->bindParam(":x2", $x2_long);
            $statement->bindParam(":y1", $y1_lat);
            $statement->bindParam(":y2", $y2_lat);
            $statement->execute();
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $finalResult = $statement->fetchAll();

            if ($finalResult == false) {
                $finalResult = null;
            }

            $connection = null;
        } catch (PDOException $exception) {
            return null;
        }
        return $finalResult;

    }
}