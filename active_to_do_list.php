<?php
    require_once'constants.php';
    require_once'db.php';

    $active_list = null;
    $completed_tasks = [];
    $delete_tasks = [];

    $connection = connect( HOST, USER, PASSWORD, DATABASE );
    if ( isset( $_POST['completed_task'] ) ){        
        if ( !empty($_POST[ 'selected_tasks' ] )){
            foreach( $_POST[ 'selected_tasks' ] as $selected_task ){                 
                $completed_tasks = [...$completed_tasks, filter_var( $selected_task, FILTER_SANITIZE_NUMBER_INT ) ];
            }
            $message = setCompletedStatus( $connection, $completed_tasks );
            //var_dump( $completed_tasks );                           
        }         
    }

    if ( isset( $_POST['delete_task'] ) ){        
        if ( !empty($_POST[ 'selected_tasks' ] )){
            foreach( $_POST[ 'selected_tasks' ] as $selected_task ){                 
                $delete_tasks = [...$delete_tasks, filter_var( $selected_task, FILTER_SANITIZE_NUMBER_INT ) ];
            }
            $message = deleteTasks( $connection, $delete_tasks );
            //var_dump( $completed_tasks );                           
        }         
    }

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
            <input type="submit" name="delete_task" value="Delete" id="delete_button">            
        </form>        
    </section>