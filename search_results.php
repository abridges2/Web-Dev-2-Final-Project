<?php
/*******w******** 
    
    Name: Aidan Bridges 
    Date: 2023-10-29
    Description: search results page for the final project wd2

****************/
require('connect.php'); // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['keyword'])) 
{
    $keyword = '%' . filter_input(INPUT_GET, 'keyword', FILTER_SANITIZE_STRING) . '%';

    // Category filtering logic
    $category_filter = null;
    if (isset($_GET['category'])) {
        $category_filter = (int)$_GET['category'];
        
    }

    // Perform a SQL LIKE query to search for pages
    $query = "SELECT * FROM posts WHERE (title LIKE :keyword OR content LIKE :keyword)";
    
    if ($category_filter !== null) {
        $query .= " AND category_id = :category";
        // nested if for the all categories
    }

    $statement = $db->prepare($query);
    $statement->bindValue(':keyword', $keyword, PDO::PARAM_STR);
    
    if ($category_filter !== null) {
        $statement->bindValue(':category', $category_filter, PDO::PARAM_INT);
    }

    $statement->execute();

    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
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
    <h1>Posts based on <?=$keyword ?></h1>

    <?php if (!empty($results)): ?>
        <?php foreach ($results as $result): ?>
            <li><a href="post.php?id=<?= $result['post_id'] ?>"><?= $result['title'] ?></a></li>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No results found.</p>
    <?php endif; ?>
</body>
</html>
