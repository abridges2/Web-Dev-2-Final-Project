<?php
session_start();
require('connect.php');

// Check if the user is of type chef.
if(isset($_POST['submit']))
{
    // Input Validation
    $chef_name = filter_input(INPUT_POST, 'chef_name', FILTER_SANITIZE_STRING);
    $chef_age = filter_input(INPUT_POST, 'chef_age', FILTER_VALIDATE_INT);
    $restaraunt_name = filter_input(INPUT_POST, 'restaraunt_name', FILTER_SANITIZE_STRING);
    $restaraunt_location = filter_input(INPUT_POST, 'restaraunt_location', FILTER_SANITIZE_STRING);
    $biography = filter_input(INPUT_POST, 'biography', FILTER_SANITIZE_STRING);

    $query = "INSERT INTO chefs(chef_name, chef_age, restaraunt_name, restaraunt_location, biography)
        VALUES (:chef_name, :chef_age, :restaraunt_name, :restaraunt_location, :biography)";
    $statement = $db->prepare($query);

    // Bind Parameters
    $statement->bindValue(':chef_name', $chef_name);
    $statement->bindValue(':chef_age', $chef_age);
    $statement->bindValue(':restaraunt_name', $restaraunt_name);
    $statement->bindValue(':restaraunt_location', $restaraunt_location); 
    $statement->bindValue(':biography', $biography);

    // Execute the query
    if ($statement->execute()) 
    {
        // Redirect after successful submission
        header("Location: chef_showcase.php");
        exit();
    } 
    else 
    {
        echo "Error: Unable to submit chef information.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="sign_up_chef_showcase.php" method="post">
        <?php if($_SESSION['user_type'] == 'chef'): ?>
            <!-- Chef information -->
            <label for="chef_name">Name:</label>
            <input type="text" name="chef_name" id="chef_name">

            <label for="chef_age">Age:</label>
            <input type="text" name="chef_age" id="chef_age">

            <label for="restaraunt_name">Restaraunt Name:</label>
            <input type="text" name="restaraunt_name" id="restaraunt_name">

            <label for="restaraunt_location">Restaraunt Location:</label>
            <input type="text" name="restaraunt_location" id="restaraunt_location">

            <label for="biography">Biography:</label>
            <input type="text" name="biography" id="biography" placeholder="Tell us about yourself.">

            <button type="submit" name="submit">Submit</button>
        
        <?php else: ?>
            <?= "Your user account is not registered as a chef." ?>
        <?php endif; ?>        
    </form>
</body>
</html>
