<?php

$viewdefs['Kanban']['DetailView'] = array(
    'templateMeta' => array(
        'form' => array(
            'buttons' => array(
                'EDIT',
                'DUPLICATE',
                'DELETE'
            )
        ),
        'maxColumns' => '1',
        'widths' => array(
            array(
                'label' => '10',
                'field' => '30'
            ),
        ),
    ),
    'panels' => array(
        'default' => array(
            array(
                'type',
            ),
            array(
                'assigned_user_name',
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
        ),
    ),
);
