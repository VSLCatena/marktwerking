<?php

require_once 'core.php';
header('Content-Type: application/json');
$timeframe = 10 * 60;

function roundTo5s($num)
{
    return round($num * 20) / 20;
}

// Grab all the drinks and link them with the correct ID in the array
$query       = $pdo->query('SELECT * FROM drinks');
$drinks      = [];
$drinksFinal = [];
$prices      = [];

foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $drink) {
    $drinks[$drink['id']]              = $drink;
    $drinks[$drink['id']]['price']     = $drink['start_price'];
    $drinks[$drink['id']]['new_price'] = null;

    // Set up final stuff
    $drinksFinal[$drink['id']]['id']     = $drink['id'];
    $drinksFinal[$drink['id']]['name']   = $drink['name'];
    $drinksFinal[$drink['id']]['prices'] = [];
    $prices[0][$drink['id']]             = $drink['start_price'];
}

// Next grab the list of order history
$ptsd = $pdo->prepare('SELECT drink_id AS drink_id, sum(amount) AS amount, floor((unix_timestamp(date)) / ?) AS timeframe
FROM orders
WHERE date < ?
GROUP BY orders.drink_id, floor((unix_timestamp(date)) / ?)
ORDER BY floor((unix_timestamp(date)) / ?)');
// We round to the closest timeframe, this is so both the bar and the projector will have accurate results.
// We just need to make sure they're both loaded AFTER this timeframe happened.
$ptsd->execute([$timeframe, date('Y-m-d H:i:s', floor(time() / $timeframe) * $timeframe), $timeframe, $timeframe]);

$orderHistory = [];
$totals       = [];

foreach ($ptsd->fetchAll(PDO::FETCH_ASSOC) as $row) {
    // If the timeframe totals doesn't exist yet, create it
    if (! isset($totals[$row['timeframe']])) {
        $totals[$row['timeframe']] = 0;
    }

    // Add it to the timeframe totals
    $totals[$row['timeframe']] += intval($row['amount']);
    // And add the amount to the order history
    $orderHistory[$row['timeframe']][$row['drink_id']] = intval($row['amount']);
}

// Now we loop over all drinks and set them to 0 if nothing has been purchased that round
foreach ($orderHistory as &$orderH) {
    foreach ($drinks as $drink) {
        if (! array_key_exists($drink['id'], $orderH)) {
            $orderH[$drink['id']] = 0;
        }
    }
}

// We loop over every order history and create the price with that.
foreach ($orderHistory as $timeframe => $history) {
    $total = $totals[$timeframe];

    // First we calculate the percentages per drink. This will be overwritten for every foreach
    foreach ($drinks as $id => &$drink) {
        $drink['percentage'] = round($history[$id] / $total * 100);
    }

    // Then we calculate how much the price descended.
    // We use this to calculate the ascension prices.
    $totalDescend = 0;

    foreach ($drinks as $id => &$drink) {
        if ($drink['percentage'] < 15) {
            // Magic stuff
            $diff = roundTo5s(round((10 - $drink['percentage']) / 10));
            // Make sure it doesn't exceed the minimum price
            $drink['new_price'] = max($drink['price'] - $diff, $drink['minimum_price']);
            // Add the price to the totalDescend variable
            $totalDescend += $drink['price'] - $drink['new_price'];
        }
    }

    // For now we do this in a separate foreach as we want to keep the code as much the same as the old one
    // For more easy debugging :3
    $totalAscendPercentages = 0;

    foreach ($drinks as $drink) {
        if ($drink['percentage'] > 15) {
            $totalAscendPercentages += $drink['percentage'];
        }
    }

    foreach ($drinks as $id => &$drink) {
        if ($drink['percentage'] > 15) {
            $drink['ascendPercentage'] = round($drink['percentage'] / $totalAscendPercentages * 100);
        }
    }

    $totalAscend = 0;

    foreach ($drinks as $id => &$drink) {
        if ($drink['percentage'] > 15) {
            $diff               = roundTo5s($drink['ascendPercentage'] * $totalDescend / 100);
            $drink['new_price'] = $drink['price'] + $diff;
            $totalAscend += $drink['new_price'] - $drink['price'];
        }
    }

    // Now update every price to the new_price for the next loop
    foreach ($drinks as $id => &$drink) {
        // If we don't have a new price, we don't have to update anything
        if ($drink['new_price'] != null) {
            $drink['price'] = $drink['new_price'];
        }

        // Also set the new price to null for the next iteration
        $drink['new_price'] = null;
        // Also add it to the prices table
        $prices[$timeframe][$id] = $drink['price'];
    }
}

foreach ($prices as $timeframe => $priceList) {
    foreach ($priceList as $key => $price) {
        $drinksFinal[$key]['prices'][] = doubleval($price);
    }
}

echo json_encode(array_values($drinksFinal), JSON_PRETTY_PRINT);
