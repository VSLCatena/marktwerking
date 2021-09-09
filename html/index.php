<?php
$GLOBALS['debug'] = false;
if ($GLOBALS['debug']) {
	error_reporting(E_ALL);
	ini_set('display_errors',1);
}

function getenv_docker($env, $default) {
		if ($fileEnv = getenv($env . '_FILE')){ 
				if($GLOBALS['debug']){echo "<br>file:";print_r(rtrim(file_get_contents($fileEnv), "\r\n"));}
				return rtrim(file_get_contents($fileEnv), "\r\n");
		}
		else if (($val = getenv($env)) !== false) {
				if($GLOBALS['debug']){echo "<br>getenv:";print_r($val);}
				return $val;
		}
		else {
				if($GLOBALS['debug']){echo "<br>default:";print_r($default);}
				return $default;
		}
}

function ipCIDRCheck ($IP, $CIDR) {
    list ($net, $mask) = explode("/", $CIDR); 

    $ip_net = ip2long ($net);
    $ip_mask = ~((1 << (32 - $mask)) - 1);

    $ip_ip = ip2long ($IP);

    $ip_ip_net = $ip_ip & $ip_mask;

    return ($ip_ip_net == $ip_net);
  }


function isAllowed($ip){
    $whitelist = array(getenv_docker('MW_IP_WHITELIST',  array('127.0.0.1','192.168.0.0/16')));

    // If the ip is matched, return true
    if(in_array($ip, $whitelist)) {
		if($GLOBALS['debug']){echo "<br>IP in whitelist";}
        return true;
    }

    foreach($whitelist as $i){
        $wildcardPos = strpos($i, "*");
        // Check if the ip has a wildcard
        if($wildcardPos !== false && substr($ip, 0, $wildcardPos) . "*" == $i) {
            if($GLOBALS['debug']){echo "<br>IP in wildcard";}
			return true;
        }
        if(ipCIDRCheck ($ip, $i)){
			if($GLOBALS['debug']){echo "<br>IP in CIDR";}
            return true;
        }

    }

    return false;
}

if(! isAllowed($_SERVER['REMOTE_ADDR'])) {
	if($GLOBALS['debug']){echo "<br>REMOTE_ADDR:"; print_r($_SERVER['REMOTE_ADDR']);}
    header('Location: http://google.com');
} else{
	if($GLOBALS['debug']){echo "<br>REMOTE_ADDR:"; print_r($_SERVER['REMOTE_ADDR']);}
}
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

