<?php
    require_once'constants.php';
    require_once'db.php';

    $active_list = null;

    $connection = connect( HOST, USER, PASSWORD, DATABASE );
    $active_tasks = displayActiveList($connection);
    foreach( $active_tasks as $active_task ){
        $active_list .= sprintf('
            <tr>
                <td><input type="checkbox" name="selected_tasks[]" value=%d></td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
            </tr>',
            $active_task[ 'TaskID' ],
            $active_task[ 'CategoryName' ],
            $active_task[ 'TaskName' ],
            $active_task[ 'DueDate' ]            
        );
    }
?>
<section>
        <h2>Active To-Do List</h2>
        <?php// if($message) echo $message; ?>
        <form action="#" method="POST">
            <table>
                <tr>
                   <th><input type="checkbox" disabled></th> 
                   <th>Category</th> 
                   <th>Task</th> 
                   <th>Due Date</th> 
                </tr>
                <?php echo $active_list ?>
            </table>
            <input type="submit" name="completed_task" value="Completed!" id="completed_button">            
        </form>        
    </section>