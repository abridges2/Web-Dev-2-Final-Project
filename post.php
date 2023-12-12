<?php

/*******w******** 
    
    Name: Aidan Bridges
    Date: 2023-09-24
    Description: post for final project wd2

****************/

require('connect.php');

// Sanitize $_GET['id] to ensure it's a number.
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
// Check if $id is a valid integer
if (!filter_var($id, FILTER_VALIDATE_INT)) {
    // Redirect the user back to the index page if $id is not a valid integer
    header("Location: index.php");
    exit;
}

// Build parameterized SQL query 
$query = "SELECT * FROM posts WHERE post_id = :id LIMIT 1";
$statement = $db->prepare($query); 

//Bind the :id parameter from the query to the sanitized $id specifying a binding-type of integer.
$statement->bindValue(':id', $id, PDO::PARAM_INT);
$statement->execute();

$row = $statement->fetch();
if (!$row) 
{
    // Handle the case where no post is found
    echo "Post not found!";
    exit;
}

$comments_query = "SELECT * FROM comments WHERE post_id = :id AND visible = 1 ORDER BY created_at DESC";
$comments_statement = $db->prepare($comments_query);
$comments_statement->bindValue(':id', $id, PDO::PARAM_INT);
$comments_statement->execute();
$comments = $comments_statement->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>My Blog Post!</title>
</head>
<body>
    <nav>
        <!-- Might change to buttons or some type of menu design not sure. -->
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="create.php">Create Post</a></li>
        </ul>
    </nav>

    <h2><?=$row['title'] ?></h2>
    <p>
        <small><?=$row['created_at'] ?></small>
        <a href="edit.php?id=<?= $row['post_id'] ?>">Edit</a>
        <a href="add_comment.php?id=<?= $row['post_id'] ?>">Add Comment</a>
    </p>
    <p>
        <?= $row['content'] ?>
    </p>
    <?php foreach($comments as $comment): ?>
        <p>
            <?= $comment['content']?>
            <?= $comment['created_at']?>
            <a href="moderate_comment.php?id=<?= $comment['comment_id'] ?>">Moderate Comment</a>
        </p>
    <?php endforeach ?>


</body>
</html>