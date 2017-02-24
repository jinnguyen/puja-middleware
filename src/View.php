<?php
namespace Puja\Middleware;
use Puja\Configure\Configure;

class View
{
    protected $tplFile;
    protected $tplData;
    protected $contentType;

    protected $tpl;

    public function __construct()
    {
        $this->tpl = new \Puja\Template\Template(Configure::getInstance('TemplateEngine')->getAll());
    }

    public function addData($key, $values)
    {
        $this->tpl->add($key, $values);
    }


    public function parse($tplFile, $data = array(), $return = false, $contentType = 'text/html; charset=utf-8')
    {
        if ($return) {
            return $this->tpl->parse($tplFile, $data, $return, $contentType);
        }

        $this->tplFile = $tplFile;
        $this->tplData = $data;
        $this->contentType = $contentType;
    }

    public function render()
    {
        if (empty($this->tplFile)) {
            return null;
        }
        return $this->tpl->parse($this->tplFile, $this->tplData, true, $this->contentType);
    }
}