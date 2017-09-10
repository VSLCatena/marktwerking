<?php include("./password_protect.php"); ?>
<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

require_once('../core.php');
$data = array();

// Grab all categories
$query = $pdo->query('SELECT * FROM categories');
$data['categories'] = $query->fetchAll(PDO::FETCH_ASSOC);

// fetch all drink data
$query = $pdo->query("SELECT * FROM drinks");
// Save it to a separate array to easily link categories to it
$drinks = $query->fetchAll(PDO::FETCH_ASSOC);

// fetch all the drink_categories
$query = $pdo->query('SELECT * FROM drink_category');
$drink_cat = array();
foreach($query->fetchAll(PDO::FETCH_ASSOC) as $row){
    $drink_cat[$row['drink_id']][] = $row['category_id'];
}

// Now link the categories to the drinks
foreach($drinks as &$drink){
    if(!array_key_exists($drink['id'],$drink_cat)){
        $drink['categories'] = array();
        continue;
    }
    $drink['categories'] = $drink_cat[$drink['id']];
}
$data['drinks'] = $drinks;

// fetch all stock data
$query = $pdo->query("SELECT * FROM `stock` ORDER BY `date` DESC");
// Save it to a separate array to easily link categories to it
foreach($query->fetchAll(PDO::FETCH_ASSOC) as $row){
    $stock[$row['drink_id']][] = $row;
}

//link stock data
foreach($drinks as &$drink) {
    if (!array_key_exists($drink['id'], $stock)) {
        $drink['stock'] = array();
        continue;
    }
    $drink['stock'] = $stock[$drink['id']];
}

// fetch all settings
$query = $pdo->query("SELECT * FROM settings");
$settings = array();
foreach($query->fetchAll(PDO::FETCH_ASSOC) as $row){
    $settings[$row['setting']] = $row['value'];
}
$data['settings'] = $settings;


echo json_encode($data);
