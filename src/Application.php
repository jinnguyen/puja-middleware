<?php
namespace Puja\Middleware;
use Puja\Configure\Configure;
class Application
{
    protected $appVersion = '1.0';
    protected $router;
    protected $config;
    public function __construct($appDir)
    {
        header('X-Powered-By: PUJA ' . Version::VERSION);
        header('X-Puja-Version: ' . Version::VERSION);
        header('X-Puja-Url: https://github.com/jinnguyen/puja');

        // Load configure
        new Configure(array($appDir . '/config/'));
        $this->config = Configure::getInstance('application');
        $this->bootstrap();

        $routeCls = $this->config->get('Route', '\\Puja\\Middleware\\Route');
        $this->router = $this->getRouteInstance(
            new $routeCls(
                substr(
                    $_SERVER[$this->config->get('request_uri', 'REQUEST_URI')],
                    strlen($this->config->get('path_dir', 0))
                ),
                Configure::getInstance('router')->getAll()
            )
        );
    }

    protected function bootstrap()
    {
        $bootstrapCls = $this->config->get('Bootstrap', '\\Puja\\Middleware\\Bootstrap');
        if ($bootstrapCls && class_exists($bootstrapCls)) {
            new $bootstrapCls();
        }
    }

    public function run()
    {
        $route = $this->router->getRoute();
        if (empty($route)) {
            $route = array('router' => $this->config->get('error404', null));
            if (empty($route['router']['annotation'])) {
                $route['router']['annotation'] .= 'Action';
            }
        }

        $viewCls = $this->config->get('View', '\\Puja\\Middleware\\View');
        $controller = $this->getControllerInstance(
            new $route['router']['controller'](
                $this->getViewInstance(new $viewCls),
                $route['router']['moduleId'],
                $route['router']['controllerId'],
                $route['router']['actionId'],
                $route['uri'],
                (empty($route['params']) ? array() : $route['params']) + $_REQUEST
            )
        );
        $controller->beforeLoadAction();

        if ($route['router']['annotation']) {
            $action = $this->getActionInstance(new $route['router']['action']($controller));
            $action->run();
        } else {
            $controller->$route['router']['action']();
        }
        $controller->afterLoadAction();
        $controller->end();
    }

    protected function getActionInstance(Action $action)
    {
        return $action;
    }

    protected function getControllerInstance(Controller $controller)
    {
        return $controller;
    }

    protected function getViewInstance(View $view)
    {
        return $view;
    }

    protected function getRouteInstance(Route $route)
    {
        return $route;
    }
}