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
if (!empty($data)) {

    foreach ($data['orders'] as $key => $value) {
        $stmt = $pdo->prepare("INSERT INTO `orders` (`drink_id`,`amount`) VALUES (?,?) ;");
        $stmt->execute(array($value['id'],$value['times']));
    }

}