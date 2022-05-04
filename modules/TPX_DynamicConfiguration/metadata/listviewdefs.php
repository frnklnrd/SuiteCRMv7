<?php
$module_name = 'TPX_DynamicConfiguration';
$OBJECT_NAME = 'TPX_DYNAMICCONFIGURATION';
$listViewDefs [$module_name] =
    array(
        'NAME' =>
            array(
                'type' => 'varchar',
                'label' => 'LBL_NAME',
                'width' => '10%',
                'default' => true,
            ),
        'CODE' =>
            array(
                'type' => 'varchar',
                'label' => 'LBL_CODE',
                'width' => '10%',
                'default' => true,
            ),
        'VAR_TYPE' =>
            array(
                'type' => 'enum',
                'default' => true,
                'studio' => 'visible',
                'label' => 'LBL_VAR_TYPE',
                'width' => '10%',
            ),
        'VALUE' =>
            array(
                'type' => 'text',
                'studio' => 'visible',
                'label' => 'LBL_VALUE',
                'sortable' => false,
                'width' => '10%',
                'default' => true,
            ),
        'PARENT_NAME' =>
            array(
                'type' => 'parent',
                'studio' => 'visible',
                'label' => 'LBL_FLEX_RELATE',
                'link' => true,
                'sortable' => false,
                'ACLTag' => 'PARENT',
                'dynamic_module' => 'PARENT_TYPE',
                'id' => 'PARENT_ID',
                'related_fields' =>
                    array(
                        0 => 'parent_id',
                        1 => 'parent_type',
                    ),
                'width' => '10%',
                'default' => true,
            ),
        'UPLOADFILE' =>
            array(
                'type' => 'file',
                'label' => 'LBL_FILE_UPLOAD',
                'width' => '10%',
                'default' => true,
            ),
        'EXPOSE_TO_WEB_ASSETS' =>
            array(
                'type' => 'bool',
                'default' => true,
                'label' => 'LBL_EXPOSE_TO_WEB_ASSETS',
                'width' => '10%',
            ),
        'GLOBAL' =>
            array(
                'type' => 'bool',
                'default' => true,
                'label' => 'LBL_GLOBAL',
                'width' => '10%',
            ),
        'ENABLED' =>
            array(
                'type' => 'bool',
                'default' => true,
                'label' => 'LBL_ENABLED',
                'width' => '10%',
            ),
    );;
?>
