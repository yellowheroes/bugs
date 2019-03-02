<?php
namespace yellowheroes\jimmy\system\libs;

use yellowheroes\jimmy\system\config as config;

/**
 * DEVELOPMENT ENVIRONMENT
 */
class CoreView
{
    // properties

    // methods
    public function __construct()
    {
    }

    /**
     * @param string $viewsDir  - the views-directory contains a category of view-pages (e.g. 'crud' is the viewsDir for database related view-pages)
     * @param string $page      - the view-page (e.g. create.php or delete.php in views-directory 'crud')
     * @param type $id          - $id used in database reference (target a record) or possibly reference a #id on the view-page
     * @param mixed $param1, 2, 3 - we added extra parameters that can be sent to the view-pages (e.g. data from models)
     */
    public function render($viewsDir, $page='index', $id=null, $param1 = null, $param2 = null, $param3 = null)
    {
        /** does the page-view exist? */
        if (!file_exists(dirname(__DIR__) . '/mvc/views/' . $viewsDir . '/' . $page . '.php')) {
            $page = 'index'; // view doesn't exist, we set the $page argument given to CoreView::render() to default 'index'
        }
        /** use ob_start(), ob_end_flush() to avoid Warning: "Headers already sent" or "Cannot modify header information" */
        \ob_start();
        require dirname(__DIR__) . '/mvc/views/header.php';
        require dirname(__DIR__) . '/mvc/views/' . $viewsDir . '/' . $page . '.php';
        require dirname(__DIR__) . '/mvc/views/footer.php';
        \ob_end_flush();
    }
}