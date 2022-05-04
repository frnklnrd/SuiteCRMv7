<?php
$viewdefs ['Trackers'] =
array(
  'EditView' =>
  array(
    'templateMeta' =>
    array(
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
      'useTabs' => false,
      'syncDetailEditViews' => false,
    ),
    'panels' =>
    array(
        'default' =>
        array(
            array(
                array(
                    'name' => 'MODULE_NAME',
                    'displayParams' =>
                        array(
                            'required' => true,
                        ),
                ),
                array(
                    'name' => 'ACTION',
                    'displayParams' =>
                        array(
                            'required' => true,
                        ),
                )
            )
        )
    ),
  ),
);
