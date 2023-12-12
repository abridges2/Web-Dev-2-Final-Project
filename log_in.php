<?php
/*******w******** 
    
    Name: Aidan Bridges 
    Date: 2023-12-04
    Description: log in page for final project

****************/

session_start();
require('connect.php');

if(isset($_SESSION['email']))
{
    header("Location: index.php");
    exit;
}

if (isset($_POST['submit'])) 
{
    // Filter inputs
    $input_email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $input_password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    echo "Input Email: $input_email<br>";
    echo "Input Password: $input_password<br>";

    // Query to check user credentials 
    $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
    $statement = $db->prepare($query);
    $statement->bindValue(':email', $input_email);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    echo "User Data: ";
    var_dump($user);

    if ($user && password_verify($input_password, $user['password'])) 
    {
    // store user information
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['user_type'] = $user['user_type'];
    $_SESSION['logged_in'] = true;

    header("Location: index.php");
    exit;
    }
    else
    {
        echo "Incorrect email or password.";
    }
}
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
</head>
<body>
    <form action="log_in.php" method="post">
        <fieldset>
            <legend>User Login</legend>

            <p>
                <label for="email">Email:</label>
                <input type="text" name="email" required>
            </p>

            <p>
                <label for="password">Password:</label>
                <input type="password" name="password" required>
            </p>

            <p>
                <input type="submit" name="submit" value="Login">
            </p>
        </fieldset>
    </form>
</body>
</html>
