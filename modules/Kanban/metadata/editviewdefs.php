<?php

$viewdefs['Kanban']['EditView'] = array(
    'templateMeta' => array(
        'maxColumns' => '1',
        'widths' => array(
            array(
                'label' => '10',
                'field' => '30',
            ),
        ),
        'includes' => array(
            array(
                'file' => 'modules/Kanban/js/EditView.js',
            ),
        ),
    ),
    'panels' => array(
        'default' => array(
            array(
                array(
                    'name' => 'name',
                    'type' => 'html',
                ),
            ),
            array(
            ),
            array(
                'self_name',
            ),
            array(
                'type',
            ),
            array(
                'target_module',
            ),
            array(
                'target_field',
            ),
            array(
                'target_values',
            ),
            array(
                'target_close_values',
            ),
            array(
                'header_fields',
            ),
            array(
                'body_fields',
            ),
            array(
                'horizontal_lines_field',
            ),
        ),
        //maguilar: Añado el panel Conditions y los campos correspondientes
        'Conditions' => array(
            array(
                'conditions',
            ),
        ),
        //maguilar: Añado el panel Card Colors y los campos correspondientes
        'Colors' => array(
            array(
                'card_colors',
            ),
        ),
        //fin maguilar
    ),
);
