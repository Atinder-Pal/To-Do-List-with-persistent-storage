<?php
    require_once'constants.php';
    require_once'db.php';

    $categories = null;
    $message = null;
        if( isset( $_POST[ 'add_category' ] ) ){
            if ( !empty($_POST[ 'new_category' ])){
                $connection = connect( HOST, USER, PASSWORD, DATABASE );
                $new_sanitized_category = $connection->real_escape_string( $_POST[ 'new_category' ] );
                if( !isDuplicateCategory( $connection, $new_sanitized_category ) ){
                    $message = insertCategory( $connection, $new_sanitized_category );
                }    
                else{
                    $message = "Category is already in the category list!";
                }    
                $connection->close(); 
            }
        }

        if( isset( $_POST[ 'edit_category' ] ) ){
            //Test if $_POST has form data
            // echo '<pre>';
            // print_r($_POST);
            // echo '</pre>';
            if ( !empty($_POST[ 'category_id_to_be_edited' ]) && !empty($_POST[ 'category_to_be_edited' ]) ){
                $connection = connect( HOST, USER, PASSWORD, DATABASE );
                $category_name = $connection->real_escape_string( $_POST[ 'category_to_be_edited' ] );
                if( !isDuplicateCategory( $connection, $category_name ) ){
                    $category_id = filter_var($_POST['category_id_to_be_edited'], FILTER_SANITIZE_NUMBER_INT);
                    $message = editCategory( $connection, $category_id, $category_name );
                }    
                else{
                    $message = "Category is already in the category list!";
                } 
                $connection->close();  
            }
        }

        if( isset( $_POST[ 'delete_category' ] ) ){
            //Test if $_POST has form data
            // echo '<pre>';
            // print_r($_POST);
            // echo '</pre>';
            if ( !empty($_POST[ 'category_id_to_be_edited' ]) && !empty($_POST[ 'category_to_be_edited' ]) ){
                $category_id = filter_var($_POST['category_id_to_be_edited'], FILTER_SANITIZE_NUMBER_INT);
                $connection = connect( HOST, USER, PASSWORD, DATABASE );
                $message = deleteCategory( $connection, $category_id );
                $connection->close();                  
            }
        }

        $connection = connect( HOST, USER, PASSWORD, DATABASE );
        $fetch_categories = fetchAllCategories( $connection );
        foreach( $fetch_categories as $category ){
            $categories .= sprintf( 
                '<form action="#" method="POST">
                <input type="hidden" name="category_id_to_be edited" id="category_id_to_be edited" value=%d>
                <input type="text" name="category_to_be edited" id="category_to_be edited" value="%s" autofocus>
                <input type="submit" name="edit_category" value="Edit" id="edit_category_button">
                <input type="submit" name="delete_category" id="delete_category_button" value="X">
                </form>',
                $category[ 'CategoryID' ],
                $category[ 'CategoryName' ] 
            );
        }
        $connection->close();   

?>    
<article>
<nav>
    <a href="index.php">HOME</a>
</nav>
<section>
<h2>Add a new Category!</h2>
    <?php if($message) echo $message; ?>
    <form action="#" method="POST">
        <label for="new_category">
            Add new Category:            
            <input id="new_category" type="text" value="" name="new_category" placeholder="New Category" autofocus required>
        </label>
        <input type="submit" name="add_category" value="Add" id="add_category_button"> 
    </form>
</section>

<section>
<h2>Existing Categories:</h2>
<h3>Make change(s) in any of the following categories and Press the Edit button to submit the change(s) </h3>
    <?php echo $categories ?>
</section>
</article>
