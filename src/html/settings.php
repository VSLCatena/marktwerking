<?php
define('DEBUG',False);

if (!function_exists('getenv_docker')) {
	function getenv_docker($env, $default)
	{
		if (($val = getenv($env)) !== false) {
			if (DEBUG) {
				echo "<br>getenv:";
				print_r($val);
			}
			return $val;
		} else {
			if (DEBUG) {
				echo "<br>default:";
				print_r($default);
			}
			return $default;
		}
	}
}
define('DB_DRIVER', getenv_docker('MW_DB_DRIVER', 'mysql'));
define('DB_HOST', getenv_docker('MW_DB_HOST', 'db'));
define('DB_USERNAME', getenv_docker('MW_DB_USERNAME', 'dbuser'));
define('DB_PASSWORD', getenv_docker('MW_DB_PASSWORD', 'dbpassword'));
define('DB_DATABASE', getenv_docker('MW_DB_DATABASE', 'marktwerking'));

define('BAR_PASSWORD', getenv_docker('MW_BAR_PASSWORD', 'MW2023'));
define('TITLE', getenv_docker('MW_TITLE', 'Marktwerking'));
define('MW_DEBUG',(bool) getenv_docker('MW_DEBUG', False));
$whitelist = str_replace('"', '', getenv_docker('MW_IP_WHITELIST', '127.0.0.1'));
define('MW_IP_WHITELIST', array_unique(array_merge(explode(',', $whitelist), array('127.0.0.1'))));
