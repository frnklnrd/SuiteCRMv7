<?php

require_once 'include/MVC/View/SugarView.php';
require_once "modules/MySettings/TabController.php";
require_once 'modules/Kanban/license/OutfittersLicense.php';

class KanbanViewKanban extends SugarView
{

    public $options = array(
        'show_header' => true,
        'show_title' => true,
        'show_subpanels' => false,
        'show_search' => true,
        'show_footer' => false,
        'show_javascript' => true,
        'view_print' => false,
    );
    public $menuModules = array(
        'Tasks' => 'Task',
        'Meetings' => 'Meeting',
        'Calls' => 'Call',
        'Missions' => 'Mission',
    );
    public $menuStatic = array(
        'plans',
        'history',
        'details',
        // aromero: añadir enlace al menú contextual
        'mylink',
    );

    protected function getBean()
    {
        if (!empty($this->bean->id)) {
            return $this->bean;
        }

        global $current_user;

        $where = array();
        $where[] = 'assigned_user_id = ' . $this->bean->db->quoted($current_user->id);
        $where[] = 'type = ' . $this->bean->db->quoted('personal');

        $kanbanRepository = BeanFactory::getBean('Kanban');
        $entities = $kanbanRepository->get_full_list("", implode(' AND ', $where));

        if (!empty($entities)) {
            return current($entities);
        }

        return BeanFactory::getBean('Kanban');
    }

    protected function getColumns()
    {
        return $this->getBean()->getColumnsWithItems();
    }

    protected function getLines()
    {
        return $this->getBean()->getColumnsWithHorizontalLines();
    }

    protected function getContextmenuItems()
    {
        global $current_user;
        global $beanList;
        global $mod_strings;

        $controller = new TabController();
        $systabs = $controller->get_tabs_system();

        $menuItems = array();

        foreach ($this->menuModules as $module => $bean) {
            if (!ACLAction::userHasAccess($current_user->id, $module, 'create')) {
                continue;
            }

            if (!isset($beanList[$module])) {
                continue;
            }

            if (!array_key_exists($module, $systabs[0])) {
                continue;
            }

            $menuItems[] = array(
                'class' => 'kanban-panel-menu-items-create-' . strtolower($bean),
                'label' => $mod_strings['LBL_CREATE_' . strtoupper($bean)],
            );
        }

        foreach ($this->menuStatic as $item) {
            $menuItems[] = array(
                'class' => 'kanban-panel-menu-items-' . strtolower($item),
                'label' => $mod_strings['LBL_' . strtoupper($item)],
            );
        }
        return $menuItems;
    }

    protected function getKanbans()
    {
        global $current_user, $moduleList;

        $where = array();
        $where[] = 'assigned_user_id = ' . $this->bean->db->quoted($current_user->id);
        $where[] = 'type = ' . $this->bean->db->quoted('general');

        $kanbanRepository = BeanFactory::getBean('Kanban');
        $entities = $kanbanRepository->get_full_list("", implode(' OR ', $where));

        $options = array();

        if (!$entities) {
            return $options;
        }

        foreach ($entities as $entity) {
            if (array_search($entity->target_module, $moduleList) === false) {
                continue;
            }

            $options[$entity->id] = array(
                'name' => htmlspecialchars($entity->name),
                'self_name' => htmlspecialchars($entity->self_name),
                'id' => htmlspecialchars($entity->id),
                'type' => htmlspecialchars($entity->type),
            );
        }

        return $options;
    }

    protected function getCloseColumns()
    {
        return $this->getBean()->getCloseColumns();
    }

    public function display()
    {
        global $current_user;

        //$validate_license = OutfittersLicense::isValid('Kanban', $current_user->id);
        $validate_license = true;
        if ($validate_license !== true) {
            echo $this->ss->fetch('modules/Kanban/tpls/kanban_license.tpl');

            return;
        }

        $bean = $this->getBean();
        $this->ss->assign('admin', is_admin($current_user));
        $this->ss->assign('kanbans', $this->getKanbans());
        // aromero: añadir variables para varias filas de kanban
        $this->ss->assign('lines', $this->getLines());
        //$this->ss->assign('columns', $this->getColumns());
        $this->ss->assign('closeColumns', $this->getCloseColumns());
        $this->ss->assign('record', $bean->id);
        $this->ss->assign('name', $bean->self_name ? $bean->self_name : $bean->name);
        $this->ss->assign('target_module', $bean->target_module);
        $this->ss->assign('contextmenu', $this->getContextmenuItems());
        //---------------------------------
        // PW - custom code
        $this->ss->assign('color_values', $bean->color_values);
        //---------------------------------
        // aromero: nuevo tpl para varias filas de kanban
        echo $this->ss->fetch('modules/Kanban/tpls/kanban-horizontal-divisions.tpl');
        //echo $this->ss->fetch('modules/Kanban/tpls/kanban.tpl');
    }

}
