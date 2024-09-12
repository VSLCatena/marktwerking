<?php include './password_protect.php'; ?>
<?php


require_once '../core.php';

if (MW_DEBUG == true) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}



// SQL queries for both tables
$sql = Array(
    "SELECT * FROM drinks",
    "SELECT * FROM orders",
    "SELECT * FROM order_history"
);

$csvFiles = [];

echo "<h2>CSV Data Preview</h2>";
echo "<ul>";

foreach($sql as $query){

    // Match table name from query
    preg_match('/from\s+(\S+)/i', $query, $matches);
    $tableName = $matches[1];

    // Create a temporary file
    $filepath = tempnam(__DIR__ . "/tmp",$tableName . "_csv_");
    $tmpFile = basename($filepath);

    // Store temporary file path to download later
    $csvFiles[] = $tmpFile;

    // Open the temporary file for writing
    $output = fopen($filepath, 'w');

    // Execute query
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Write column headers to the CSV
    if (!empty($result)) {
        fputcsv($output, array_keys($result[0]));
    }

    // Write each row to the CSV
    foreach ($result as $row) {
        fputcsv($output, $row);
    }

    // Close the temporary file
    fclose($output);

    // Display the CSV content in the browser as a preview
    echo "<h3>Table: $tableName</h3>";
    echo "<pre>";

    // Open the file for reading and output its contents
    $csvData = file_get_contents($filepath);
    echo nl2br($csvData); // Display CSV data

    echo "</pre>";

    // Provide a link to download the file
    echo '<li><a href="./tmp/' . $tmpFile . '">Download ' . $tableName . ' CSV</a></li>';
}

echo "</ul>";
?>