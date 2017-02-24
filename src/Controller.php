<?php
namespace Puja\Middleware;
use Puja\Configure\Configure;

class Controller
{
    const HTTP_RESPONSE_CODE_302 = 302;
    const HTTP_RESPONSE_CODE_301 = 301;
    protected $view;
    protected $moduleId;
    protected $controllerId;
    protected $actionId;
    protected $uri;
    protected $params;
    public function __construct(View $view, $moduleId, $controllerId, $actionId, $uri, $params)
    {
        $this->view = $view;
        $this->view->addData('config', Configure::getInstance('application')->getAll());

        $this->moduleId = $moduleId;
        $this->controllerId = $controllerId;
        $this->actionId = $actionId;
        $this->uri = $uri;
        $this->params = $params;
    }

    public function beforeLoadAction()
    {

    }

    public function afterLoadAction()
    {
        $this->end(
            $this->view->render()
        );
    }

    public function render($tplFile, $data = array(), $return = false, $contentType = 'text/html; charset=utf-8')
    {
        return $this->view->parse($tplFile, $data, $return, $contentType);
    }

    public function json($data, $contentType = 'application/json')
    {
        header('Content-type:' . $contentType);
        $this->end(json_encode($data));
    }

    public function getView()
    {
        return $this->view;
    }

    public function redirect($url = null, $statusCode = self::HTTP_RESPONSE_CODE_302)
    {
        if (empty($url)) {
            return false;
        }
        header('Location:' . $url, true, $statusCode);
    }

    public static function actions()
    {

    }

    public function end($message = null)
    {
        if ($message) {
            echo $message;
        }

        exit;
    }

    public function getModuleId()
    {
        return $this->moduleId;
    }

    public function getControllerId()
    {
        return $this->controllerId;
    }

    public function getActionId()
    {
        return $this->actionId;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getParam($key, $defaultValue = null)
    {
        if (array_key_exists($key, $this->params)) {
            return $this->params[$key];
        }

        return $defaultValue;
    }


}