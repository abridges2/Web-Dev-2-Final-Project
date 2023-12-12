<?php

/*******w******** 
    
    Name: Aidan Bridges 
    Date: 2023-11-13
    Description: Create category page for final project wd2.

****************/
require('connect.php');
require('authenticate.php');

// Empty string array for holding any error messages.
$error_msg = '';

// If the submit button is clicked
if(isset($_POST['create_category_submit']))
{
    // Sanitize data before being inserted into posts table.
    $chosen_category_name = filter_input(INPUT_POST, 'category_name', FILTER_SANITIZE_STRING);
    

    if(strlen($chosen_category_name) < 1)
    {
        $error_msg = "The category name has to be more than 1 character";
    }
    else
    {
        // Build the parameterized SQL query and bind to the above sanitized values.
        $query = "INSERT INTO categories (category_name) VALUES (:category_name)";
        $statement = $db->prepare($query);
        
        // Bind values to the parameters
        $statement->bindValue(':category_name', $chosen_category_name);

        // If the query goes through with 0 errors re locate user to index page.
        if($statement->execute())
        {
            header('Location: index.php');
            exit;
        }
        // Error.
        else 
        {
            echo "There was an error submitting data to the database.";
        }
    } 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="create_category.php" method="post">
        <fieldset>
            <?php if(!empty($error_msg)) :?>
                <?= $error_msg?>
            <?php endif ?>
            <p>
                <label for="category_name">Category Name</label>
                <input type="text" name="category_name" id ="category_name">
            </p>
            <p>
                <input type="submit" name="create_category_submit" value="Create">
            </p>
        </fieldset>
    </form>
    
</body>
</html>