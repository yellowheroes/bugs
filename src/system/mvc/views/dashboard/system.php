<?php
namespace yellowheroes\jimmy\system\mvc\views;

use yellowheroes\jimmy\system\config as config;
use yellowheroes\jimmy\system\libs as libs;

/**
 * check if user is logged-in and has appropriate (admin) priviliges
 * - if not, redirect user back to src/app/index
 * - header.php reads $_SESSION['access_denied'] to determine if user was redirected
 * and, if so, shows an alert to inform user her credentials were insufficient
 * to visit e.g. 'dashboard/createblog'.
 *
 * @var $userType     - the variable in header.php that contains userType: admin, editor or guest(visitor)
 */
if ($userType !== 'admin') {
    $session->set('access_denied', 'dashboard_system_php'); // set key, value===location
    header('Location: ' . $index);
    exit();   
}
/**
 * header.php has instantiated:
 * 1. a config\Config() object called $config - so we can access the paths here with $config->path[...]
 * 2. a libs\SessionManager() object called $session - so we can get and set session variables here
 */

$cookieLife = \ini_get('session.cookie_lifetime');
$useCookies = \ini_get('session.use_cookies');
$useOnlyCookies = \ini_get('session.use_only_cookies');
$secure = \ini_get('session.cookie_secure');
$httponly = \ini_get('session.cookie_httponly');
$strictMode = \ini_get('session.use_strict_mode');

echo 'cookie lifetime (default in .ini): ' . $cookieLife;
echo '<br />';
echo 'use cookies (default in .ini): ' . $useCookies;
echo '<br />';
echo 'use only cookies (default in .ini): ' . $useOnlyCookies;
echo '<br />';
echo 'cookie secure (default in .ini): ' . $secure;
echo '<br />';
echo 'httponly  (default in .ini):  ' . $httponly;
echo '<br />';
echo 'session use strict mode  (default in .ini):  ' . $strictMode;
echo '<br />';