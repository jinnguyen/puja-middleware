<?php
namespace Puja\Middleware;


class Bootstrap
{
    public function __construct()
    {
        // Init error handle
        new \Puja\Error\ErrorManager(
            \Puja\Configure\Configure::getInstance('error_handler')->getAll()
        );


        $this->init();
    }

    protected function init()
    {

    }
}