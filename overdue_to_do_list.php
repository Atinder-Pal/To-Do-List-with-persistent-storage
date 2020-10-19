<?php
    require_once'constants.php';
    require_once'db.php';

    $overdue_list = null;

    $connection = connect( HOST, USER, PASSWORD, DATABASE );
    $overdue_tasks = displayOverdueList($connection);
    foreach( $overdue_tasks as $overdue_task ){
        $overdue_list .= sprintf('
            <tr>
                <td><input type="checkbox" name="selected_tasks[]" value=%d></td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
            </tr>',
            $overdue_task[ 'TaskID' ],
            $overdue_task[ 'CategoryName' ],
            $overdue_task[ 'TaskName' ],
            $overdue_task[ 'DueDate' ]            
        );
    }
    $connection->close();
?>

    <section>
        <h2>Overdue Tasks</h2>
        <?php// if($message) echo $message; ?>
        <form action="#" method="POST">
            <button type="submit" name="completed_task" id="completed_button"><i class="far fa-check-square"></i> Complete</button> 
            <button type="submit" name="delete_task" id="delete_button"><i class="far fa-trash-alt"></i> Delete</button>
            <table>
                <tr>
                    <th><input type="checkbox" disabled></th> 
                    <th>Category</th> 
                    <th>Task</th> 
                    <th>Due Date</th> 
                </tr>
                <?php echo $overdue_list ?>
            </table>
                    
        </form>        
    </section>
</div>