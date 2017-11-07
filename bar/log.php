<?php include("./password_protect.php"); ?>
<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

//logging
require_once('../core.php');
//echo '<pre>';
//var_dump($_POST);
//echo '</pre>';
//
//$log_database = array(
//    "Modus is veranderd",
//    "SQL-Error",
//    "Help"
//);

//
//$stmt = $pdo->prepare("
//				INSERT INTO `log` (level,type,message)
//				VALUES (?,?,?)
//			");
//$stmt->execute(
//    array(
//        $_POST['log']['level'],
//        $_POST['log']['type'],
//        $_POST['log']['message']
//    ));





$query = $pdo->query("SELECT * FROM log");
// Save it to a separate array to easily link categories to it
$log = $query->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($log);