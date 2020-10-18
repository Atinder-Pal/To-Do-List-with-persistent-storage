
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
    $result->close();  
    return $data;
}

function insertTask( mysqli $db, string $task_name, string $due_date, int $category_id){
    if( $task_name !='' && $due_date !='' && $category_id !='' ){
        // Citation
        // Following code referenced from Classroom Practice by Tammy Valgardson
        $insert = $db->prepare( "INSERT INTO Task( TaskID, CategoryID, TaskName, DueDate, IsComplete )
        VALUES( NULL, ?,?,?,0 )");
        if( $insert ){
            if( $insert->bind_param("iss", $category_id, $task_name, $due_date) ){
                if( $insert->execute() ){
                    $message = "Task Added to To-Do List";
                }
                else{
                    exit("There was a problem executing insert stmt");
                }
            }
            else{
                exit("There was a problem binding param to insert stmt");
            }
        }
        else {
            exit("There was a problem with the prepare statement");
        }
        $insert->close();
    }
    // End Citation     
    return $message;    
}

function isDuplicate( mysqli $db, string $task_name, string $due_date, int $category_id ){       
    if( $task_name !=''){    
        $message = FALSE;    
        $sql = $db->prepare( "SELECT * FROM Task WHERE TaskName = ? AND CategoryID = ? AND Duedate = ? AND IsComplete IS NOT TRUE" );
        if( $sql ){
            if( $sql->bind_param( "sis", $task_name, $category_id, $due_date ) ){
                if( $sql->execute() ){
                    $result = $sql->get_result();
                    if($result->num_rows > 0){
                        $message = TRUE;
                    }
                }
                else{
                    exit("There was a problem executing insert stmt");
                }
            }
            else{
                exit("There was a problem binding param to insert stmt");
            }
        }
        else {
            exit("There was a problem with the prepare statement");
        }
        $sql->close();
    }
    // End Citation     
    return $message; 
}

function isDuplicateCategory( mysqli $db, string $category_name ){       
    if( $category_name !=''){    
        $message = FALSE;    
        $sql = $db->prepare( "SELECT * FROM Category WHERE CategoryName = ?" );
        if( $sql ){
            if( $sql->bind_param( "s", $category_name ) ){
                if( $sql->execute() ){
                    $result = $sql->get_result();
                    if($result->num_rows > 0){
                        $message = TRUE;
                    }
                }
                else{
                    exit("There was a problem executing stmt");
                }
            }
            else{
                exit("There was a problem binding param to stmt");
            }
        }
        else {
            exit("There was a problem with the prepare statement");
        }
        $sql->close(); 
    }
    // End Citation       
    return $message; 
}


function displayActiveList( mysqli $db ){
    $data = [];
    // Citation
    // https://www.w3schools.com/sql/func_sqlserver_datediff.asp
    // Learnt about DATEDIFF() from above source
    $sql = "SELECT TaskID, CategoryID, TaskName, DueDate, CategoryName 
    FROM Task 
    INNER JOIN Category USING (CategoryID)
    WHERE IsComplete IS NOT TRUE
    AND (SELECT DATEDIFF(DueDate,Now()))>=0";
    // End Citation
    
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
    $result->close();      
    return $data;
}


function displayOverdueList( mysqli $db ){
    $data = [];
    // Citation
    // https://www.w3schools.com/sql/func_sqlserver_datediff.asp
    // Learnt about DATEDIFF() from above source
    $sql = "SELECT TaskID, CategoryID, TaskName, DueDate, CategoryName 
    FROM Task 
    INNER JOIN Category USING (CategoryID)
    WHERE IsComplete IS NOT TRUE
    AND (SELECT DATEDIFF(DueDate,Now()))<0";
    // End Citation

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
    $result->close(); 
    return $data;
}

function setCompletedStatus(mysqli $db, array $completed_tasks ){
    $message = null;
    if( $completed_tasks !=['']){
        // Citation
        // https://www.w3schools.com/php/func_string_implode.asp
        // https://stackoverflow.com/questions/920353/can-i-bind-an-array-to-an-in-condition/36070527#36070527
        // Learnt about implode() function to unpack an array with desired separator
        $condition = implode(",",$completed_tasks);
        // End Citation
        $sql = "UPDATE Task SET IsComplete = 1 WHERE TaskID IN (".$condition.") ";
        $result = $db->query($sql);

        //Check if the query was executed successfully
        // Hint: if it is not executed successfully- it will return False
        if( !$result ) {
            echo "Something went wrong with the update query";
            exit();
        }
        //If query returned any affected rows
        // citation
        // https://stackoverflow.com/questions/8356845/php-mysql-get-number-of-affected-rows-of-update-statement
        if($db->affected_rows > 0){
            $message="Task(s) added to Completed List" ;
        }
        // End Citation
    } 
    $result->close();    
    return $message;
}

function displayCompletedList( mysqli $db ){
    $data = [];
    // Citation
    // https://www.w3schools.com/sql/func_sqlserver_datediff.asp
    // Learnt about DATEDIFF() from above source
    $sql = "SELECT TaskName, DueDate, CategoryName 
    FROM Task 
    INNER JOIN Category USING (CategoryID)
    WHERE IsComplete IS TRUE";
    // End Citation
    
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
    $result->close(); 
    return $data;
}

function deleteTasks(mysqli $db, array $selected_tasks ){
    $message = null;
    if( $selected_tasks !=['']){
        // Citation
        // https://www.w3schools.com/php/func_string_implode.asp
        // https://stackoverflow.com/questions/920353/can-i-bind-an-array-to-an-in-condition/36070527#36070527
        // Learnt about implode() function to unpack an array with desired separator
        $condition = implode(",",$selected_tasks);
        // End Citation
        $sql = "DELETE FROM Task WHERE TaskID IN (".$condition.") ";
        $result = $db->query($sql);

        //Check if the query was executed successfully
        // Hint: if it is not executed successfully- it will return False
        if( !$result ) {
            echo "Something went wrong with the delete query";
            exit();
        }
        //If query returned any affected rows
        // citation
        // https://stackoverflow.com/questions/8356845/php-mysql-get-number-of-affected-rows-of-update-statement
        if($db->affected_rows > 0){
            $message="Task(s) deleted!" ;
        }
        // End Citation
        $result->close(); 
    }    
    return $message;
}


function insertCategory( mysqli $db, string $category_name){
    if( $category_name !=''){
        // Citation
        // Following code referenced from Classroom Practice by Tammy Valgardson
        $insert = $db->prepare( "INSERT INTO Category( CategoryID, CategoryName )
        VALUES( NULL, ? )");
        if( $insert ){
            if( $insert->bind_param("s", $category_name) ){
                if( $insert->execute() ){
                    $message = "Category Added to To-Do List";
                }
                else{
                    exit("There was a problem executing insert stmt");
                }
            }
            else{
                exit("There was a problem binding param to insert stmt");
            }
        }
        else {
            exit("There was a problem with the prepare statement");
        }
        $insert->close();
    }
    // End Citation     
    return $message;    
}

function editCategory( $db, $category_id, $category_name ){
    $message = null;
    if( $category_id !='' && $category_name !='' ){       
        $edit = $db->prepare( "UPDATE Category SET CategoryName = ? WHERE CategoryID = ? " );
        if( $edit ){
            if( $edit->bind_param("si", $category_name, $category_id) ){
                if( $edit->execute() ){
                    $message = "Category edited in the Category List";
                }
                else{
                    exit("There was a problem executing edit stmt");
                }
            }
            else{
                exit("There was a problem binding param to edit stmt");
            }
        }
        else {
            exit("There was a problem with the prepare statement");
        }
        $edit->close(); 
    }
    // End Citation  
     
    return $message; 
    
}

function deleteCategory( $db, $category_id ){
    $message = null;
    if( $category_id !=['']){
        $delete = $db->prepare( "DELETE FROM Category WHERE CategoryID = ?" );
        if( $delete ){
            if( $delete->bind_param("i", $category_id) ){
                if( $delete->execute() && $db->affected_rows > 0 ){
                    $message = "Category deleted from Category List";
                }
                else{
                    exit("There was a problem executing delete stmt");
                }
            }
            else{
                exit("There was a problem binding param to delete stmt");
            }
        }
        else {
            exit("There was a problem with the prepare statement");
        }       
        $delete->close(); 
    }    
    return $message;
}
    
