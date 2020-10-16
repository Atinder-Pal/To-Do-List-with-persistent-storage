
<?php
//Citation
//https://www.youtube.com/watch?v=sRvxYlCmwis
//https://github.com/pfwd/how-to-code-well-tutorials/blob/master/tutorials/php/php-mysql/db.php
//Above source shows how we can separate concerns by having all db operations in functions in a single file 
//End Citation
function connect($dbHost, $dbUsername, $dbPassword, $dbName){
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

function fetchAllCategories( mysqli $db ){
    $data = [];
    $sql = "SELECT * FROM Category";
    $result = $db->query($sql);

    //Check if the query was executed successfully
    // Hint: if it is not executed successfully- it will return False
    if( !$result ) {
        echo "Something went wrong with the category query";
        exit();
    }
    //If query returned any resultset
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){ //we can also use fetch_object() to return resultset as an object
            $data[] = $row;
        }
    }

    return $data;
}