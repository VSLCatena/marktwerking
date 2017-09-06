<?php
$showForm=false;
$conn=false;

//creation of settings file.
if (!file_exists("./settings.php")) {
    $showForm = true;
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {
            $pdo = new PDO($_POST['DB_DRIVER'] . ':host=' . $_POST['DB_HOST'] . ';dbname=' . $_POST['DB_DATABASE'] . ';', $_POST['DB_USERNAME'], ($_POST['DB_PASSWORD']));
            // set the PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo("Yay connection!");
            $showForm = false;
            $conn = true;
            } catch (PDOException $e) {
            echo('Could not connect to the database:<br/><pre>' . $e . '</pre>');
            $conn=false;
            $showForm = true;
        }


        if ($conn == true) {
            $text = array(
                "<?php",
                "// Database",
                "define('DB_DRIVER', '" . $_POST['DB_DRIVER'] . "');",
                "define('DB_HOST', '" . $_POST['DB_HOST'] . "');",
                "define('DB_USERNAME', '" . $_POST['DB_USERNAME'] . "');",
                "define('DB_PASSWORD', '" . $_POST['DB_PASSWORD'] . "');",
                "define('DB_DATABASE', '" . $_POST['DB_DATABASE'] . "');",
                "\n// Bar password",
                "define('BAR_PASSWORD', '" . $_POST['BAR_PASSWORD'] . "');",
                "\n// Extras",
                "define('TITLE', 'Marktwerking');",
            );
            $settingsFile = fopen("./settings.php", "w") or die("Unable to open file!");
            foreach ($text as $key => $value) {
                fwrite($settingsFile, $value . "\n");
            }
            fclose($settingsFile);
            echo("<br>Settings have been written to file");

//            if ($_POST['table']==='true'){
//            //create table blabla
//            }
        }
//            echo("<pre>");
//            print_r($_POST);
//            echo("</pre>");



    $pdo = null;
    };
    if ($showForm==true){
        ?>
        <h4>Databaseinstellingen</h4>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            DB_DRIVER: <input type="text" name="DB_DRIVER" value="mysql"><br>
            DB_HOST: <input type="text" name="DB_HOST" value="localhost"><br>
            DB_USERNAME: <input type="text" name="DB_USERNAME"><br>
            DB_PASSWORD: <input type="password" name="DB_PASSWORD"><br>
            DB_DATABASE:<input type="text" name="DB_DATABASE"><br><br>
            BAR_PASSWORD:<input type="password" name="BAR_PASSWORD"><br><br>
            Tabellen aanmaken?:
            <input type="radio" name="table"
                   value="true" checked="checked">Ja
            <input type="radio" name="table"
                   value="false">Nee<br>
            <input type="submit" name="submit" value="Submit">
        </form>
        <?php
    }
    die();
}