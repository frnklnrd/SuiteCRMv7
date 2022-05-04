<?php

ob_get_clean();
$kanban = BeanFactory::getBean('Kanban', $_REQUEST['record']);
$bean = BeanFactory::getBean($_REQUEST['target']['module'], $_REQUEST['target']['record']);
$bean->{$_REQUEST['target']['field']} = $_REQUEST['target']['value'];
$bean->save();

// aromero: crea o actualiza el registro correspondiente en la tabla kan_sorts para cada elemento de las listas involucradas.
require_once 'modules/kan_sorts/kan_sorts.php';
$originlist = $_REQUEST['originlist'];
$targetlist = $_REQUEST['targetlist'];
$module = $_REQUEST['target']['module'];
$field = $_REQUEST['target']['field'];
$id_kanban = $_REQUEST['record'];
$sortBean = new kan_sorts();
$sortBean->processBeans($module, $field, $id_kanban, $originlist);
$sortBean->processBeans($module, $field, $id_kanban, $targetlist);
// aromero: devolvemos el json del objeto soltado para actualizar la tarjeta.
echo json_encode($kanban->processBean($bean));
die;
