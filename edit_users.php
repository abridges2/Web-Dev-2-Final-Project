<?php
/*******w******** 
    
    Name: Aidan Bridges 
    Date: 2023-12-04
    Description: Edit user page for admin management page for the final project.

****************/
require('connect.php');

if (!isset($_SESSION)) 
{
    session_start();
    if ($_SESSION['user_type'] != 'admin') 
    {
        header("Location: index.php");
        exit;
    }

    if (isset($_GET['id'])) 
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        // Fetch user data for editing
        $query = "SELECT * FROM users WHERE user_id = :user_id";
        $statement = $db->prepare($query);
        $statement->bindParam(':user_id', $id);
        $statement->execute();

        if ($statement->rowCount() > 0) 
        {
            $user = $statement->fetch();
        } 
        else 
        {
            echo "User not found.";
            exit;
        }
    }
} 
else 
{
    header("Location: log_in.php");
    exit;
}

// Handle form submission and updating or deleting the user
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
    $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);

    if (isset($_POST['update'])) 
    {
        // Update existing user
        $query = "UPDATE users SET first_name = :first_name WHERE user_id = :user_id";
        $statement = $db->prepare($query);
        $statement->bindParam(':first_name', $first_name);
        $statement->bindParam(':user_id', $user_id);
        $statement->execute();
        header("Location: users.php");
        exit;
    } 
    elseif (isset($_POST['delete'])) 
    {
        // Delete user
        $query = "DELETE FROM users WHERE user_id = :user_id";
        $statement = $db->prepare($query);
        $statement->bindParam(':user_id', $user_id);
        $statement->execute();

        // Redirect after deletion
        header("Location: users.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
</head>
<body>
    <h2>Edit User</h2>

    <form action="edit_users.php" method="post">
        <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">

        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" value="<?= $user['first_name'] ?>" required>

        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" value="<?= $user['last_name'] ?>" required>

        <label for="email">Email:</label>
        <input type="text" name="email" value="<?= $user['email'] ?>" required>

        <!-- Update button -->
        <button type="submit" name="update">Update User</button>

        <!-- Delete button -->
        <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this user?')">Delete User</button>
    </form>
</body>
</html>

