<?php
require_once('core.php');

class Product implements JsonSerializable {
    private $id;
    private $name;
    private $minimumPrice;
    private $startPrice;
    private $prices;

    function __construct($id, $name, $startPrice, $minimumPrice){
        $this->id = $id;
        $this->name = $name;
        $this->startPrice = $startPrice;
        $this->minimumPrice = $minimumPrice;
    }

    /**
     * This is the calculating function.
     * This should contain the algorithm used to determine the prices.
     *
     * @param array $roundAmounts the amount that have been sold on this product per round.
     * @param array $totalsPerRound the amount that have been sold in the complete round (So all products)
     * @param int $amountOfItems the amount of items that are in the game
     *
     * @return array prices per round
     */
    function calculate($roundAmounts, $totalsPerRound, $amountOfItems){
        $prices = array();
        $prices[0] = $this->startPrice;

        for($i = 0; $i < count($roundAmounts); $i++){
            $percentage = $roundAmounts[$i] / ($totalsPerRound[$i] / $amountOfItems);

            $prices[$i+1] = max(
                $prices[$i] * max(
                    log($percentage, 1.5),
                    0.6
                ),
                $this->minimumPrice
            );

            // Round it to the nearest 5 cents
            $prices[$i+1] = round($prices[$i+1]*20)/20;
        }

        $this->prices = $prices;
    }

    public function getPrices(){
        return $this->prices;
    }

    public function getLatestPrice(){
        return $this->prices[count($this->prices)-1];
    }

    public function jsonSerialize(){
        return [
            'id' => $this->id,
            'name' => $this->name,
            'prices' => $this->prices,
        ];
    }
}

// Create an array with all the products
$ptsd = $pdo->prepare('SELECT * FROM drinks');
$ptsd->execute();

$products = array();
foreach($ptsd->fetchAll() as $row){
    $products[$row['id']] = new Product($row['id'], $row['name'], $row['start_price'], $row['minimum_price']);
}

// Get a list of all average buying amounts
$ptsd = $pdo->prepare('SELECT SUM(amount) as amount, FLOOR(UNIX_TIMESTAMP(date) / (10 * 60)) AS timeframe FROM orders GROUP BY timeframe ORDER BY timeframe ASC');
$ptsd->execute();
$roundAmounts = array();

foreach($ptsd->fetchAll() as $row){
    $roundAmounts[$row['timeframe']] = $row['amount'];
}


// Get a list of amount per product per timeframe
$ptsd = $pdo->prepare('SELECT * FROM order_history');
$ptsd->execute();
$amounts = array();
foreach($ptsd->fetchAll(PDO::FETCH_ASSOC) as $row){
    $amounts[$row['drink_id']][$row['timeframe']] = $row['amount'];
}


// We want to fill in the blanks, for when a product hasn't been bought all round
foreach(array_keys($amounts) as $id){
    // So first we fill in the blanks
    foreach(array_keys($roundAmounts) as $timeframe){
        if(!array_key_exists($timeframe, $amounts[$id])){
            $amounts[$id][$timeframe] = 0;
        }
    }
    ksort($amounts[$id]);

    // Then we want to normalize the indexes from timeframes to normal numbered indexes.
    $amounts[$id] = array_values($amounts[$id]);
}

// At last, we'll normalize the indexes of the averages
$roundAmounts = array_values($roundAmounts);

// Now send all the data to the products for calculation
foreach($products as $id => $product){
    $product->calculate($amounts[$id], $roundAmounts, count($products));
}

// Now we just want an array containing all the values without their keys. And use pretty printing because it's pretty <3
echo json_encode(array_values($products), JSON_PRETTY_PRINT);