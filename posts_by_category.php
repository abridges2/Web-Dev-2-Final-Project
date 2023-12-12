<?php
/*******w******** 
    
    Name: Aidan Bridges 
    Date: 2023-11-13
    Description: Filtered posts by single category page for final project wd2.

****************/
require('connect.php');
require('authenticate.php');

$chosen_category_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

// Query to get category name
$query_for_names = "SELECT * FROM categories WHERE category_id = :category_id";
$statement_for_names = $db->prepare($query_for_names);
$statement_for_names->bindParam(':category_id', $chosen_category_id, PDO::PARAM_INT);
$statement_for_names->execute();
$category = $statement_for_names->fetch();

// Query to get posts
$query = "SELECT * FROM posts WHERE category_id = :category_id";
$statement = $db->prepare($query); 
$statement->bindParam(':category_id', $chosen_category_id, PDO::PARAM_INT);
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
    <h1>Posts filtered by <?= $category['category_name'] ?></h1>
    <?php while($row = $statement->fetch()): ?>
        <h2><a href="post.php?id=<?= $row['post_id'] ?>"><?= $row['title'] ?></a></h2>
    <?php endwhile ?>
</body>
</html>
