<?php
require_once 'modules/Kanban/Kanban.php';
require 'modules/kan_colors/kan_colors.php';

function displayColorForm($bean)
{
    $html = '';
    $link = 'kanban_kan_colors_1';
    // aromero: Cargar la relación del kanban actual
    if ($bean->load_relationship($link)) {
        $colorsBeans = $bean->$link->getBeans();
        $numcolors = count($colorsBeans);

        $html .= '<div id="kanban_color_form">';
        $html .= '<script src="modules/kan_colors/kanban_color.js"></script>';
        $html .= '<input type="hidden" id="id_kanban" value="' . $bean->id . '" />';
        // aromero: Mostramos el campo tipo dropdown target para el color
        $html .= '<div id="kanban_color_form_div" >';
        $html .= '<label for="kanban_color_field" style="width:25%;font-weight:normal;">';
        //$html .= translate('LBL_COLOR_FIELD', 'kan_colors');
        $html .= '</label>';
        $html .= '<select id="kanban_color_field" name="kanban_color_field">';
        $kanban_color_field_values = $bean->getEnumFields($bean);
        foreach ($kanban_color_field_values as $kanban_color_field_value => $kanban_color_field_value_label) {
            $firstBean = array_values($colorsBeans)[0];
            if ($numcolors && ($firstBean->kanban_color_field == $kanban_color_field_value)) {
                $html .= '<option selected value="' . $kanban_color_field_value . '">' . $kanban_color_field_value_label . '</option>';
            } else {
                $html .= '<option value="' . $kanban_color_field_value . '">' . $kanban_color_field_value_label . '</option>';
            }

        }
        $html .= '</select>';
        $html .= '</div>';
        // aromero: Mostramos la tabla con los registros de color
        $html .= '<table id="kanban_color_table">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>Field</th>';
        $html .= '<th>Color</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        // aromero: Si hay registros de color almacenados

        if ($numcolors) {

            // aromero: Mostrar registros de color
            foreach ($colorsBeans as $colorBean) {
                $linea_html = '<tr>';
                $linea_html .= '<td>';
                $linea_html .= '<input type="text" name="colors[' . $colorBean->id . '][kanban_color_field_value]" value="' . $colorBean->kanban_color_field_value . '" disabled/>';
                $linea_html .= '</td>';
                $linea_html .= '<td>';
                $linea_html .= '<input type="text" name="colors[' . $colorBean->id . '][kanban_color]" class="color_input" value="' . $colorBean->kanban_color . '" maxlength="7"/>';
                $linea_html .= '</td>';
                $linea_html .= '</tr>';
                $html .= $linea_html;
            }

        } else {
            $linea_html = '<tr>';
            $linea_html .= '<td colspan="2">';
            $linea_html .= 'Please select a color field';
            $linea_html .= '</td>';
            $linea_html .= '</tr>';
            $html .= $linea_html;
        }
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';
    } else {
        $html .= 'Error loading relationship';
    }
    return $html;
}
