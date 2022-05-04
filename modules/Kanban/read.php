<?php

ob_get_clean();
$kanban = BeanFactory::getBean('Kanban', $_REQUEST['record']);
$bean = BeanFactory::getBean($_REQUEST['target']['module'], $_REQUEST['target']['record']);
$bean->{$_REQUEST['target']['field']} = $_REQUEST['target']['value'];

echo json_encode($kanban->processBean($bean));

die;
