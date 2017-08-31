<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

require_once('core.php');
$data = array();


$new_data = json_decode(file_get_contents('php://input'), true);
print_r($new_data);
if (!empty($new_data)){

    foreach ($new_data as $key => $value) {
        //$sql = "UPDATE settings SET lastname='Doe' WHERE id=2";
        //$stmt = $conn->prepare($sql[$key]);
        //$stmt->execute();
        //print_r($value);
    }


}




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


// fetch all settings
$query = $pdo->query("SELECT * FROM settings");
$settings = array();
foreach($query->fetchAll(PDO::FETCH_ASSOC) as $row){
    $settings[$row['setting']] = $row['value'];
}
$data['settings'] = $settings;


echo json_encode($data);
