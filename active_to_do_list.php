<?php
    require_once'constants.php';
    require_once'db.php';

    $active_list = null;
    $completed_tasks = [];
    $delete_tasks = [];
    
    if ( isset( $_POST['completed_task'] ) ){        
        if ( !empty($_POST[ 'selected_tasks' ] )){
            foreach( $_POST[ 'selected_tasks' ] as $selected_task ){                 
                $completed_tasks = [...$completed_tasks, filter_var( $selected_task, FILTER_SANITIZE_NUMBER_INT ) ];
            }
            $connection = connect( HOST, USER, PASSWORD, DATABASE );
            $message = setCompletedStatus( $connection, $completed_tasks );
            //var_dump( $completed_tasks );
            $connection->close();                              
        }         
    }

    if ( isset( $_POST['delete_task'] ) ){        
        if ( !empty($_POST[ 'selected_tasks' ] )){
            foreach( $_POST[ 'selected_tasks' ] as $selected_task ){                 
                $delete_tasks = [...$delete_tasks, filter_var( $selected_task, FILTER_SANITIZE_NUMBER_INT ) ];
            }
            $connection = connect( HOST, USER, PASSWORD, DATABASE );
            $message = deleteTasks( $connection, $delete_tasks );
            //var_dump( $completed_tasks );   
            $connection->close();                         
        }         
    }
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
    $connection->close();
?>
<div class="flex">
<section>
        <h2>Active To-Do List</h2>
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
                <?php echo $active_list ?>
            </table>
                        
        </form>        
    </section>

