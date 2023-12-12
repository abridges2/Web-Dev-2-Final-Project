<?php

/*******w******** 
    
    Name: Aidan Bridges 
    Date: 2023-11-10
    Description: Edit page for the final project wd2

****************/

require('connect.php');
require('authenticate.php');

// Check if the form has been submitted and the update button has been selected
if ($_POST && isset($_POST['title']) && isset($_POST['content']) && isset($_POST['post_id']) && isset($_POST['update_button'])) 
{
    // Sanitize the user input to escape HTML entities and filter out dangerous characters.
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);
    $category_id = filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT);
    $id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
    $id = filter_var($id, FILTER_VALIDATE_INT);

    // Build the parameterized query and bind the above sanitized values.
    $query = "UPDATE posts SET title = :title, content = :content, category_id = :category_id WHERE post_id = :id";
    $statement = $db->prepare($query);

    // Bind the values 
    $statement->bindValue(':title', $title);
    $statement->bindValue(':content', $content);
    $statement->bindValue(':category_id', $category_id, PDO::PARAM_INT);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);

    // Execute the UPDATE.
    $statement->execute();

    // Redirect after the update.
    header("Location: index.php");
    exit;
} 
elseif ($_POST && isset($_POST['title']) && isset($_POST['content']) && isset($_POST['post_id']) && isset($_POST['delete_button'])) 
{
    // Get the post ID to delete and sanitize
    $id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
    $id = filter_var($id, FILTER_VALIDATE_INT);

    // Build the parameterized query to delete the post
    $query = "DELETE FROM posts WHERE post_id = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(":id", $id, PDO::PARAM_INT);

    // Execute the DELETE query
    $statement->execute();

    // Redirect after the delete
    header("Location: index.php");
    exit;
} 
elseif (isset($_GET['id'])) // Check if the id param exists in the URL
{ 
    // Sanitize the id. 
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $id = filter_var($id, FILTER_VALIDATE_INT);

    // Build the parameterized SQL query using the filtered id.
    $query = "SELECT * FROM posts WHERE post_id = :id LIMIT 1";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);

    // Execute the SELECT and fetch the single row returned. (The blog post that user clicked edit on)
    $statement->execute();
    $row = $statement->fetch();
} 
else
{
    $id = false; // Not updating, deleting, or retrieving set the id to false.
}

// Fetch all categories for the dropdown
$categoryQuery = "SELECT * FROM categories";
$categoryStatement = $db->prepare($categoryQuery);
$categoryStatement->execute();
$categories = $categoryStatement->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Edit this Post!</title>
</head>

<body>
    <div>
        <div>
            <h1>
                Edit Post
            </h1>
        </div>

        <ul>
            <li>
                <a href="index.php">Home</a>
            </li>
            <li>
                <a href="create.php">New Post</a>
            </li>
        </ul>

        <div>
            <form method="post">
                <!-- Grab id from this hidden input -->
                <input type="hidden" name="post_id" value="<?= $row['post_id'] ?>">
                <fieldset>
                    <p>
                        <label for="title">Title</label>
                        <input name="title" id="title" value="<?= $row['title'] ?>">
                    </p>
                    <p>
                        <label for="content">Content</label>
                        <textarea name="content" id="content"><?= $row['content'] ?></textarea>
                    </p>
                    <p>
                        <label for="category_id">Category</label>
                        <select name="category_id" id="category_id">
                            <?php foreach ($categories as $category) : ?>
                                <option value="<?= $category['category_id'] ?>" <?= ($category['category_id'] == $row['category_id']) ? 'selected' : '' ?>>
                                    <?= $category['category_name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </p>
                    <p>
                        <input type="submit" name="update_button" value="Update">
                        <input type="submit" name="delete_button" value="Delete">
                    </p>
                </fieldset>
            </form>
        </div>
    </div>

</body>

</html>
