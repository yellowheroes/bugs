<?php

namespace yellowheroes\bugs\system\mvc\controllers;

use yellowheroes\bugs\system\libs as libs;
use yellowheroes\bugs\system\config as config;
use yellowheroes\bugs\system\mvc\models as models;

class Crud extends libs\CoreController
{
    // properties

    // methods
    public function __construct()
    {
        parent::__construct(); // $this->view = CORE VIEW object, $this->model = CORE MODEL object
    }

    /**
     * Utilize CoreView::render() to render a page-view in a views-directory
     *
     * render a page-view as follows:
     * - className (Crud in this case) is automatically set as the 'views-directory' - lcfirst() - a views-directory
     * can contain multiple page-views
     * - $page is a specific page-view in the views-directory
     * - $id can be used for specifying a paragraph in a page-view, or e.g. targeting a database record (the page-view
     * will deal with the $id value as it sees fit)
     */
    public function index($page = 'index', $id = null)
    {
        $className = \get_class(); // get_class() returns the namespace\className
        $className = \explode('\\', $className); // so we explode into elements based on '\'
        $className = \end($className); // get the last element of the array
        $viewsDir = \lcfirst($className); // our views-directories' names all start with lower-case

        $this->view->render($viewsDir, $page, $id); // CoreView.php will default $page to 'index.php' if the $page page-view does not exist
    }

    public function create($page = 'create')
    {
        $this->index($page);
    }

    public function read($id = null, $page = 'read')
    {
        $this->index($page, $id);
    }

    public function update($id = null, $page = 'update')
    {
        $this->index($page, $id);
    }

    public function delete($id = null, $page = 'delete')
    {
        $this->index($page, $id);
    }
}
