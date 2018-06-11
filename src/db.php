<?php

require_once('dbconfig.php');


/*
 * query function
 * does SQL query and returns if needed.
*/

function query($querystring, $params = [], $needsOutput=false)
{
    $conn = new PDO("mysql:host=".SQLSERVERIP.";dbname=".SQLSERVERDBNAME.";charset=utf8", SQLSERVERUSER,SQLSERVERPSSWD);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (!($stmt = $conn->prepare($querystring)))
    {
        die($conn->errorInfo());
    }

    foreach($params as $param => $value)
    {
        if (is_numeric($value))
            $stmt->bindParam($param,$value,PDO::PARAM_INT);
        else
            $stmt->bindParam($param,$value);
    }

    $stmt->execute();

    if ($needsOutput)
    {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return 969;
}