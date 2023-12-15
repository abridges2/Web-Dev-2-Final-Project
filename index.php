<?php

/*******w******** 
    
    Name: Aidan Bridges
    Date: 2023-11-09
    Description: Index page for final project wd2.
    Questions: Ask if the sorted stuff was meant to be on a different page.

****************/
session_start();
require('connect.php');

// Fetch categories from the database
$queryCategories = "SELECT * FROM categories";
$statementCategories = $db->prepare($queryCategories);
$statementCategories->execute();
$categories = $statementCategories->fetchAll(PDO::FETCH_ASSOC);

// Default variable declared too ensure that the posts are sorted by date descending as default.
$sorting_column = 'created_at';
$sort_order = 'DESC';
$indication_of_sorting = 'newest to oldest';
// Check what the user has selected for the sorting of the posts.
if(ISSET($_POST['sort_title']))
{
    require('authenticate.php');
    $sorting_column = 'title';
    $sort_order = 'ASC';
    $indication_of_sorting = 'title alphabetically';
}
elseif(ISSET($_POST['sort_oldest']))
{
    require('authenticate.php');
    $sorting_column = 'created_at';
    $sort_order = 'ASC';
    $indication_of_sorting = 'oldest to newest';
}
elseif(ISSET($_POST['sort_newest']))
{
    // Add something so that newest and oldest can be done with the same button. *
    require('authenticate.php');
    $sorting_column = 'created_at';
    $sort_order = 'DESC';
    $indication_of_sorting = 'newest to oldest';
}
elseif(ISSET($_POST['sort_updated']))
{
    require('authenticate.php');
    $sorting_column = 'updated_at';
    $sort_order = "DESC";
    $indication_of_sorting = 'updated newest to oldest';
}

// Build parameterized SQL query for showing all of the posts.
// Default sort order is descending by the date the post was created.
$query = "SELECT * FROM posts ORDER BY $sorting_column $sort_order";
$statement = $db->prepare($query); 
$statement->execute();

// Fetch posts from the chefs table with a limit of 5
$query_chef_posts = "SELECT * FROM chefs ORDER BY created_at DESC LIMIT 5";
$statement_chef_posts = $db->prepare($query_chef_posts);
$statement_chef_posts->execute();
$chef_posts = $statement_chef_posts->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <header>
        <h1 a href="index.php">WinnipegEatsHub</h1>   
    </header>

    <!-- Search Form -->
    <form action="search_results.php" method="GET">
        <label for="keyword">Search:</label>
        <input type="text" name="keyword" id="keyword" required>
        
        <label for="category">Category:</label>
        <select name="category" id="category">
        <option value="">All Categories</option>
        <?php foreach ($categories as $category): ?>
            <option value="<?= $category['category_id'] ?>"><?= $category['category_name'] ?></option>
        <?php endforeach; ?>

        <button type="submit">Search posts by keyword</button>
    </select>
    </form>

    <nav>
        <!-- Might change to buttons or some type of menu design not sure. -->
        <ul>
             <li><a href="index.php">Home</a></li>
             <li><a href="sign_up.php">Sign Up</a></li>
             <li><a href="log_in.php">Log In</a></li>
             <li><a href="logout.php">Log Out</a></li>
             <li><a href="create.php">Create Post</a></li>
             <li><a href="categories.php">Post Categories</a></li>
             <?php if($_SESSION['user_type'] == "admin"): ?>
                <li><a href="users.php">Manage Users</a></li>
             <?php endif; ?>
        </ul>
    </nav>

    <sidebar>
        <h3><a href="chef_showcase.php">Chef Showcase</a></h3>
        <ul>
            <?php foreach ($chef_posts as $chef_post): ?>
                <li><a href="chef_showcase_post.php?id=<?= $chef_post['chef_id'] ?>"><?= $chef_post['chef_name'] ?></a></li>
            <?php endforeach; ?>
        </ul>
    </sidebar>

    <!-- Sorting options for the regular blog post view. -->
    <!-- Sending back button selected as a post parameter -->
    <form method="post" action="index.php">
        <button type="submit" name="sort_newest" value="created_at">Sort by newest</button>  
        <button type="submit" name="sort_title" value="title">Sort by Title</button>
        <button type="submit" name="sort_oldest" value="created_at">Sort by oldest</button>
        <button type="submit" name="sort_updated" value="updated_at">Sort by updated</button>
    </form>
        
    <p>Posts sorted by: <?=$indication_of_sorting?></p>
    <!-- Grabbing a single post -->
    <?php while ($row = $statement-> fetch()): ?>
        <!-- Add ID to div to add style to each post on home page. Change the tags for edit link maybe. -->
        <div id ="single_post">  
            <h2><a href="post.php?id=<?= $row['post_id'] ?>"><?= $row['title'] ?></a></h2>
            <p><a href="edit.php?id=<?= $row['post_id'] ?>">Edit</a></p>
            <p><?=$row['content']?></p>
            <li><?=$row['created_at']?></li>
        </div>
    <?php endwhile ?>
    
</body>
</html>