<?php
require_once 'modules/kan_sorts/kan_sorts.php';

class Kanban extends Basic
{

    public $table_name = 'kanban';
    public $module_name = 'Kanban';
    public $module_dir = 'Kanban';
    public $object_name = 'Kanban';

    public $name;
    public $self_name;
    public $target_module;
    public $target_field;

    public function getModules($bean, $field, $value, $view)
    {
        global $app_list_strings, $moduleList, $beanList, $modInvisList;
        $module_list = array_merge($moduleList, $modInvisList);
        foreach ($moduleList as $module) {
            $res[$module] = $app_list_strings['moduleList'][$module];
        }
        asort($res);

        return $res;
    }

    public function getEnumFields($bean, $field = 'id', $value = null, $view = 'list')
    {
        $targetBean = BeanFactory::getBean($bean->target_module);

        if (!$targetBean) {
            return array();
        }

        $fields = $targetBean->getFieldDefinitions();
        $options = array(
            '' => '',
        );
        foreach ($fields as $field) {
            if ($field['type'] !== 'enum') {
                continue;
            }
            $options[$field['name']] = rtrim(translate($field['vname'], $targetBean->module_name), ': ');
        }

        return array_unique($options);
    }

    public function getFields($bean, $field = 'id', $value = null, $view = 'list')
    {
        $targetBean = BeanFactory::getBean($bean->target_module);

        if (!$targetBean) {
            return array();
        }

        $fields = $targetBean->getFieldDefinitions();

        $options = array();

        foreach ($fields as $field) {
            if (empty($field['vname'])) {
                continue;
            }

            if ($field['type'] == 'link') {
                continue;
            }

            $label = rtrim(translate($field['vname'], $targetBean->module_name), ': ');

            if ($field['vname'] == $label) {
                continue;
            }

            if (($field['type'] == 'id' || (isset($field['dbType']) && $field['dbType'] == 'id')) && stripos($label, 'ID') === false) {
                $label .= ' (ID)';
            }

            $options[$field['name']] = $label;
        }

        asort($options);

        return $options;
    }

    public function getValues($bean, $field, $value, $view)
    {
        $targetBean = BeanFactory::getBean($bean->target_module);

        if (!$targetBean) {
            return array();
        }

        $field = $targetBean->getFieldDefinition($bean->target_field);

        $options = array(
            '' => '',
        );

        if (empty($field['options']) || empty($bean->target_module)) {
            return array();
        }

        foreach (translate($field['options'], $bean->target_module) as $key => $label) {
            $options[$key] = $label;
        }

        return array_unique($options);
    }

    protected function getMarkByBean(SugarBean $bean)
    {
        $links = array_filter($bean->getFieldDefinitions(), function ($field) use ($bean) {
            if ($field['type'] !== 'link') {
                return 0;
            }

            $bean->load_relationship($field['name']);

            if (empty($bean->{$field['name']})) {
                return 0;
            }

            $module = $bean->{$field['name']}->getRelatedModuleName();

            if ($module == 'Calls' || $module == 'Tasks' || $module == 'Meetings' || $module == 'Missions') {
                return 1;
            }
        });

        if (!$links) {
            return 'white';
        }

        $queriesExpired = array();
        //------------------------------------------------------------------
        // PW - custom code
        $queriesUnexpired = array();
        //------------------------------------------------------------------

        foreach ($links as $field => $link) {
            $queryExpired = $bean->{$link['name']}->getQuery(array(
                'return_as_array' => true,
            ));

            if (!array_key_exists('join', $queryExpired)) {
                $queryExpired['join'] = '';
            }

            $queryUnexpired = $queryExpired;

            //------------------------------------------------------------------------------
            // PW - custom code
            $table = $bean->{$link['name']}->relationship->getRelationshipTable();
            $table_alias = $table;
            //$table = $bean->{$link['name']}->getRelatedModuleName()->table_name;
            //------------------------------------------------------------------------------

            if ($bean->{$link['name']}->relationship->type == 'many-to-many' && $queryExpired['join'] == '') {
                //------------------------------------------------------------------------------
                // PW - custom code
                $table = $bean->{$link['name']}->relationship->def['rhs_table'];
                $table_alias = $table . '_' . rand(11111, 99999) . '_' . str_pad(count($queriesExpired) + count($queriesUnexpired) + 1, 3, '0', STR_PAD_LEFT);
                //$table = $bean->{$link['name']}->getRelatedModuleName()->table_name;
                //------------------------------------------------------------------------------
                $join = ' JOIN ' . $table . ' AS ' . $table_alias . ' ON ' . $table_alias . '.id = ' .
                    $bean->{$link['name']}->relationship->def['join_table'] . '.' . $bean->{$link['name']}->relationship->def['join_key_rhs'];
                $queryExpired['join'] .= $join;
                $queryUnexpired['join'] .= $join;
            }

            switch ($bean->{$link['name']}->getRelatedModuleName()) {
                case 'Meetings':
                case 'Calls':
                    //------------------------------------------------------------------------------
                    // PW - custom code
                    $queryExpired['where'] .= " AND {$table_alias}.status IN ('Planned') AND date_end < <now>";
                    $queryUnexpired['where'] .= " AND {$table_alias}.status IN ('Planned') AND date_end >= <now>";
                    //$queryExpired['where'] .= " AND {$table}.status IN ('Planned') AND date_end < <now>";
                    //$queryUnexpired['where'] .= " AND {$table}.status IN ('Planned') AND date_end >= <now>";
                    //------------------------------------------------------------------------------
                    break;
                case 'Tasks':
                    //------------------------------------------------------------------------------
                    // PW - custom code
                    $queryExpired['where'] .= " AND {$table_alias}.status IN ('Not Started', 'In Progress', 'Pending Input') AND date_due < <now>";
                    $queryUnexpired['where'] .= " AND {$table_alias}.status IN ('Not Started', 'In Progress', 'Pending Input') AND date_due >= <now>";
                    //$queryExpired['where'] .= " AND {$table}.status IN ('Not Started', 'In Progress', 'Pending Input') AND date_due < <now>";
                    //$queryUnexpired['where'] .= " AND {$table}.status IN ('Not Started', 'In Progress', 'Pending Input') AND date_due >= <now>";
                    //------------------------------------------------------------------------------
                    break;
                case 'Missions':
                    //------------------------------------------------------------------------------
                    // PW - custom code
                    $queryExpired['where'] .= " AND {$table_alias}.status IN ('planned', 'in_progress') AND date_due < <now>";
                    $queryUnexpired['where'] .= " AND {$table_alias}.status IN ('planned', 'in_progress') AND date_due >= <now>";
                    //$queryExpired['where'] .= " AND {$table}.status IN ('planned', 'in_progress') AND date_due < <now>";
                    //$queryUnexpired['where'] .= " AND {$table}.status IN ('planned', 'in_progress') AND date_due >= <now>";
                    //------------------------------------------------------------------------------
                    break;
            }

            $queriesExpired[] = '(' . $queryExpired['select'] . ' ' . $queryExpired['from'] . ' ' . $queryExpired['join'] . ' ' . $queryExpired['where'] . ')';
            $queriesUnexpired[] = '(' . $queryUnexpired['select'] . ' ' . $queryUnexpired['from'] . ' ' . $queryUnexpired['join'] . ' ' . $queryUnexpired['where'] . ')';
        }

        $expiredTemplate = 'SELECT COUNT(*) AS count FROM (' . implode(' UNION ', $queriesExpired) . ') AS vtable';
        $unexpiredTemplate = 'SELECT COUNT(*) AS count FROM (' . implode(' UNION ', $queriesUnexpired) . ') AS vtable';

        $expiredSql = strtr($expiredTemplate, array(
            '<now>' => $this->db->quoted(gmdate('Y-m-d H:i:s')),
        ));

        $unexpiredSql = strtr($unexpiredTemplate, array(
            '<now>' => $this->db->quoted(gmdate('Y-m-d H:i:s')),
        ));

        $rowExpired = $this->db->fetchOne($expiredSql);

        if ($rowExpired['count']) {
            return 'red';
        }

        $rowUnexpired = $this->db->fetchOne($unexpiredSql);

        if (!$rowExpired['count'] && !$rowUnexpired['count']) {
            return 'yellow';
        }

        if (!$rowExpired['count'] && $rowUnexpired['count']) {
            return 'green';
        }

        return 'white';
    }

    // aromero: getValues for horizontal_lines_field
    public function getEnumAndParentFields($bean, $field, $value, $view)
    {
        $ar_result = $bean->getEnumFields($bean);

        $targetBean = BeanFactory::getBean($bean->target_module);

        if (isset($targetBean->field_defs) && is_array($targetBean->field_defs)) {
            foreach ($targetBean->field_defs as $field_key => $field_def) {

                if (isset($field_def['type']) && $field_def['type'] == 'link' && isset($field_def['id_name'])) {
                    $relationship_link = $field_key;
                    $ar_result[$relationship_link] = translate($field_def['module']);
                }

            }
        }
        return $ar_result;
    }

    // aromero: getValues of horizontal_lines_field selected
    public function getHorizontalLinesValues()
    {
        $targetBean = BeanFactory::getBean($this->target_module);

        if (!$targetBean) {
            return array();
        }

        $field = $targetBean->getFieldDefinition($this->horizontal_lines_field);

        $options = array(
            '' => '',
        );

        if (isset($field['type']) && $field['type'] == 'link') {
            return array();
        }
        if (empty($field['options']) || empty($this->target_module)) {
            return array();
        }

        foreach (translate($field['options'], $this->target_module) as $key => $label) {
            $options[$key] = $label;
        }

        return array_unique($options);
    }

    // aromero: getColumns con separaciones horizontales
    public function getColumnsWithHorizontalLines()
    {
        $lines = array();
        $module = $this->target_module;
        $field = $this->target_field;
        $horizontal_lines_field = $this->horizontal_lines_field;
        $id_kanban = $this->id;

        $beanRepository = BeanFactory::getBean($module);

        if (!$beanRepository) {
            return array();
        }

        if (!$field) {
            return array();
        }
        $where = $this->getConditions();
        $beans = $beanRepository->get_list("", $where, 0, 100, 100);
        $beans = $beans['list'];

        if (empty($beans)) {
            return $columns;
        }

        if (!$horizontal_lines_field) {
            $horizontal_lines_field = 'module_dir';
            $lines[$this->target_module] = array(
                'target_module' => $this->target_module,
                'target_field' => $this->target_field,
                'label' => translate($this->target_module) . ' ' . translate($this->target_field, $this->target_module),
                'columns' => $this->getColumns());
        } else {

            // aromero: según el valor del campo dropdown creamos los arrays columns
            $horizontal_lines_field_values = $this->getHorizontalLinesValues();

            $ar_label_fields = $this->getEnumFields($this);
            $horizontal_lines_field_label = $ar_label_fields[$horizontal_lines_field];
            foreach ($horizontal_lines_field_values as $key => $value) {

                $lines[$key] = array(
                    'field_key' => $this->horizontal_lines_field,
                    'field_key_value' => $key,
                    'label' => $horizontal_lines_field_label . ': ' . $value,
                    'columns' => $this->getColumns());
            }
        }

        foreach ($beans as $bean) {
            $value = $bean->$field;
            if ($bean->load_relationship($horizontal_lines_field)) {
                $relatedBeans = $bean->$horizontal_lines_field->getBeans();

                $parentBean = false;
                if (!empty($relatedBeans)) {
                    //order the results
                    reset($relatedBeans);

                    //first record in the list is the parent
                    $parentBean = current($relatedBeans);
                    $bean->$horizontal_lines_field = $parentBean->id;
                    if (!isset($lines[$parentBean->id])) {
                        $lines[$parentBean->id] = array('label' => $parentBean->name, 'columns' => $this->getColumns());
                    }

                }

            }
            if (!isset($lines[$bean->$horizontal_lines_field]['columns'][$value])) {
                continue;
            }
            $sortBean = new kan_sorts();
            $sortBean->retrieve_by_string_fields(array('id_kanban' => $id_kanban, 'id_item' => $bean->id));

            $bean_sort_index = $sortBean->sort_index;
            // aromero: creo el orden para la primera vez
            if ($bean_sort_index == null) {
                $bean_sort_index = count($lines[$bean->$horizontal_lines_field]['columns'][$value]['items']);
                $sortBean->id_kanban = $id_kanban;
                $sortBean->id_item = $bean->id;
                $sortBean->description = $bean->name;
                $sortBean->sort_index = $bean_sort_index;
                $sortBean->column_value = $value;
                $sortBean->save(false);
                $bean->sort_index = $bean_sort_index;
            }

            $lines[$bean->$horizontal_lines_field]['columns'][$value]['items'][$bean_sort_index] = $this->processBean($bean);

        }

        // aromero: ordeno la lista a devolver conforme al orden de la tabla kanban_order
        foreach ($lines as $line_key => $line_value) {
            $empty = true;
            foreach ($lines[$line_key]['columns'] as $key => $column) {
                $ar_sort = $sortBean->sort($lines[$line_key]['columns'][$key]['items']);
                $lines[$line_key]['columns'][$key]['items'] = $ar_sort;
                $empty = $empty && empty($ar_sort);
            }

            if ($empty) {
                unset($lines[$line_key]);
            }

        }
        // aromero: ordeno las lineas por el label
        usort($lines, function ($a, $b) {

            if (substr($a['label'], 0, 3) == substr($b['label'], 0, 3)) {
                return 0;
            }
            return (substr($a['label'], 0, 3) < substr($b['label'], 0, 3)) ? -1 : 1;
        });
        return $lines;
    }

    public function getColumnsWithItems()
    {
        $columns = $this->getColumns();
        $module = $this->target_module;
        $field = $this->target_field;
        $id_kanban = $this->id;
        $sortBean = new kan_sorts();
        $beanRepository = BeanFactory::getBean($module);

        if (!$beanRepository) {
            return array();
        }

        if (!$field) {
            return array();
        }

        //$beans = $beanRepository->get_full_list();
        $where = $this->getConditions();
        $beans = $beanRepository->get_list("", $where, 0, 10);
        $beans = $beans['list'];

        if (empty($beans)) {
            return $columns;
        }

        foreach ($beans as $bean) {
            $value = $bean->$field;

            if (!isset($columns[$value])) {
                continue;
            }
            $sortBean->retrieve_by_string_fields(array('id_kanban' => $id_kanban, 'id_item' => $bean->id));

            $bean_sort_index = $sortBean->sort_index;
            if ($bean_sort_index == null) {
                $bean_sort_index = count($columns[$value]['items']);
            }
            $columns[$value]['items'][$bean_sort_index] = $this->processBean($bean);

        }
        // aromero: ordeno la lista a devolver conforme al orden de la tabla kanban_order
        foreach ($columns as $key => $column) {
            $ar_sort = $sortBean->sort($columns[$key]['items']);
            $columns[$key]['items'] = $ar_sort;
        }
        return $columns;
    }

    public function processBean($bean)
    {
        $field = $this->target_field;
        $sortBean = new kan_sorts();
        $sortBean->retrieve_by_string_fields(array('id_kanban' => $this->id, 'id_item' => $bean->id));
        $sort_index = $sortBean->sort_index;
        if ($sort_index == null) {
            $sort_index = $bean->sort_index;
        }
        return array(
            'id' => htmlspecialchars($bean->id),
            'module' => htmlspecialchars($bean->module_name),
            'name' => $bean->name,
            'title' => $this->getTitleByBean($bean),
            'content' => $this->getBodyByBean($bean),
            'mark' => $this->getMarkByBean($bean),
            'record' => htmlspecialchars($this->id),
            // aromero: añadir campos orden y value
            'sort_index' => $sort_index,
            'value' => $bean->$field,
            'kanban_header_color' => $this->getColorHeader($bean),
        );
    }

    protected function getBodyByBean(SugarBean $bean)
    {
        global $app_strings, $app_list_strings;

        require_once 'include/utils.php';
        $title = array();
        $fields = $this->setAdditionalBeanFields($bean);
        foreach (unencodeMultienum($this->body_fields) as $field) {
            if (empty($field) || empty($fields[strtoupper($field)])) {
                continue;
            }
            /* aromero: si el campo es de tipo relación obtengo el objeto relacionado.*/
            if ($bean->field_defs[$field]['type'] == 'relate') {
                $link = $bean->field_defs[$field]['link'];
                if ($bean->load_relationship($link)) {
                    //Fetch related beans
                    $relatedBeans = $bean->$link->getBeans();
                    foreach ($relatedBeans as $item) {
                        $title[] =
                            '<span class="field field-' . $field . '" title="' . (translate($bean->field_defs[$field]['vname'], $bean->module_name)) . '">'
                            . '<b class="field-label">' . (translate($bean->field_defs[$field]['vname'], $bean->module_name)) . ': </b>'
                            . '<span class="field-value">'
                            . '<a href="index.php?action=DetailView&module=' . $bean->field_defs[$field]['module'] . '&record=' . $item->id . '" target="_blank">' . $item->name . '</a>'
                            . '</span>'
                            . '</span>';
                    }
                }
                //else es un campo normal
            } else {
                $title[] =
                    '<span class="field field-' . $field . '" title="' . (translate($bean->field_defs[$field]['vname'], $bean->module_name)) . '">'
                    . '<b class="field-label">' . (translate($bean->field_defs[$field]['vname'], $bean->module_name)) . ': </b>'
                    . '<span class="field-value">' . $this->getFixedValue($fields[strtoupper($field)], $bean->field_defs[$field]['type']) . '</span>'
                    . '</span>';
            }

        }
        /* aromero: obtener las relaciones de subpaneles y sus objetos. */
        $htmlrelations = array();
        foreach ($fields as $field => $value) {
            if (($bean->field_defs[strtolower($field)]['type'] == 'link') && isset($bean->field_defs[strtolower($field)]['module'])) {
                $link = strtolower($field);
                if ($bean->load_relationship($link)) {
                    //Fetch related beans
                    $relatedBeans = $bean->$link->getBeans();
                    if (count($relatedBeans)) {
                        $htmlrelations[] = '<strong>' . translate($bean->field_defs[strtolower($field)]['vname']) . '</strong>';
                    }
                    foreach ($relatedBeans as $item) {

                        $htmlrelations[] = '<a href="index.php?action=DetailView&module=' . $bean->field_defs[strtolower($field)]['module'] . '&record=' . $item->id . '" target="_blank">' . $item->name . '</a>';
                    }
                }
            }
        }
        $title[] = '<div class="accordion" style="margin-right:5px;"><h3>Objetos Relacionados</h3><div>' . implode('<br/>', array_filter($htmlrelations)) . '</div></div>';
        return implode('<br/>', array_filter($title));
    }

    protected function getTitleByBean(SugarBean $bean)
    {
        $title = array();

        $fields = $this->setAdditionalBeanFields($bean);

        foreach (unencodeMultienum($this->header_fields) as $field) {
            if (empty($field)) {
                continue;
            }

            $title[] = '<span data-title-field="' . strtolower($field) . '"><span class="value">' . $this->getFixedValue($fields[strtoupper($field)], $bean->field_defs[$field]['type']) . '</span></span>';
        }

        return implode(' - ', array_filter($title));
    }

    public function getColumns()
    {
        if (empty($this->target_module)) {
            return array();
        }

        if (empty($this->target_field)) {
            return array();
        }

        if (empty($this->target_values)) {
            return array();
        }

        $columns = array();
        $showColumns = unencodeMultienum($this->target_values);
        $closeColumns = $this->getCloseColumns();
        $targetBean = BeanFactory::getBean($this->target_module);
        $field = $this->target_field;
        $fieldDef = $targetBean->getFieldDefinition($field);
        $options = translate($fieldDef['options']);

        foreach ($options as $key => $label) {
            if (is_array($label)) {
                $label = '';
            }

            if (isset($closeColumns[$key])) {
                continue;
            }

            if (!in_array($key, $showColumns, true)) {
                continue;
            }

            $columns[$key] = array(
                'title' => htmlspecialchars($label),
                'field' => htmlspecialchars($field),
                'value' => htmlspecialchars($key),
                'items' => array(),
            );
        }

        return $columns;
    }

    public function getCloseColumns()
    {
        if (empty($this->target_module)) {
            return array();
        }

        if (empty($this->target_field)) {
            return array();
        }

        if (empty($this->target_close_values)) {
            return array();
        }

        $targetBean = BeanFactory::getBean($this->target_module);
        $closedColumns = unencodeMultienum($this->target_close_values);
        $columns = array();
        $fieldDef = $targetBean->getFieldDefinition($this->target_field);
        $options = translate($fieldDef['options']);

        foreach ($options as $key => $label) {
            if (is_array($label)) {
                $label = '';
            }

            if (!in_array($key, $closedColumns, true)) {
                continue;
            }

            $columns[$key] = array(
                'title' => htmlspecialchars($label),
                'field' => htmlspecialchars($this->target_field),
                'value' => htmlspecialchars($key),
                'items' => array(),
            );
        }

        return $columns;
    }

    public static function getTypes()
    {
        global $app_list_strings;
        global $current_user;

        if (is_admin($current_user)) {
            return $app_list_strings['kanban_full_type_list'];
        }

        return $app_list_strings['kanban_personal_type_list'];
    }

    public function save($check_notify = false)
    {
        global $current_user;
        $this->name = $this->generateName();
        $this->assigned_user_id = $current_user->id;
        if ($this->horizontal_lines_field != $this->fetched_row['horizontal_lines_field']) {
            $where = 'WHERE id_kanban = "' . $this->id . '"';
            $sql = 'DELETE FROM kan_sorts ' . $where;

            $this->db->query($sql);
        }
        $this->save_color();
        $this->save_condition();

        return parent::save($check_notify);
    }

    protected function generateName()
    {
        global $app_list_strings;

        $name = array();

        if (!empty($this->target_module)) {
            $name[] = $app_list_strings['moduleList'][$this->target_module];
        }

        if (!empty($this->target_field)) {
            $fields = $this->getFields($this, null, null, null);
            $name[] = $fields[$this->target_field];
        }

        $generatedName = implode(' - ', array_filter($name));

        if (!empty($this->type)) {
            $types = $this->getTypes();
            $generatedName .= ' (' . $types[$this->type] . ')';
        }

        return $generatedName;
    }

    protected function getFixedValue($value, $type)
    {
        global $timedate, $current_user;

        $fixed_value = $value;

        switch ($type) {
            case 'date':
            case 'time':
            case 'datetime':
            case 'datetimecombo':
                if ($this->isDbDate($value, $type)) {
                    $fixed_value = $timedate->asUserType($timedate->fromDbType($value, $type), $type, $current_user);
                }
                break;

            default:
                break;
        }

        return $fixed_value;
    }

    public function isDbDate($date, $type)
    {
        global $timedate;

        if ($type == 'datetime' || $type == 'datetimecombo') {
            $frm = $timedate->get_db_date_time_format();
        } elseif ($type == 'date') {
            $frm = $timedate->get_db_date_format();
        } elseif ($type == 'time') {
            $frm = $timedate->get_db_time_format();
        }

        $frm_regex = $timedate->get_regular_expression($frm);
        $frm_regex = '/' . $frm_regex['format'] . '/';

        if (preg_match($frm_regex, $date)) {
            return true;
        } else {
            return false;
        }
    }

    protected function setAdditionalBeanFields(&$bean)
    {
        $sea = new SugarEmailAddress;

        $bean->fill_in_additional_detail_fields();
        $sea->handleLegacyRetrieve($bean);

        $fields = $bean->get_list_view_array();

        return $fields;
    }

    //Aplicar las Conditions: Si no tiene condiciones, devuelve false, en caso contrario devuelve un array.
    public function getConditions()
    {
        $ar_condition = array(
            'equal' => '=',
            'less' => '<',
            'greater' => '>',
            'equal_or_less' => '<=',
            'equal_or_greater' => '>=',
            'not_equal' => '<>',
            'like' => 'like',
            'is_null' => 'is null',
            'is_not_null' => 'is not null',
        );

        $targetBean = BeanFactory::getBean($this->target_module);

        if (!$targetBean) {
            $targetBean = null;
        }

        $where = "";
        if ($this->load_relationship('kanban_kan_conditions_1')) {

            $items = $this->kanban_kan_conditions_1->getBeans();
            // aromero: ordeno las condiciones
            usort($items, function ($a, $b) {

                if ($a->condition_order == $b->condition_order) {
                    return 0;
                }
                return ($a->condition_order < $b->condition_order) ? -1 : 1;
            });
            $conteo = 0;
            foreach ($items as $item) {
                if ($conteo > 0) {
                    $where .= " " . $item->conditional . " ";
                }
                $str_operator = $ar_condition[$item->operator];
                if (empty($str_operator)) {
                    $str_operator = '=';
                }
                //TODO: obtener el nombre de la tabla en vez del nombre del modulo.

                //-----------------------------------------------------------------
                // PW - custom code
                $field_definition = $targetBean->getFieldDefinition($item->target);

                $table_name = strtolower($this->target_module);

                if (!empty($targetBean) && !empty($field_definition) && isset($field_definition['source']) && $field_definition['source'] == 'custom_fields') {
                    $table_name = strtolower($this->target_module) . '_cstm';
                }
                //-----------------------------------------------------------------

                $where .= "( " . $table_name . "." . $item->target . " " . $str_operator;
                if ($item->value) {
                    $where .= " '" . $item->value . "' )";
                } else {
                    $where .= " )";
                }

                $conteo++;

            }
        }
        return $where;
    }

    //Aplicar los colores: Si el board no tiene colores, devuelve false, en caso contrario devuelve un array.
    public function save_color()
    {
        if (isset($_REQUEST['colors']) && isset($_REQUEST['kanban_color_field'])) {
            foreach ($_REQUEST['colors'] as $kan_colors_id => $color) {
                $targetBean = BeanFactory::getBean('kan_colors', $kan_colors_id);
                $targetBean->kanban_color = $color['kanban_color'];
                $targetBean->description = 'campo: ' . $_REQUEST['kanban_color_field'] . ' value: ' . $targetBean->kanban_color_field_value . ' color: #' . $color['kanban_color'];
                $targetBean->kanban_color_field = $_REQUEST['kanban_color_field'];
                $targetBean->name = $color['kanban_color'];
                $targetBean->save(false);
            }
        }

    }

    public function save_condition()
    {

        // global $current_user;

        $conditions = $_REQUEST['conditions'];

        // Eliminar los que no vienen en el formulario
        if ($this->load_relationship('kanban_kan_conditions_1')) {
            $items = $this->kanban_kan_conditions_1->getBeans();
            $conditions_key = array_keys($conditions);
            foreach ($items as $item) {
                if (!in_array($item->id, $conditions_key)) {
                    $item->deleted = 1;
                    $item->save(false);
                }
            }
        }

        require_once 'modules/kan_conditions/kan_conditions.php';

        foreach ($conditions as $condition_id => $condition) {

            if ($condition['value'] != '' || $condition['operator'] == 'is_not_null' || $condition['operator'] == 'is_null') {
                $condition_bean = new kan_conditions();
                $condition_bean->retrieve($condition_id);

                $condition_bean->name = $condition['name'];
                $condition_bean->target = $condition['target'];
                $condition_bean->operator = $condition['operator'];
                $condition_bean->value = $condition['value'];
                $condition_bean->type = $condition['type'];
                $condition_bean->condition_order = $condition['condition_order'];
                $condition_bean->conditional = $condition['conditional'];

                $condition_bean->kanban_kan_conditions_1kanban_ida = $this->id;

                $condition_bean->save(false);
            }
        }
    }

    public function getColorHeader($bean)
    {
        if (!isset($this->color_values)) {

            if ($this->load_relationship('kanban_kan_colors_1')) {
                $ar_colors = array();
                $parent_field = "";
                $items = $this->kanban_kan_colors_1->getBeans();
                foreach ($items as $item) {
                    $parent_field = $item->kanban_color_field;
                    $ar_colors[$item->kanban_color_field_value] = $item->kanban_color;
                }
                $this->color_values = $ar_colors;
                $this->color_parent_field = $parent_field;

            }
        } else {
            $ar_colors = $this->color_values;
            $parent_field = $this->color_parent_field;
        }
        if (!empty($parent_field)) {
            $value = $bean->$parent_field;
            if (isset($ar_colors[$value])) {
                return '#' . $ar_colors[$value];
            }
        }

    }

}
