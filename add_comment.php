<?php
/*******w******** 
    
    Name: Aidan Bridges 
    Date: 2023-11-29
    Description: add comment page for the final project wd2

****************/
session_start();

require('connect.php');

// Retrieve the post_id from the URL
$post_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Set user_id to a default value for now. Adding functionality for different users soon.
    $user_id = 1;

    // Retrieve the user-entered comment and CAPTCHA from the form
    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
    $user_captcha = filter_input(INPUT_POST, 'captcha', FILTER_SANITIZE_NUMBER_INT);

    // Verify the CAPTCHA
    if (!isset($_SESSION['captcha']) || $user_captcha != $_SESSION['captcha']) {
        // Redirect the user back to the form and give another chance
        header("Location: add_comment.php?id=$post_id&error=captcha");
        exit;
    }

    // CAPTCHA verification succeeded, clear the session variable
    unset($_SESSION['captcha']);

    // Query to insert user's comment into the database
    $query = "INSERT INTO comments (user_id, post_id, content) VALUES (:user_id, :post_id, :content)";

    $statement = $db->prepare($query);

    // Bind parameters
    $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
    $statement->bindValue(':content', $comment, PDO::PARAM_STR);

    // Execute the query
    $statement->execute();

    // Redirect back to the post after adding the comment
    header("Location: post.php?id=$post_id");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Comment</title>
</head>
<body>
    <form action="add_comment.php?id=<?= $post_id ?>" method="post">
        <p>
            <label for="comment">Comment on this post</label>
            <textarea name="comment" id="comment" required></textarea>
        </p>
         <!-- CAPTCHA -->
         <p>
            <label for="captcha">Enter the CAPTCHA</label>
            <input type="text" name="captcha" id="captcha" required>
            <img src="generate_captcha.php">
        </p>

        <input type="submit" name="submit" value="Add Comment">
    </form>
</body>
</html>
