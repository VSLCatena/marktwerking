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
        $stmt = $pdo->prepare("INSERT INTO orders (drink_id, amount, volume, price, date) VALUES (?, ?, ?, ?, ?) ;");
        $stmt->execute(array($value['id'], $value['times'],$value['volume'], $value['price'], date("Y-m-d H:i:s", time())));
    }

}

// get info of all orders
$orderInfo=array();
$orderInfo['total']=0;
$orderInfo['all']=array();
$query = $pdo->query('SELECT * FROM orders');
foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $order) {
    if (!array_key_exists($order['drink_id'],$orderInfo)){
        $orderInfo[$order['drink_id']]=0;
    }
    $orderInfo['total']+=$order['amount']*$order['price'];
    $orderInfo[$order['drink_id']]+=$order['amount']*$order['price'];
    array_push($orderInfo['all'],$order);

}
echo json_encode($orderInfo);