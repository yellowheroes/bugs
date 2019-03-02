<?php
namespace yellowheroes\jimmy\system\mvc\controllers;

use yellowheroes\jimmy\system\libs as libs;
use yellowheroes\jimmy\system\config as config;
use yellowheroes\jimmy\system\mvc\models as models;

class Login extends libs\CoreController
{

    function __construct()
    {
        parent::__construct(); // get access to CoreView.php and CoreModel.php
    }

    /**
     * Utilize CoreView::render() to render a page-view in a views-directory
     * 
     * render a page-view as follows:
     * - className (Login in this case) is automatically set as the 'views-directory' - lcfirst() - a views-directory can contain multiple page-views
     * - $page is a specific page-view in the views-directory
     * - $id can be used for specifying a paragraph in a page-view, or e.g. targeting a database record (the page-view will deal with the $id value as it sees fit)
     */
    public function index($page = 'index', $id = null)
    {
        $className = \get_class(); // get_class() returns the namespace\className
        $className = \explode('\\', $className); // so we explode into elements based on '\'
        $className = \end($className); // get the last element of the array
        $viewsDir = \lcfirst($className); // our views-directories' names all start with lower-case
        
        $this->view->render($viewsDir, $page, $id); // CoreView.php will default $page to 'index.php' if the $page page-view does not exist
    }

    public function logout()
    {
        /**
         * user decided to log-out - get LoginModel.php to terminate user-connection
         */
        $logout = (new models\LoginModel())->logout();
    }
}
