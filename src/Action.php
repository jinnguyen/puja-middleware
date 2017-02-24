<?php
namespace Puja\Middleware;
class Action
{
    protected $controller;
    public function __construct(Controller $controller)
    {
        $this->controller = $controller;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function run()
    {

    }
}