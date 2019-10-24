
<?php

function openConnection()
{
    $dbhost = 'localhost';
    $dbuser = 'admin';
    $dbpass = 'admin@123';
    $db = 'mailerlite';
    $conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

    if ($conn->connect_error) {
        die('Connect Error, '.$conn->connect_errno.': '.$conn->connect_error);
    }

    return $conn;
}

function closeConnection($conn): void
{
    $conn->close();
}

?>
