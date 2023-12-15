<?php
/*******w******** 
    
    Name: Aidan Bridges 
    Date: 2023-12-04
    Description: page for viewing specific posts from the chef_showcase page

****************/

require('connect.php');

$chef_post_id = FILTER_INPUT(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Query for retrieving the single post based on the ID from the get parameter.
$query = "SELECT * FROM chefs WHERE chef_id = :id";
$statement = $db->prepare($query);

// Bind values to the placeholder paramaters in the query.
$statement->bindValue(":id", $chef_post_id, PDO::PARAM_INT);

// Execute the query.
$statement->execute();

// Fetch the single row.
$row = $statement->fetch();

if(!$row)
{
    echo "post was not found";
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
    <h2><a href="index.php">Home</a></h2>
    <h2><a href="chef_showcase.php">See the showcase!</a></h2>
    <article>
        <h2><?= $row['chef_name'] ?></h2>
        <p>Age: <?= $row['chef_age'] ?></p>
        <p>Restaurant: <?= $row['restaraunt_name'] ?></p>
        <p>Location: <?= $row['restaraunt_location'] ?></p>
        <p>Biography: <?= $row['biography'] ?></p>
        <p>Created At: <?= $row['created_at'] ?></p>
    </article>
</body>
</html>