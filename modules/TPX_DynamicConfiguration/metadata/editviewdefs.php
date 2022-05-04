<?php
$module_name = 'TPX_DynamicConfiguration';
$viewdefs [$module_name] =
    array(
        'EditView' =>
            array(
                'templateMeta' =>
                    array(
                        'form' =>
                            array(
                                'enctype' => 'multipart/form-data',
                                'hidden' =>
                                    array(),
                            ),
                        'maxColumns' => '2',
                        'widths' =>
                            array(
                                0 =>
                                    array(
                                        'label' => '10',
                                        'field' => '30',
                                    ),
                                1 =>
                                    array(
                                        'label' => '10',
                                        'field' => '30',
                                    ),
                            ),
                        'javascript' => '{sugar_getscript file="include/javascript/popup_parent_helper.js"}
	{sugar_getscript file="cache/include/javascript/sugar_grp_jsolait.js"}
	{sugar_getscript file="modules/Documents/documents.js"}',
                        'useTabs' => false,
                        'tabDefs' =>
                            array(
                                'DEFAULT' =>
                                    array(
                                        'newTab' => false,
                                        'panelDefault' => 'expanded',
                                    ),
                                'LBL_EDITVIEW_PANEL1' =>
                                    array(
                                        'newTab' => false,
                                        'panelDefault' => 'expanded',
                                    ),
                                'LBL_EDITVIEW_PANEL2' =>
                                    array(
                                        'newTab' => false,
                                        'panelDefault' => 'expanded',
                                    ),
                            ),
                        'syncDetailEditViews' => false,
                    ),
                'panels' =>
                    array(
                        'default' =>
                            array(
                                0 =>
                                    array(
                                        0 => 'document_name',
                                        1 =>
                                            array(
                                                'name' => 'code',
                                                'label' => 'LBL_CODE',
                                            ),
                                    ),
                                1 =>
                                    array(
                                        0 =>
                                            array(
                                                'name' => 'var_type',
                                                'studio' => 'visible',
                                                'label' => 'LBL_VAR_TYPE',
                                            ),
                                        1 =>
                                            array(
                                                'name' => 'parent_name',
                                                'studio' => 'visible',
                                                'label' => 'LBL_FLEX_RELATE',
                                            ),
                                    ),
                                2 =>
                                    array(
                                        0 =>
                                            array(
                                                'name' => 'global',
                                                'label' => 'LBL_GLOBAL',
                                            ),
                                        1 =>
                                            array(
                                                'name' => 'expose_to_web_assets',
                                                'label' => 'LBL_EXPOSE_TO_WEB_ASSETS',
                                            ),
                                    ),
                                3 =>
                                    array(
                                        0 =>
                                            array(
                                                'name' => 'enabled',
                                                'label' => 'LBL_ENABLED',
                                            ),
                                        1 => '',
                                    ),
                                4 =>
                                    array(
                                        0 =>
                                            array(
                                                'name' => 'description',
                                            ),
                                    ),
                            ),
                        'lbl_editview_panel1' =>
                            array(
                                0 =>
                                    array(
                                        0 =>
                                            array(
                                                'name' => 'default_value',
                                                'studio' => 'visible',
                                                'label' => 'LBL_DEFAULT_VALUE',
                                            ),
                                        1 =>
                                            array(
                                                'name' => 'value',
                                                'studio' => 'visible',
                                                'label' => 'LBL_VALUE',
                                            ),
                                    ),
                            ),
                        'lbl_editview_panel2' =>
                            array(
                                0 =>
                                    array(
                                        0 =>
                                            array(
                                                'name' => 'uploadfile',
                                                'displayParams' =>
                                                    array(
                                                        'onchangeSetFileNameTo' => 'document_name',
                                                    ),
                                            ),
                                        1 => 'status_id',
                                    ),
                                1 =>
                                    array(
                                        0 => 'category_id',
                                        1 => 'subcategory_id',
                                    ),
                                2 =>
                                    array(
                                        0 => 'active_date',
                                        1 => 'exp_date',
                                    ),
                            ),
                    ),
            ),
    );;
?>
