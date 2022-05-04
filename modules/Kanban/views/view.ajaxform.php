<?php

require_once 'include/EditView/EditView2.php';
require_once 'include/DetailView/DetailView2.php';
require_once 'include/SubPanel/SubPanelTiles.php';
require_once 'modules/Kanban/SubpanelHandler.php';

class KanbanViewAjaxForm extends SugarView
{

    public $view;
    public $type = 'edit';
    public $templateFile = 'include/EditView/EditView.tpl';
    public $showTitle = false;
    public $subpanel_only = false;
    public $target_bean;

    function ViewEdit()
    {
        parent::SugarView();
    }

    function ViewDetail()
    {
        parent::SugarView();
    }

    public function preDisplay()
    {
        $this->view = $this->getView();
        $this->view->ss = & $this->ss;

        $this->module = $this->getBean()->module_name;
        $metadataFile = $this->getMetaDataFile();

        $this->view->setup($this->getBean()->module_name, $this->getBean(), $metadataFile, get_custom_file_if_exists($this->templateFile));
    }

    function display()
    {
        $this->view->process();

        if (!$this->subpanel_only) {
            echo $this->view->display(false);
        }

        $this->displaySubpanel();
    }

    protected function getBean()
    {
        if ($this->target_bean) {
            return $this->target_bean;
        }

        $bean = BeanFactory::getBean($_REQUEST['target']['module']);
        if (!empty($_REQUEST['target']['record'])) {
            $bean->retrieve($_REQUEST['target']['record']);
        }

        $this->target_bean = $bean;

        return $bean;
    }

    protected function getView()
    {
        if (!empty($_REQUEST['view'])) {
            switch ($_REQUEST['view']) {
                case 'subpanel':
                    $this->subpanel_only = true;
                case 'detail':
                    $this->type = 'detail';
                    $this->templateFile = 'include/DetailView/DetailView.tpl';
                    return new DetailView2();

                case 'edit':
                default:
                    $this->type = 'edit';
                    $this->templateFile = 'include/EditView/EditView.tpl';
                    return new EditView();
            }
        }

        return new EditView();
    }

    protected function displaySubpanel()
    {
        if ($this->type !== 'detail') {
            return;
        }

        if (!isset($_REQUEST['subpanel_type'])) {
            return;
        }

        $subpanelHandler = new SubpanelHandler();
        $subpanelHandler->displaySubpanel($this->getBean());
    }
}
