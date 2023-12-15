<?php
/*******w******** 
    
    Name: Aidan Bridges 
    Date: 2023-12-04
    Description: admin user management page for final project.

****************/
require('connect.php');

// Query to get all users
$query = "SELECT * FROM users ORDER BY last_name, first_name";

$statement = $db->prepare($query);
$statement->execute();

$users = $statement->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin User Management</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email Address</th>
                <th>User Type</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $user): ?>
                <tr>
                    <td><?=$user['first_name']?></td>
                    <td><?=$user['last_name']?></td>
                    <td><?=$user['email']?></td>
                    <td><?=$user['user_type']?></td>
                    <td><?=$user['created_at']?></td>
                    <td><a href="edit_users.php?id=<?=$user['user_id']?>">Edit</a></td>
                </tr>
                <?php endforeach ?>
        </tbody>
    </table>
</body>
</html>