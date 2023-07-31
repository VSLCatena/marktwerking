<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

require_once('core.php');
$data = array();


#$new_data = $_POST;
#json_decode(file_get_contents('php://input'), true);
#print_r($new_data);
/*if (!empty($new_data)) {

    foreach ($new_data['settings'] as $key => $value) {
        $value =
        $sql = "UPDATE `settings` SET `value` = '$value' WHERE `settings`.`setting` = '$key';";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
    foreach ($new_data['category'] as $key => $value) {
        $sql = "UPDATE `categories` SET `name` = '$value[name]' WHERE `categories`.`id` = '$value[id]';";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
    foreach ($new_data['items'] as $key => $value) {
        $sql = "UPDATE `drinks` SET `name` = '$value[name]', `start_price` = '$value[start_price]', `minimum_price` = '$value[minimum_price]', `active` = '$value[active]' WHERE `drinks`.`id` = '$value[id]';";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }

    //first clear table of drinks&category
    $sql = "TRUNCATE TABLE `drink_category`;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    foreach ($new_data['items'] as $key => $value) {
        foreach ($value['categories'] as $key2 => $value2) {
            $sql = "INSERT INTO `drink_category` (`drink_id`, `category_id`) VALUES ('$value[id]', '$value2');";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
        }

    }
}*/




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
