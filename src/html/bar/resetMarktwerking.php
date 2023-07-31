<?php include("./password_protect.php"); ?>
<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

require_once('../core.php');
//echo '<pre>';
//print_r($_POST);
//echo '</pre>';

$data=$_POST;

// SQL Injections are no bueno
if (!empty($data) && ($data['reset']='true')) {

    $stmt = $pdo->prepare("TRUNCATE `orders`;");
    $stmt->execute();


}