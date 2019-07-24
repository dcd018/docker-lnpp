<!DOCTYPE html>
<html>
<strong>pg_connect():</strong>
<?php
    list('DB_HOST' => $host, 'DB_DATABASE' => $db, 'DB_USER' => $user, 'DB_PASSWORD' => $password) = $_ENV;
    $params = [
        'host'     => $host,
        'dbname'   => $db,
        'user'     => $user,
        'password' => $password,
    ];
    $connection = pg_connect(sprintf("host=%s dbname=%s user=%s password=%s", $host, $db, $user, $password));
    if($connection) {
       echo 'Database connection established using pg_connect()';
    } else {
        echo 'Database connection could not be established established using pg_connect()';
    }
?>
<br />
<strong>PDO_PGSQL:</strong>
<?php
    $dsn = sprintf('pgsql:%s', http_build_query($params, null, ';'));
    try {
        $conn = new PDO($dsn);
        if ($conn) {
            echo 'Database connection established using PDO_PGSQL';
        } else {
            echo 'Database connection could not be established established using PDO_PGSQL';
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
?>
</html>