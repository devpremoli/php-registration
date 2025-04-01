<?php
$HOSTNAME = 'localhost';
$USERNAME = 'root';
$PASSWORD = '';
$DATABASE = 'registration';

$con = mysqli_connect($HOSTNAME, $USERNAME, $PASSWORD, $DATABASE);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
