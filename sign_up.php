<?php
/*******w******** 
    
    Name: Aidan Bridges 
    Date: 2023-12-04
    Description: sign up page for final project

****************/
session_start();
require('connect.php');

if (!empty($_POST['first_name']) && !empty($_POST['last_name']) && !empty($_POST['email'])
    && !empty($_POST['password']) && !empty($_POST['confirm_password']) && isset($_POST['user_type'])) 
{

    // Filter inputs
    $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
    $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password']; 

    // Check if passwords match
    if ($_POST['password'] === $_POST['confirm_password']) 
    {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Other form data
        $user_type = $_POST['user_type'];

        // Prepare the SQL query
        $query = "INSERT INTO users (first_name, last_name, email, password, user_type) 
                  VALUES (:first_name, :last_name, :email, :password, :user_type)";
        $statement = $db->prepare($query);

        // Bind parameters
        $statement->bindValue(':first_name', $first_name);
        $statement->bindValue(':last_name', $last_name);
        $statement->bindValue(':email', $email);
        $statement->bindValue(':password', $hashed_password); 
        $statement->bindValue(':user_type', $user_type);

        // Execute the query
        if ($statement->execute()) 
        {
            // Redirect to login.php
            header('Location: log_in.php');
            exit();
        } 
        else 
        {
            echo "Error: Unable to register user.";
        }
    } 
    else 
    {
        echo "Passwords do not match.";
    }
} 
else 
{
    echo "All fields are required.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
</head>
<body>
    <form action="sign_up.php" method="post">
        <fieldset>
            <legend>User Registration</legend>

            <p>
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" required>
            </p>

            <p>
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" required>
            </p>

            <p>
                <label for="email">Email:</label>
                <input type="text" name="email" required>
            </p>

            <p>
                <label for="password">Password:</label>
                <input type="password" name="password" required>
            </p>

            <p>
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" required>
            </p>

            <p>
                <label for="user_type">User Type:</label>
                <select name="user_type" required>
                    <option value="blogger">Blogger</option>
                    <option value="chef">Chef</option>
                    <option value="restaurant_owner">Restaurant Owner</option>
                    <option value="admin">Admin</option>
                </select>
            </p>


            <p>
                <input type="submit" name="submit" value="Register">
            </p>
        </fieldset>
    </form>
</body>
</html>
