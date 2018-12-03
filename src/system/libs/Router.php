<?php
namespace yellowheroes\projectname\system\libs;

use yellowheroes\projectname\system\config as config;

class Router
{

    public $config = null;
    // properties - default settings
    protected $controller = 'Index';
    protected $method = 'index';
    protected $params = [];
    // properties - route log file
    public $logController = '';
    public $logMethod = '';
    public $logParams = '';
    // properties session
    public $session = null;

    // methods
    public function __construct()
    {
        $this->config = new config\Config(); // access to $this->config::PROJECTNAME

        if (isset($_GET['url'])) {
            $this->parseUrl();
        }
    }

    public function parseUrl()
    {
        $url = $_GET['url'];

        // exploding $_GET['url'] by forward slash will return an array, which we store in $url.
        // $url array will contain [0]controller, [1]method, [2]params
        $url = explode('/', (filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL)));

        /*
         * CONTROLLER
         */
        if (isset($url[0])) {
            // strip .php extension
            // e.g. user sets index.php, we turn it into index - so we can instantiate the Controller - e.g. new Index();
            $strip = pathinfo($url[0]);
            $url[0] = $strip['filename'];
            $projectName = '';
            // dirname(__DIR__) === D:\Users\Robert\Documents\z_other\coding\MVC\MVC_Base_v50\src\system
            if (file_exists(dirname(__DIR__) . '/mvc/controllers/' . ucfirst($url[0]) . '.php')) {
                $this->logController = ucfirst($url[0]); // store controller for ROUTES_LOG file
                // namespace: we cannot use the namespace alias... not clear why
                // so we use the full namespace path to be able to instantiate the 'controllers' class
                $projectName = ($this->config::PROJECTNAME !== '') ? $this->config::PROJECTNAME : $this->config::APPFOLDER;
                $this->controller = 'yellowheroes\\' . $projectName . '\\system\\mvc\\controllers\\' . ucfirst($url[0]);
                unset($url[0]);
                // instantiate controller
                $this->controller = new $this->controller;
            } else { // use default controller - class 'Index.php'
                $this->logController = $this->controller; // store controller for ROUTES_LOG file
                $projectName = ($this->config::PROJECTNAME !== '') ? $this->config::PROJECTNAME : $this->config::APPFOLDER;
                $this->controller = 'yellowheroes\\' . $projectName . '\\system\\mvc\\controllers\\' . $this->controller;
                $this->controller = new $this->controller;
                unset($url[0]); // a controller is set, but doesn't exist - destroy $url[0]
            }
        }

        /**
         * METHOD
         */
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]); // a method is set, and exists in the controller - destroy $url[1]
            } else {
            unset($url[1]); // a method is set, but doesn't exist in the controller - destroy $url[1]
            }
        }
        $this->logMethod = $this->method; // store method for ROUTES_LOG file

        /**
         * PARAMETERS
         * 
         * if there are still elements left in $url array, they are the parameters
         * the first element in the parameter array, if set, is any other-than-default ('index.php') view-page
         * this enables routing to a views-directory that is home to multiple view-pages
         * e.g. a views-directory 'crud', contains multiple - other than index.php - view-pages: 'create.php', 'delete.php', 'read.php', 'update.php'
         */
        if (count($url) > 0) {
            $this->params = array_values($url); // array_values automatically rebases the array to 0
        }

        // clear $_GET array
        unset($_GET);

        // write route to log file
        $this->logRoute();
               
         /**
         * INVOKE ACTION
         */
        call_user_func_array([$this->controller, $this->method], $this->params);
    }
    
    public function logRoute()
    {
        // write the route to log file
        $log = new Log();
        $fileName = 'ROUTES_LOG';
        $logMsg = "CONTROLLER: " . $this->logController . ".php" . "\n" .
            "METHOD: " . $this->logMethod . "\n";
        foreach ($this->params as $key => $value) {
            $this->logParams .= "PARAM: " . $value . "\n";
        }
        $logMsg .= ($this->logParams !== '') ? $this->logParams : "PARAMS: N/A";
        $log->store($fileName, $logMsg);
    }
}
