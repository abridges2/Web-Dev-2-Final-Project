<?php
require('connect.php');
require('authenticate.php');
$comment_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

// Getting the content from the comment that was selected by the user.
$comment_query = "SELECT content FROM comments WHERE comment_id = :comment_id";
$statement = $db->prepare($comment_query);
$statement->bindValue(':comment_id', $comment_id, PDO::PARAM_INT);
$statement->execute();
$comment = $statement->fetch();

// If update button is clicked.
if(isset($_POST['update']))
{ 
    // Filter the input for content making sure its string has no special chars.
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);

    // Query for updating the comments content.
    $update_query =  "UPDATE comments SET content = :content WHERE comment_id = :comment_id";
    $statement = $db->prepare($update_query);
    // Bind content variable to the content placeholder variable in the query.
    $statement->bindValue(':content', $content, PDO::PARAM_STR);
    $statement->bindValue(':comment_id', $comment_id, PDO::PARAM_INT);

    // Execute the update on the comment
    $statement->execute();

    header("Location: post.php");
    exit;
}
elseif(isset($_POST['delete']))
{
    // Get the post ID to delete and sanitize
    $id_to_delete = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    $delete_query = "DELETE FROM comments WHERE comment_id = :comment_id";
    $delete_statement = $db->prepare($delete_query);
    $delete_statement->bindValue(":comment_id", $id_to_delete, PDO::PARAM_INT);
    $delete_statement->execute();

    header("Location: post.php");
    exit;
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
    <form action="moderate_comment.php?id=<?=$comment_id ?>" method="post">
        <!-- Update content section  -->
        <label for="content">Update comments content</label>
        <textarea name="content" id="comment"><?= $comment['content'] ?></textarea>

        <!-- Update Content of Comment Button -->
        <button name="update">Update Content</button>

        <!-- Delete Comment Button -->
        <button name="delete">Delete Comment</button>
    </form>

</body>
</html>

