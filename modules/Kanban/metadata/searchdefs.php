<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$searchdefs['Kanban'] = array(
    'templateMeta' => array(
        'maxColumns' => '3',
        'widths' => array(
            'label' => '10',
            'field' => '30'
        ),
    ),
    'layout' => array(
        'basic_search' => array(
            array(
                'name' => 'assigned_user_name',
                'displayParams' => array(
                    'displayIdSearch' => true,
                ),
            ),
        ),
    ),
);
