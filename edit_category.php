<?php
/*******w******** 
    
    Name: Aidan Bridges 
    Date: 2023-11-10
    Description: Edit category page for the final project wd2

****************/

require('connect.php');
require('authenticate.php');

$error_msg = '';

if ($_POST && isset($_POST['category_id']) && isset($_POST['update_button'])) {
    $category_id = filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT);
    $category_name = filter_input(INPUT_POST, 'category_name', FILTER_SANITIZE_STRING);

    if (strlen($category_name) < 1) 
    {
        $error_msg = "The category name must be more than 1 character.";
    } 
    else 
    {
        $query = "UPDATE categories SET category_name = :category_name WHERE category_id = :category_id";
        $statement = $db->prepare($query);
        $statement->bindValue(':category_name', $category_name);
        $statement->bindValue(':category_id', $category_id, PDO::PARAM_INT);

        if ($statement->execute()) 
        {
            header('Location: categories.php'); // Redirect to the categories page.
            exit;
        } 
        else 
        {
            echo "There was an error updating the category.";
        }
    }
} 
elseif (isset($_POST['delete_button'])) 
{
    $query = "DELETE FROM categories WHERE category_id = :category_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':category_id', $category_id, PDO::PARAM_INT);

    if ($statement->execute()) {
        header('Location: categories.php'); // Redirect to the categories page.
        exit;
    } else {
        echo "There was an error deleting the category.";
    }
}
elseif (isset($_GET['id'])) 
{
    $category_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $category_id = filter_var($category_id, FILTER_VALIDATE_INT);

    $query = "SELECT * FROM categories WHERE category_id = :category_id LIMIT 1";
    $statement = $db->prepare($query);
    $statement->bindValue(':category_id', $category_id, PDO::PARAM_INT);
    $statement->execute();
    $category = $statement->fetch();
}
else 
{
    $category_id = false; // Not updating or retrieving, set the id to false.
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
</head>
<body>
    <div>
        <h1>Edit Category</h1>
    </div>

    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="create.php">New Post</a></li>
    </ul>

    <div>
        <form method="post">
            <input type="hidden" name="category_id" value="<?= $category['category_id'] ?>">
            <fieldset>
                <p>
                    <label for="category_name">Category Name</label>
                    <input type="text" name="category_name" id="category_name" value="<?= $category['category_name'] ?>">
                </p>
                <p>
                    <input type="submit" name="update_button" value="Update">
                </p>
            </fieldset>
        </form>
    </div>
</body>
</html>
