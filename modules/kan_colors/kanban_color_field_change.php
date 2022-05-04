<?php
require_once 'modules/kan_colors/kan_colors.php';
$html = '';
$target_module = $_REQUEST['target_module'];
$target_field = $_REQUEST['target_field'];
$id_kanban = $_REQUEST['id_kanban'];

// bean del modulo
$targetBean = BeanFactory::getBean($target_module);

if (!$targetBean) {
    die('No hay target_module');
}

// recuperar el nombre del dropdown del campo del modulo
$target_children_fields = getValues($targetBean, $target_field);
// aromero: Eliminar todos los registros de color que tiene asociado el kanban
global $db;
$db->query("delete from kan_colors where id in (select kanban_kan_colors_1kan_colors_idb from kanban_kan_colors_1_c where kanban_kan_colors_1kanban_ida = '" . $id_kanban . "')");
$db->query("delete from kanban_kan_colors_1_c where kanban_kan_colors_1kanban_ida = '" . $id_kanban . "'");
if (count($target_children_fields)) {
    foreach ($target_children_fields as $key => $value) {
        $kan_colors = BeanFactory::getBean('kan_colors');
        $kan_colors->name = 'CCCCCC';
        $kan_colors->description = 'Automatically generated';
        $kan_colors->kanban_color = 'CCCCCC';
        $kan_colors->kanban_color_field = $target_field;
        $kan_colors->kanban_color_field_value = $key;
        $kan_colors->kanban_kan_colors_1kanban_ida = $id_kanban;
        $kan_colors->save();

        $html .= "<tr>
            <td><input type=\"text\" name=\"colors[" . $kan_colors->id . "][kanban_color_field_value]\" value=\"" . $kan_colors->kanban_color_field_value . "\" disabled/></br>
            <td><input type=\"text\" name=\"colors[" . $kan_colors->id . "][kanban_color]\" class=\"color_input\" value=\"" . $kan_colors->kanban_color . "\" maxlength=\"7\"/></td>
            </tr>";
    }
} else {
    $linea_html = '<tr>';
    $linea_html .= '<td colspan="2">';
    $linea_html .= 'Please select a color field';
    $linea_html .= '</td>';
    $linea_html .= '</tr>';
    echo $linea_html;
}

echo $html;

function getValues($targetBean, $field)
{
    $field = $targetBean->getFieldDefinition($field);

    $options = array(
        '' => '',
    );

    if (empty($field['options'])) {
        return array();
    }

    foreach (translate($field['options'], $targetBean->module_name) as $key => $label) {
        $options[$key] = $label;
    }

    return array_unique($options);
}
