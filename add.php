<?php session_start(); ?>

<?php

if ( !isset( $_SESSION['valid'] ) ) {
	header( 'Location: login.php' );
}

?>

<html lang="en">
    <head>
        <title>Movie World - Add Movie</title>
    </head>
    
    <body>
    <h2>Add Movie</h2>
    <br>
    <form name="form2" method="post" action="">
        <label for="title">Title: </label><input type="text" name="title" id="title">
        <label for="description">Description: </label><input type="text" name="description" id="description">
        <label for="date">Date of publish: </label><input type="date" name="date" id="date">
        <input type="submit" value="Add movie">
    </form>
    <?php
    include("connection.php");
    require_once('connection.php');

    if ( isset( $_POST['submit'] ) ) {
        $title = $_POST['title'];
        $desc = $_POST['description'];
        $date = strtotime( $_POST['date'] );
        $userName = $_SESSION['valid'];

        if( $date == "" || $title == "" || $desc == "" ) {
            echo "All fields should be filled. Either one or many fields are empty.";
            echo "<br/>";
        } else {
            $mysqli = new mysqli($databaseHost, $databaseUsername, $databasePassword, $databaseName);
            mysqli_query($mysqli, "INSERT INTO movies(title, description, username, dop, likes, hates) VALUES('$title', '$desc', '$userName,' '$date', 0, 0)")
            or die("Could not execute the insert query.");

            echo "<a href='login.php'>Login</a>";
        }
    }
    ?>
    </body>
</html>
