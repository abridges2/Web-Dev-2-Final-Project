<?php

/*******w******** 
    
    Name: Aidan Bridges 
    Date: 2023-09-24
    Description: Create page for final project wd2.

****************/
require('connect.php');
require('authenticate.php');

// Empty string array for holding any error messages.
$error_msg = '';

// Fetching all of the category table rows for the drop down list in the form.
$query_categories = "SELECT * FROM categories";
$categories_statement = $db->prepare($query_categories);
$categories_statement->execute();
$categories = $categories_statement->fetchall();

// If the submit button is clicked
if (isset($_POST['submit'])) 
{
    // Sanitize data before being inserted into posts table.
    $chosen_title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $chosen_content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);
    $chosen_category_id = filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT);

    // Setting the ID of the post to null here so it can be used in scope below. make it in scope to use below.
    $last_post_id = null;

    if (strlen($chosen_title) < 1 && strlen($chosen_content) < 1) 
    {
        $error_msg = "Both the title and the content need at least one character.";
    } 
    else 
    {
        // Build the parameterized SQL query and bind to the above sanitized values.
        $query = "INSERT INTO posts (title, content, category_id) VALUES (:title, :content, :category_id)";
        $statement = $db->prepare($query);

        // Bind values to the parameters
        $statement->bindValue(':title', $chosen_title);
        $statement->bindValue(':content', $chosen_content);
        $statement->bindValue(':category_id', $chosen_category_id);

        // If the query goes through with 0 errors, relocate user to index page.
        if ($statement->execute()) 
        {
            // Retrieve the ID of the last inserted post
            $last_post_id = $db->lastInsertId();

            // Check if an image is uploaded
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) 
            {
                $image_filename = $_FILES['image']['name'];
                $temporary_image_path = $_FILES['image']['tmp_name'];
                $new_image_path = file_upload_path($image_filename);

                // Move the uploaded image
                if (file_is_an_image($temporary_image_path, $new_image_path)) {
                    move_uploaded_file($temporary_image_path, $new_image_path);

                    // Insert image details into the images table
                    $image_query = "INSERT INTO images (post_id, file_name) VALUES (:post_id, :file_name)";
                    $image_statement = $db->prepare($image_query);
                    $image_statement->bindValue(':post_id', $last_post_id, PDO::PARAM_INT);
                    $image_statement->bindValue(':file_name', $image_filename, PDO::PARAM_STR);

                    
                    if ($image_statement->execute()) 
                    {
                        echo "Image inserted successfully.";
                    } 
                    else 
                    {
                        $errorInfo = $image_statement->errorInfo();
                        echo "Error inserting image: " . implode(" ", $errorInfo);
                    }
                }
            }

            header('Location: index.php');
            exit;
        } 
        else 
        {
            echo "There was an error submitting data to the database.";
        }
    }
}

// Function for saving the user uploaded image file
function file_upload_path($original_filename, $upload_subfolder_name = 'uploads') 
{
    $current_folder = dirname(__FILE__);
    $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
    return join(DIRECTORY_SEPARATOR, $path_segments);
}

//  Function for testing a files image-ness
function file_is_an_image($temporary_path, $new_path) 
{
    $allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png'];
    $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];

    $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
    $actual_mime_type        = getimagesize($temporary_path)['mime'];

    $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
    $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);

    return $file_extension_is_valid && $mime_type_is_valid;
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
    <form action="create.php" method="post" enctype="multipart/form-data">
        <fieldset>
            <?php if (!empty($error_msg)) : ?>
                <?= $error_msg ?>
            <?php endif ?>
            <p>
                <label for="title">Title</label>
                <input name="title" id="title">
            </p>
            <p>
                <label for="content">Content</label>
                <textarea name="content" id="content"></textarea>
            </p>
            <p>
                <label for="category_name">Category</label>
                <select name="category_id" id="category_id">
                    <option value=""></option> <!-- This is for if the post has no category -->
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?= $category['category_id'] ?>"><?= $category['category_name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </p>

            <p>
                <label for="image">Image Filename:</label>
                <input type="file" name="image" id="image">
            </p>
            <p>
                <input type="submit" name="submit" value="Create">
            </p>
        </fieldset>
    </form>
</body>
</html>