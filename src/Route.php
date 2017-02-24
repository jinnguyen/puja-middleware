<?php
namespace Puja\Middleware;
class Route
{
    protected $router;
    protected $uri;
    public function __construct($uri, array $config)
    {
        $this->uri = $uri;
        $this->router = new \Puja\Route\Route($config);
        $this->router->build();
    }

    protected function rewrite($uri)
    {
    }

    public function getRoute()
    {
        $uri = $this->rewrite($this->uri);
        if (empty($uri)) {
            $uri = $this->uri;
        }
        return $this->router->getRoute($uri);
    }
}