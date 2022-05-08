<?php

$db_name =  'profilelist';
$db_user = 'root';
$db_pass = '';

$db = new PDO("mysql:host=localhost;dbname=$db_name", $db_user, $db_pass);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


/* try {
    $dbh = new PDO("mysql:host=localhost;dbname=$db_name", $db_user, $db_pass);
    foreach($dbh->query('SELECT * from FOO') as $row) {
        print_r($row);
    }
    $dbh = null;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
} */
