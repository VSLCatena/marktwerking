<?php

echo '<pre>';
var_dump($_POST);
echo '</pre>';

// SQL Injections are no bueno
//$new_data = json_decode(file_get_contents('php://input'), true);
//print_r($new_data);
//if (!empty($new_data)) {
//
//    foreach ($new_data['settings'] as $key => $value) {
//        $value =
//        $sql = "UPDATE `settings` SET `value` = '$value' WHERE `settings`.`setting` = '$key';";
//        $stmt = $pdo->prepare($sql);
//        $stmt->execute();
//    }
//    foreach ($new_data['category'] as $key => $value) {
//        $sql = "UPDATE `categories` SET `name` = '$value[name]' WHERE `categories`.`id` = '$value[id]';";
//        $stmt = $pdo->prepare($sql);
//        $stmt->execute();
//    }
//    foreach ($new_data['items'] as $key => $value) {
//        $sql = "UPDATE `drinks` SET `name` = '$value[name]', `start_price` = '$value[start_price]', `minimum_price` = '$value[minimum_price]', `active` = '$value[active]' WHERE `drinks`.`id` = '$value[id]';";
//        $stmt = $pdo->prepare($sql);
//        $stmt->execute();
//    }
//
//    //first clear table of drinks&category
//    $sql = "TRUNCATE TABLE `drink_category`;";
//    $stmt = $pdo->prepare($sql);
//    $stmt->execute();
//    foreach ($new_data['items'] as $key => $value) {
//        foreach ($value['categories'] as $key2 => $value2) {
//            $sql = "INSERT INTO `drink_category` (`drink_id`, `category_id`) VALUES ('$value[id]', '$value2');";
//            $stmt = $pdo->prepare($sql);
//            $stmt->execute();
//        }
//
//    }
//}