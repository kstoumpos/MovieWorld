<?php

/**
 * mysqli_connect
 */

$databaseHost = 'localhost';
$databaseName = 'db';
$databaseUsername = 'root';
$databasePassword = 'root';

$mysqli = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName); 
