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
    <?php //echo $categories ?>
</section>