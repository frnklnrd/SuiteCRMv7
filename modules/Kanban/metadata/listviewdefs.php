<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');


$listViewDefs['Kanban'] = array(
    'ASSIGNED_USER_NAME' => array(
        'width' => '5',
        'label' => 'LBL_LIST_ASSIGNED_USER',
        'module' => 'Kanban',
        'id' => 'ID',
        'default' => true
    ),
    'TYPE' => array(
        'width' => '5',
        'label' => 'LBL_TYPE',
        'link' => true,
        'default' => true
    ),
    'TARGET_MODULE' => array(
        'width' => '5',
        'label' => 'LBL_TARGET_MODULE',
        'link' => true,
        'default' => true
    ),
);

