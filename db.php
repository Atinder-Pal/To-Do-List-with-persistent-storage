
<?php
//Citation
//https://www.youtube.com/watch?v=sRvxYlCmwis
//https://github.com/pfwd/how-to-code-well-tutorials/blob/master/tutorials/php/php-mysql/db.php
//Above source shows how we can separate concerns by having all db operations in functions in a single file 
//End Citation
function connect($dbHost, $dbName, $dbUsername, $dbPassword){
    $connection = new mysqli(
        $dbHost,
        $dbUsername,
        $dbPassword,
        $dbName
    );
    if($connection->connect_error){
        die("Cannot connect to database: \n"
            . $connection->connect_error . "\n"
            . $connection->connect_errno
        );
    }
    return $connection;
}