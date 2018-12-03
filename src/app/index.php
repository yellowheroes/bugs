<?php
/**
 * Created by Yellow Heroes
 * Project: base0
 * File: index.php
 * User: Robert
 * Date: 26/11/2018
 * Time: 00:18
 */
namespace yellowheroes\projectname\app;

use yellowheroes\projectname\system\libs as libs;
use yellowheroes\projectname\system\mvc\controllers as controllers;

/*
 * activate Composer autoloading
 */
require dirname(__DIR__, 2) . '/vendor/autoload.php';

/*
 * on each client 'request' for a VIEW,
 * e.g. a click on a button to go 'home' or go 'contact' or...
 * the REWRITE rule turns the request into:
 * ...src/app/index.php?url=controller/action/param
 * so we always arrive in this script (index.php).
 *
 * Here, we instantiate our Router
 * which based on the query string in 'url'
 * invokes a controller's method(action) (with param(s))
 *
 * Each and any invoked controller will finally render the requested VIEW
 */
$invoke = new libs\Router();