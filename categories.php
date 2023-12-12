<?php
/*******w******** 
    
    Name: Aidan Bridges 
    Date: 2023-11-13
    Description: Post categories page for final project wd2.

****************/
require('connect.php');
require('authenticate.php');

$query = "SELECT * FROM categories";
$statement = $db->prepare($query); 
$statement->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Categories of Posts</h1>
    <h2>Search for posts by category</h2>
    <ul>
        <?php while ($row= $statement->fetch()): ?>
            <li><a href="posts_by_category.php?id=<?= $row['category_id'] ?>"><?= $row['category_name'] ?></a></li>
            <li><a href="edit_category.php?id=<?=$row['category_id'] ?>">Edit</a></li>
        <?php endwhile ?>
    </ul>
    <li><a href="create_category.php">Add a new category</a></li>
</body>
</html>
