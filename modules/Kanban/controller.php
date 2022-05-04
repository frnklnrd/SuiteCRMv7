<?php

class KanbanController extends SugarController
{
    public $action_remap = array();

    public function KanbanController()
    {
        parent::SugarController();
    }

    public function action_detailview()
    {
        $this->view = 'kanban';
    }

    public function action_index()
    {
        $this->view = 'list';
    }

    public function action_ajaxform()
    {
        $this->view = 'ajaxform';
    }

}
