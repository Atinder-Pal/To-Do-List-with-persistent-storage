<?php
    require_once'constants.php';
    require_once'db.php';

    $categories = null;

    $connection = connect( HOST, USER, PASSWORD, DATABASE );
    if($connection instanceof mysqli){
        if( isset( $_POST[ 'add_category' ] ) ){
            if ( !empty($_POST[ 'new_category' ])){
                $new_sanitized_category = $connection->real_escape_string( $_POST[ 'new_category' ] );
                if( !isDuplicateCategory( $connection, $new_sanitized_category ) ){
                    $message = insertCategory( $connection, $new_sanitized_category );
                }    
                else{
                    $message = "Category is already in the category list!";
                }    
            }
        }






        $fetch_categories = fetchAllCategories( $connection );
        foreach( $fetch_categories as $category ){
            $categories .= sprintf( 
                '<form>
                <input type="hidden" name="category_id_to_be edited" id="category_id_to_be edited" value=%d>
                <input type="text" name="category_to_be edited" id="category_to_be edited" value="%s" autofocus>
                <input type="submit" name="edit_category_button" value="Edit" id="edit_category_button">
                </form>',
                $category[ 'CategoryID' ],
                $category[ 'CategoryName' ] 
            );
        }
    }

?>    
<nav>
    <a href="index.php">HOME</a>
</nav>

<h2>Add a new Category!</h2>
    <?php //if($message) echo $message; ?>
    <form action="#" method="POST">
        <label for="new_category">
            Add new Category:            
            <input id="new_category" type="text" value="" name="new_category" placeholder="New Category" autofocus required>
        </label>
        <input type="submit" name="add_category" value="Add" id="add_category_button"> 
</form>
<section>
<h2>Existing Categories:</h2>
<h3>Make change(s) in any of the following categories and Press the Edit button to submit the change(s) </h3>
    <?php echo $categories ?>
</section>