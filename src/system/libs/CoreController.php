<?php
namespace yellowheroes\jimmy\system\libs;

class CoreController
{

// properties
    protected $view;    // hold a CORE VIEW OBJECT for controllers
    protected $model;   // hold a CORE MODEL OBJECT for controllers

// methods
    public function __construct()
    {
        // instantiate CORE VIEW - store CORE VIEW object in property $view
        $this->view = new CoreView();
        // connect to all available flat file databases and start session
        $this->model = new CoreModel();
    }
}
