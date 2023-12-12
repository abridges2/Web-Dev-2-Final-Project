<?php
/*******w******** 
    
    Name: Aidan Bridges 
    Date: 2023-12-04
    Description: page for viewing specific posts from the chef_showcase page

****************/
$chef_post_id = FILTER_INPUT(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Query for retrieving the single post based on the ID from the get parameter.
$query = "SELECT * FROM chefs WHERE chef_id = :id";
$statement = $db->prepare($query);

// Bind values to the placeholder paramaters in the query.
$statement->bindValue(":id", $chef_post_id, PDO::PARAM_INT);

// Execute the query.
$statement->exectue();

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
    <article>
        <h2><?= $row['chef_name'] ?></h2>
        <p>Age: <?= $row['chef_age'] ?></p>
        <p>Restaurant: <?= $row['restaurant_name'] ?></p>
        <p>Location: <?= $row['restaurant_location'] ?></p>
        <p>Biography: <?= $row['biography'] ?></p>
        <p>Created At: <?= $row['created_at'] ?></p>
    </article>
</body>
</html>