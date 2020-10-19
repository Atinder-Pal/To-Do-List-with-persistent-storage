<?php
    require_once'constants.php';
    require_once'db.php';     

    $connection = connect( HOST, USER, PASSWORD, DATABASE );
    if($connection instanceof mysqli){        
        $finished_tasks = displayCompletedList($connection);
        foreach( $finished_tasks as $finished_task ){
            $completed_list .= sprintf('
                <tr>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                </tr>',            
                $finished_task[ 'CategoryName' ],
                $finished_task[ 'TaskName' ],
                $finished_task[ 'DueDate' ]            
            );
        }
    }
    $connection->close();

?>

<section>
    <h2>Completed Tasks</h2>
    <?php //if($message) echo $message; ?>    
        <table>
            <tr>                
                <th>Category</th> 
                <th>Task</th> 
                <th>Due Date</th> 
            </tr>
            <?php echo $completed_list ?>
        </table>                           
</section>