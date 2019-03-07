<?php
namespace yellowheroes\bugs\system\libs;

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
        // connect to database and start session
        $this->model = new CoreModel();
    }
}
