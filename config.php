<?php

define ('IMGPATH', './img/profiles/');

$db_name =  'profilelist';
$db_user = 'root';
$db_pass = '';

$db = new PDO("mysql:host=localhost;dbname=$db_name", $db_user, $db_pass);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$data = $_POST;
