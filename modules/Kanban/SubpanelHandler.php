<?php

require_once 'include/ListView/ListView.php';

class SubpanelHandler
{
    private $fields = array(
        'default' => array(
            'date_modified',
        ),
        'activities' => array(
            'status',
            'date_start' => array(
                'date_start'
            ),
            'date_due' => array(
                'date_due',
                'date_end',
            ),
            'date_modified',
            'description',
        ),
    );
    private $relatedTab = array(
        'activities' => 'missions_planned',
        'history' => 'missions_history',
    );

    public function displaySubpanel($bean)
    {
        $subPanelTiles = new SubPanelTiles($bean, $bean->module_name);
        $subpanel_data = array(
            'headers' => array(),
            'data' => array(),
        );

        $_REQUEST['record'] = $bean->id;
        $_REQUEST['module'] = $bean->module_name;

        $tab = $_REQUEST['subpanel_type'];
        $tabs = $subPanelTiles->getTabs(true, '');

        if (array_search($tab, $tabs) !== false) {
            $subpanel_data = $this->getSubpanelData($subPanelTiles, $bean, $tab);
        } elseif (array_search($this->relatedTab[$tab], $tabs) !== false) {
            $subpanel_data = $this->getSubpanelData($subPanelTiles, $bean, $this->relatedTab[$tab], true);
        }

        $ss = new Sugar_Smarty();
        $ss->assign('headers', $subpanel_data['headers']);
        $ss->assign('subpanel_data', $subpanel_data['data']);

        echo $ss->fetch('modules/Kanban/tpls/kanban-subpanel.tpl');
    }

    public function getSubpanelData($subPanelTiles, $bean, $tab, $is_custom = false)
    {
        global $app_list_strings;
        global $beanList;

        $subPanel = $subPanelTiles->subpanel_definitions->load_subpanel($tab);

        $ListView = new ListView();
        $ListView->initNewXTemplate('include/SubPanel/SubPanelDynamic.html', $subPanel->mod_strings);
        $ListView->source_module = $bean->module_name;
        $ListView->subpanel_module = $subPanel->name;

        $tab_data = $ListView->processUnionBeans($bean, $subPanel, $subPanel->name . "_CELL");

        if (isset($beanList['Missions']) && !$is_custom) {
            $addTab = $this->relatedTab[$tab];

            $subPanel = $subPanelTiles->subpanel_definitions->load_subpanel($addTab);

            $ListView = new ListView();
            $ListView->initNewXTemplate('include/SubPanel/SubPanelDynamic.html', $subPanel->mod_strings);
            $ListView->source_module = $bean->module_name;
            $ListView->subpanel_module = $subPanel->name;

            $add_data = $ListView->processUnionBeans($bean, $subPanel, $subPanel->name . "_CELL");
        } else {
            $add_data['list'] = array();
        }

        $subpanel_data = array_merge($tab_data['list'], $add_data['list']);


        $data = array();
        $headers = array();
        switch ($tab) {
            case 'activities':
            case 'history':
            case 'missions_planned':
            case 'missions_history':
                $fields = $this->fields['activities'];
                break;

            default:
                $fields = $this->fields['default'];
                break;
        }

        foreach ($subpanel_data as $key => $value) {
            $value->retrieve($value->id);
            $data[$key] = array();

            $data[$key]['name'] = '<a href="index.php?action=DetailView&module=' . $value->module_dir . '&record=' . $key . '" target="_blank">' . $value->name . '</a>';
            $headers['name'] = rtrim(translate($value->field_name_map['name']['vname'], $value->module_dir), ': ');

            foreach ($fields as $field_key => $field) {
                if (is_array($field)) {
                    $not_used = true;
                    foreach ($field as $target_field) {
                        if (!isset($value->$target_field)) {
                            continue;
                        }

                        $field = $target_field;
                        $not_used = false;
                        break;
                    }

                    if ($not_used) {
                        if (!isset($headers[$field_key])) {
                            $headers[$field_key] = '';
                        }
                        $data[$key][$field_key] = '';
                        continue;
                    }
                } else {
                    $field_key = $field;
                }

                if (!isset($value->field_name_map[$field])) {
                    if (!isset($headers[$field_key])) {
                        $headers[$field_key] = '';
                    }
                    $data[$key][$field_key] = '';
                    continue;
                }

                $headers[$field_key] = rtrim(translate($value->field_name_map[$field]['vname'], $value->module_dir), ': ');

                if ($value->field_name_map[$field]['type'] == 'enum' || $value->field_name_map[$field]['type'] == 'multienum') {
                    $data[$key][$field_key] = $app_list_strings[$value->field_name_map[$field]['options']][$value->$field];
                } else {
                    $data[$key][$field_key] = $value->$field;
                }
            }

        }

        foreach ($headers as $field => $label) {
            if ($label === '') {
                unset($headers[$field]);
                foreach($data as $id => $props) {
                    unset($data[$id][$field]);
                }
            }
        }

        return array(
            'headers' => $headers,
            'data' => $data,
        );
    }
}
