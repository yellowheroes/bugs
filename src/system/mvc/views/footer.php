<?php
namespace yellowheroes\projectname\system\mvc\views;

use yellowheroes\projectname\system\config as config;
use yellowheroes\projectname\system\libs as libs;

$footer = new libs\BootWrap();
/* other is anything that comes after </body> element */
$other = null;
$org = config\Config::ORGNAME;
$footer->setFooter($org, null); // copyright notice is appended by default
echo $footer->getFooter();