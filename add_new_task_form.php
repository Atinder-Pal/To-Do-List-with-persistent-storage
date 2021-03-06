


<?php
    //Citation
    //https://stackoverflow.com/questions/15359451/xampp-php-date-function-time-is-different-from-local-machine-time
    // Thanks to Aaron Barthel who pointed me to above source to fix the error as my Xampp server returned different date
    // time function than my local machine time
    date_default_timezone_set('America/Edmonton'); 
    //End Citation
    
    require_once'constants.php';
    require_once'db.php';
    
    $category_select_options = null;
    $message = null;
    $completed_list = null;
    $completed_tasks = [];

    $connection = connect( HOST, USER, PASSWORD, DATABASE );
    //test if the connection is established to the database
    // Citation
    // https://github.com/pfwd/how-to-code-well-tutorials/blob/master/tutorials/php/php-mysql/index.php
    // Referenced above github repo to check syntax for confirming an object of a class is returned
    if($connection instanceof mysqli){
        // ===============Populate Select Category Menu===========================
        // Get all the categories from Category Table in db
        $categories = fetchAllCategories( $connection );
        foreach( $categories as $category ){
            $category_select_options .= sprintf( '<option value="%d">%s</option>',
                $category[ 'CategoryID' ],
                $category[ 'CategoryName' ] 
            );
        }
    }
    $connection->close();
        // ==========================================================================

        // ==============Insert the entered task into Task table in db===============
        
        //Test if the form info is transferred to $_POST on submission
        if( isset ($_POST[ 'add_task' ]) ){
            // echo '<pre>';
            // print_r($_POST);
            // echo '</pre>';

            if ( !empty($_POST[ 'new_task' ]) && !empty($_POST[ 'due_date' ]) && !empty($_POST[ 'category' ]) ){
                $connection = connect( HOST, USER, PASSWORD, DATABASE );
                //Escape User Input
                // Citation 
                // https://www.w3schools.com/php/func_mysqli_real_escape_string.asp
                // https://www.tutorialrepublic.com/php-tutorial/php-filters.php
                // https://www.w3schools.com/php/filter_sanitize_number_int.asp
                // Above sources talk about methods to validate and sanitize user input
                $new_task = $connection->real_escape_string( $_POST[ 'new_task' ] );
                $due_date = $connection->real_escape_string( $_POST[ 'due_date' ] );
                $category = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
                if( !isDuplicate( $connection, $new_task, $due_date, $category ) ){                    
                    //End Citation
                    $message = insertTask( $connection, $new_task, $due_date, $category );
                }    
                else{
                    echo "Task is already added to the list!";
                } 
                $connection->close();          
            }           
        }
        // ==========================================================================            
?>

    <section>    
        <h2>Add new Task to your List!</h2>
        <?php //if($message) echo $message; ?>
        <form action="#" method="POST">
            <label for="new_task">
                Add new task:            
                <input id="new_task" type="text" value="" name="new_task" placeholder="New Task" autofocus required>
            </label>
            <label for="due_date">
                Due date: 
                <?php//Citation ?>           
                <?php//https://stackoverflow.com/questions/43274559/how-do-i-restrict-past-dates-in-html5-input-type-date ?>           
                <?php//Above source showed how to set min attribute to current date ?> 
                <input id="due_date" type="date" name="due_date" min="<?php echo date("Y-m-d"); ?>" required>
                <?php//End Citation ?>
            </label>
            <label for="category">
                Task Category:            
                <select name="category" id="category" required >
                    <option value="">Select a category</option>
                    <?php echo $category_select_options; ?>
                </select>
            </label>
            <!-- Citation
            https://stackoverflow.com/questions/2825856/html-button-to-not-submit-form
            Fixed error by specifying type=button inside button to avoid it submitting the form
            -->
            <button type="button" id ="add_edit_category_button" name="add_edit_category_button" onclick = showEditPage()><i class="fas fa-pen"></i>Category</button>   
            <!-- End Citation -->
            <!-- Citation
            https://stackoverflow.com/questions/9376192/add-icon-to-submit-button-in-twitter-bootstrap-2
            -->
            <button type="submit" name="add_task" id="add_task_button"> <i class="fas fa-plus"></i> Add Task</button>
            <!-- End Citation -->          
        </form> 
        
    </section>

