<?php
    require_once'constants.php';
    require_once'db.php';

    $completed_list = null;
    $completed_tasks = [];

    $connection = connect( HOST, USER, PASSWORD, DATABASE );
    if($connection instanceof mysqli){
        if ( isset( $_POST['completed_task'] ) ){        
            if ( !empty($_POST[ 'selected_tasks' ] )){
                foreach( $_POST[ 'selected_tasks' ] as $selected_task ){                 
                    $completed_tasks = [...$completed_tasks, filter_var( $selected_task, FILTER_SANITIZE_NUMBER_INT ) ];
                }
                $message = setCompletedStatus( $connection, $completed_tasks );
                //var_dump( $completed_tasks );            
            }         
        }

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
?>

<section>
    <h2>Completed Tasks</h2>
    <?php if($message) echo $message; ?>
    <form action="#" method="POST">
        <table>
            <tr>                
                <th>Category</th> 
                <th>Task</th> 
                <th>Due Date</th> 
            </tr>
            <?php echo $completed_list ?>
        </table>                    
    </form>        
</section>