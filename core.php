<?php
require_once('settings.php');

$pdo = new PDO(DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_DATABASE . ';', DB_USERNAME, DB_PASSWORD);
