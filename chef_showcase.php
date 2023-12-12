<?php 
/*******w******** 
    
    Name: Aidan Bridges 
    Date: 2023-12-12
    Description: chef_showcase page for final project wd2.

****************/

$query = "SELECT * FROM chefs ORDER BY created_at";
$statement = $db->prepare($query);
$statement->execute();
$rows = $statement->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Chef Showcase</title>
</head>
<body>
    <header>
        <h1>Welcome to the Chef Showcase!</h1>
    </header>
    
    <div>
        <?php foreach ($rows as $row): ?>
            <div>
                <div>
                    <h5><a href="chef_showcase_post.php?id=<?=$row['chef_id'] ?>"><?= $row['chef_name'] ?></a></h5>
                    <p><?= $row['biography'] ?></p>
                    <p>Age: <?= $row['chef_age'] ?></p>
                    <p>Restaurant Name: <?= $row['restaurant_name'] ?></p>
                    <p>Restaurant Location: <?= $row['restaurant_location'] ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
