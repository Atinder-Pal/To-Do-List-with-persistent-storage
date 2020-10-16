<?php
    require_once'constants.php';
    require_once'db.php';

    $category_select_options = null;
    $category_sql = "SELECT * FROM Category";

    $connection = connect( HOST, USER, PASSWORD, DATABASE );
    //test if the connection is established to the database
    // Citation
    // https://github.com/pfwd/how-to-code-well-tutorials/blob/master/tutorials/php/php-mysql/index.php
    // Referenced above github repo to check syntax for confirming an object of a class is returned
    if($connection instanceof mysqli){

        // Get all the categories from Category Table in db
        $categories = fetchAllCategories( $connection );
        foreach( $categories as $category ){
            $category_select_options .= sprintf( '<option value="%i">%s</option>',
                $category[ 'CategoryID' ],
                $category[ 'CategoryName' ] 
            );
        }
    }
?>
    <section>
        <h2>Add new Task to your List!</h2>
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
                <input id="due_date" type="date" name="due_date" min="<?php echo date("Y-m-d"); ?>" autofocus required>
                <?php//End Citation ?>
            </label>
            <label for="category">
                Task Category:            
                <select name="species" id="species" required>
                    <option value="">Select a category</option>
                    <?php echo $category_select_options; ?>
                </select>
            </label>
            <input type="submit" name="add_task" value="Add to List" id="add_task_button">            
        </form>        
    </section>

