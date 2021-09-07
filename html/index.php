<?php

if (!function_exists('getenv_docker')) {
        // https://github.com/docker-library/wordpress/issues/588 (WP-CLI will load this file 2x)
        function getenv_docker($env, $default) {
                if ($fileEnv = getenv($env . '_FILE')) {
                        return rtrim(file_get_contents($fileEnv), "\r\n");
                }
                else if (($val = getenv($env)) !== false) {
                        return $val;
                }
                else {
                        return $default;
                }
        }
}
function ipCIDRCheck ($IP, $CIDR) {
    list ($net, $mask) = split ("/", $CIDR);

    $ip_net = ip2long ($net);
    $ip_mask = ~((1 << (32 - $mask)) - 1);

    $ip_ip = ip2long ($IP);

    $ip_ip_net = $ip_ip & $ip_mask;

    return ($ip_ip_net == $ip_net);
  }
  echo ipCheck ("192.168.1.23", "192.168.1.0/24");


function isAllowed($ip){
    $whitelist = array(getenv_docker('MW_IP_WHITELIST',  'localhost'));

    // If the ip is matched, return true
    if(in_array($ip, $whitelist)) {
        return true;
    }

    foreach($whitelist as $i){
        $wildcardPos = strpos($i, "*");

        // Check if the ip has a wildcard
        if($wildcardPos !== false && substr($ip, 0, $wildcardPos) . "*" == $i) {
            return true;
        }
    }

    return false;
}

(! isAllowed($_SERVER['REMOTE_ADDR'])) {
    header('Location: http://google.com');


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
