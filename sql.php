<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

require('core.php');

$sql['select_settings_all'] = "SELECT * FROM settings";
$sql['select_drinks_all'] = "SELECT * FROM drinks";
$sql['select_orders_all'] = "SELECT * FROM orders";

$sql['select_settings_pass'] = "SELECT `setting`, `value` FROM `settings` WHERE setting = 'password' ";

$sql['select_drinks_start'] = "SELECT * FROM `drinks` WHERE `round_id` = '0' ";



// prepare sql and bind parameters
$statement = $db->prepare($sql['select_drinks_start']);
$statement->execute();
$drinks_start = $statement->fetchAll(PDO::FETCH_ASSOC);



// prepare sql and bind settings parameters
$statement = $db->prepare($sql['select_settings_all']);
$statement->execute();
$settings = $statement->fetchAll(PDO::FETCH_KEY_PAIR);;

$data['settings']=$settings;
$data['drinks_start']=$drinks_start;
echo json_encode($data);
