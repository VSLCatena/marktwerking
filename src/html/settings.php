<?php

if( ! function_exists('getenv_docker'))
{
global $debug;
$debug=0;
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
}
define( 'DB_DRIVER', getenv_docker('MW_DB_DRIVER', 'mysql') ); 
define( 'DB_HOST', getenv_docker('MW_DB_HOST', 'db') );
define( 'DB_USERNAME', getenv_docker('MW_DB_USERNAME', 'dbuser') );
define( 'DB_PASSWORD', getenv_docker('MW_DB_PASSWORD', 'dbpassword') );
define( 'DB_DATABASE', getenv_docker('MW_DB_DATABASE', 'marktwerking') );

define( 'BAR_PASSWORD', getenv_docker('MW_BAR_PASSWORD', 'MW2021') );
define( 'TITLE', getenv_docker('MW_TITLE', 'Marktwerking') );
define( 'MW_DEBUG', getenv_docker('MW_DEBUG', False) );