<?php
require_once('core.php');

if (MW_DEBUG == True) {
	error_reporting(E_ALL);
	ini_set('display_errors',1);
}


function ipCIDRCheck ($IP, $CIDR) {
    list ($net, $mask) = explode ('/', $CIDR);
    
    $ip_net = ip2long ($net);
    $ip_mask = ~((1 << (32 - $mask)) - 1);

    $ip_ip = ip2long ($IP);

    return (($ip_ip & $ip_mask) == ($ip_net & $ip_mask));
}

function isAllowed($ip){
    // If the ip is matched, return true
    if(in_array($ip, MW_IP_WHITELIST)) {
		if(MW_DEBUG == True){echo "\nIP is in whitelist\n";}
        return true;
    }

    foreach(MW_IP_WHITELIST as $i){
        $wildcardPos = strpos($i, "*");
        // Check if the ip has a wildcard
        if($wildcardPos !== false && substr($ip, 0, $wildcardPos) . "*" == $i) {
            if(MW_DEBUG == True){echo "\nIP $ip in wildcard\n";}
			return true;
        }
        if(str_contains($i,"/")){
            if(ipCIDRCheck ($ip, $i)){
			    if(MW_DEBUG == True){echo "\nIP $ip in CIDR $i\n";}
                return true;
            }
        }
    }
	if(MW_DEBUG == True){echo "\nIP $ip not in whitelist\n";}
    return false;
}

if(! isAllowed($_SERVER['REMOTE_ADDR'])) {
    if(MW_DEBUG !=True){
        header('Location: about:blank');
    } else {
        echo "<pre>";
        echo "REMOTE_ADDR: " . $_SERVER['REMOTE_ADDR'] . "<br>" . "MW_IP_WHITELIST:";
        print_r(MW_IP_WHITELIST);
        echo "</pre>";
    }
} else{
    //
}


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

