<?php
require_once('core.php');
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <title><?=TITLE;?></title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/output.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-xs-10 col-xs-offset-1">
                <header class="row">
                    <div class="col-xs-12 text-center">
                        <div class="timer" id="timer">4 min. 08 sec.</div>
                    </div>
                </header>
                <main class="row">
                    <div class="col-xs-12">
                        <div class="prices-background">
                            <div class="prices col-xs-5">
                                <div class="prices-loader">
                                    <img src="./images/loading.gif" />
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 text-underline">
                                        <u>Prijzen</u>
                                    </div>
                                </div>
                                <div class="row" id="price-list">
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <footer class="row">
                    <div class="col-xs-12">
                        <canvas id="statistics"></canvas>
                    </div>
                </footer>
            </div>
        </div>
    </div>
    <div class="bn-overlay bn-hidden">
        <div class="bn-text">
            <div class="bn-breaking">Breaking</div>
            <div class="bn-news">news</div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="./js/statistics.js"></script>
    <script src="./js/index.js"></script>
</body>
</html>
