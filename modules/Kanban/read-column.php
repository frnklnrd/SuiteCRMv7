<?php

ob_get_clean();
$kanban = BeanFactory::getBean('Kanban', $_REQUEST['record']);
$columns = $kanban->getColumnsWithItems();

echo json_encode($columns[$_REQUEST['target']['value']]);

die;
