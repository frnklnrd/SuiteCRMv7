<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $sugar_flavor;

$dictionary['Kanban'] = array(
    'table' => 'kanban',
    'audited' => false,
    'unified_search' => false,
    'full_text_search' => false,
    'unified_search_default_enabled' => false,
    'duplicate_merge' => false,
    'fields' => array(
        'name' => array(
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'type' => 'varchar',
            'len' => '255',
            'audited' => true,
            'merge_filter' => 'enabled',
        ),
        'self_name' => array(
            'name' => 'self_name',
            'vname' => 'LBL_SELF_NAME',
            'type' => 'varchar',
            'len' => '255',
            'audited' => true,
            'merge_filter' => 'enabled',
        ),
        'type' => array(
            'name' => 'type',
            'vname' => 'LBL_TYPE',
            'type' => 'enum',
            'len' => '255',
            'audited' => true,
            'merge_filter' => 'enabled',
            'function' => 'Kanban::getTypes',
        ),
        'target_module' => array(
            'name' => 'target_module',
            'vname' => 'LBL_TARGET_MODULE',
            'type' => 'enum',
            'options' => 'moduleList',
            'len' => '255',
            'audited' => true,
            'merge_filter' => 'enabled',
            'function' => 'Kanban::getModules',
        ),
        'target_field' => array(
            'name' => 'target_field',
            'vname' => 'LBL_TARGET_FIELD',
            'type' => 'enum',
            'len' => '255',
            'audited' => true,
            'merge_filter' => 'enabled',
            'function' => 'Kanban::getEnumFields',
        ),
        'target_values' => array(
            'name' => 'target_values',
            'vname' => 'LBL_TARGET_VALUES',
            'type' => 'multienum',
            'options' => 'moduleList',
            'len' => '255',
            'audited' => true,
            'merge_filter' => 'enabled',
            'function' => 'Kanban::getValues',
        ),
        'target_close_values' => array(
            'name' => 'target_close_values',
            'vname' => 'LBL_TARGET_CLOSE_VALUES',
            'type' => 'multienum',
            'len' => '255',
            'audited' => true,
            'merge_filter' => 'enabled',
            'function' => 'Kanban::getValues',
        ),
        'header_fields' => array(
            'name' => 'header_fields',
            'vname' => 'LBL_HEADER_FIELDS',
            'type' => 'multienum',
            'options' => '',
            'len' => '255',
            'audited' => true,
            'merge_filter' => 'enabled',
            'function' => 'Kanban::getFields',
        ),
        'body_fields' => array(
            'name' => 'body_fields',
            'vname' => 'LBL_BODY_FIELDS',
            'type' => 'multienum',
            'options' => '',
            'len' => '255',
            'audited' => true,
            'merge_filter' => 'enabled',
            'function' => 'Kanban::getFields',
        ),
        // aromero: campo para separaciones horizontales
        'horizontal_lines_field' => array(
            'name' => 'horizontal_lines_field',
            'vname' => 'LBL_HORIZONTAL_LINES_FIELD',
            'type' => 'enum',
            'len' => '255',
            'audited' => true,
            'merge_filter' => 'enabled',
            'function' => 'Kanban::getEnumAndParentFields',
        ),
        //maguilar: Establezco los campos para Conditions
        'conditions' => array(
            'name' => 'conditions',
            'vname' => 'LBL_CONDITIONS',
            'type' => 'function',
            'options' => '',
            'len' => '255',
            'audited' => true,
            'source' => 'non-db',
            'merge_filter' => 'enabled',
            'function' => array(
                'name' => 'displayConditions',
                'returns' => 'html',
                'include' => 'modules/kan_conditions/conditLines.php',
            ),
        ),
        //maguilar: Establezco los campos para los Card Colors

        'card_colors' => array(
            'name' => 'card_colors',
            'vname' => 'LBL_CARD_COLORS',
            'type' => 'function',
            'options' => '',
            'len' => '255',
            'audited' => true,
            'source' => 'non-db',
            'merge_filter' => 'enabled',

            'function' => array(
                'name' => 'displayColorForm',
                'returns' => 'html',
                'include' => 'modules/kan_colors/kanban_color_form.php',

            ),

        ),
        //fin maguilar.
    ),
    'optimistic_locking' => true,
);

if (!empty($sugar_flavor) && $sugar_flavor == 'CE') {
    VardefManager::createVardef('Kanban', 'Kanban', array(
        'default', 'assignable',
    ));
} else {
    VardefManager::createVardef('Kanban', 'Kanban', array(
        'default', 'team_security', 'assignable',
    ));
}
