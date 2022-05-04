<?php
if (!is_file('cache/jsLanguage/kan_conditions/' . $GLOBALS['current_language'] . '.js')) {
    require_once 'include/language/jsLanguage.php';
    jsLanguage::createModuleStringsCache('kan_conditions', $GLOBALS['current_language']);
}
/*Funcion para desplegar los campos select*/
function displaySelect($field, $item = 'new', $id = 'new', $bean = null)
{
    global $app_list_strings;

    //$targetBean = (BeanFactory::newBean($bean->target_module)->field_name_map);
    $select = "<select  name='conditions[" . $id . "][" . $field . "]' id='condition_" . $field . "' title=''>";
    foreach ($app_list_strings['condit_' . $field . '_list'] as $value => $label) {
        if ($item == $value) {
            $select .= "<option selected label='" . $label . "' value='" . $value . "'>" . $label . "</option>";
        } else {
            $select .= "<option label='" . $label . "' value='" . $value . "'>" . $label . "</option>";
        }
    }
    $select .= "</select>";
    return $select;

}

/*Funcion para desplegar las condiciones existentes*/
function displayConditions($bean, $view)
{

    global $locale, $app_list_strings, $mod_strings;

    $html .= '<script src="modules/kan_conditions/conditLines.js"></script>';
    $style .= '
    <style>
    .ui-sortable-helper {
        display: table;
    }
    #conditions_table thead{
        background: none !important;
        border-bottom: 1px solid #2a536f;
    }
    .condition_place_holder td{

        height: 100%;
    }
    table tr{
        box-sizing: content-box;
    }
    #conditions_table tbody td input,#conditions_table tbody td select,#conditions_table tbody td div,#conditions_table tbody td button{

    }
    #conditions_table tbody td .condition_handle{
        width: 28px;
        text-align:center;
        line-height:28px;
        cursor: move;
        display:inline-block;
    }
    #sort_index{
        width: 30px;
        text-align: center;
    }
    </style>';
    $html .= $style;
    $html .= "<div style='padding-top:10px;padding-bottom:10px;'>";
    $html .= "<table id='conditions_table' style='width: 100%;'>";
    $html .= "<thead class='conditions_head'>";
    $html .= "<tr id='conditions_table_row'>";
    $html .= "<th>" . translate('LBL_CONDITION_ORDER', 'kan_conditions') . "</th>";
    $html .= "<th>" . $mod_strings['LBL_CONDIT_NAME'] . "</th>";
    $html .= "<th>" . $mod_strings['LBL_CONDIT_TARGET'] . "</th>";
    $html .= "<th>" . $mod_strings['LBL_CONDIT_TYPE'] . "</th>";
    $html .= "<th>" . $mod_strings['LBL_CONDIT_OPERATOR'] . "</th>";
    $html .= "<th>" . $mod_strings['LBL_CONDIT_VALUE'] . "</th>";
    $html .= "<th>" . $mod_strings['LBL_CONDIT_CONDITIONAL'] . "</th>";
    $html .= "<th></th>";
    $html .= "</tr>";
    $html .= "</thead>";
    $html .= "<tbody class='conditions_body'>";

// Linea tr oculta para el newCondition
    $html .= "<tr id='condition_new' style='display:none;'>";
    $html .= "<td>";
    $html .= "<div class='condition_handle'><span class = 'glyphicon glyphicon-resize-vertical ui-sortable-handle'></span></div>";
    $html .= "<input  readonly='readonly' id='sort_index' type='text' name='conditions[new][condition_order]' size='2' maxlength='10' value='' title=''/>";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input  type='text' name='conditions[new][name]' size='30' maxlength='255' value='' title=''/>";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<select name='conditions[new][target]' class='con_target'>";
    $target_parent_fields = $bean->getFields($bean);
    foreach ($target_parent_fields as $target_value => $target_name) {
        $html .= "<option label='" . $target_name . "' value='" . $target_value . "'>" . $target_name . "</option>";
    }
    $html .= "</select>";
    $html .= "</td>";
    $html .= "<td>" . displaySelect('type') . "</td>";
    $html .= "<td>" . displaySelect('operator') . "</td>";
    $html .= "<td>";
    $html .= "<input  type='text' name='conditions[new][value]' size='30' maxlength='255' value='' title=''/>";
    $html .= "</td>";
    $html .= "<td>" . displaySelect('conditional') . "</td>";
    $html .= "<td>";
    $html .= '<input type="button" class="button btn_deleteCondition" value="' . $mod_strings['LBL_CONDIT_DELETE'] . '" onclick="deleteCondition(' . "'" . 'condition_new' . "'" . ')" />';
    $html .= "</td>";
    $html .= "</tr>";

    $link = 'kanban_kan_conditions_1';
    if ($bean->load_relationship($link)) {
        $relatedItems = $bean->$link->getBeans();
        // aromero: ordeno las condiciones
        usort($relatedItems, function ($a, $b) {

            if ($a->condition_order == $b->condition_order) {
                return 0;
            }
            return ($a->condition_order < $b->condition_order) ? -1 : 1;
        });
        foreach ($relatedItems as $key => $value) {
            $html .= "<tr id='condition_" . $value->id . "'>";

            $html .= "<td>";
            $html .= "<div class='condition_handle'><span class = 'glyphicon glyphicon-resize-vertical ui-sortable-handle'></span></div>";
            $html .= "<input readonly='readonly' id='sort_index' type='text' name='conditions[" . $value->id . "][condition_order]' size='2' maxlength='10' value='" . $value->condition_order . "' title=''/>";
            $html .= "</td>";
            $html .= "<td>";
            $html .= "<input  type='text' name='conditions[" . $value->id . "][name]' size='30' maxlength='255' value='" . $value->name . "' title=''/>";
            $html .= "</td>";
            $html .= "<td>";
            $html .= "<select name='conditions[" . $value->id . "][target]' class='con_target'>";
            foreach ($target_parent_fields as $target_value => $target_name) {
                if ($value->target == $target_value) {
                    $html .= "<option label='" . $target_name . "' value='" . $target_value . "' selected>" . $target_name . "</option>";
                } else {
                    $html .= "<option label='" . $target_name . "' value='" . $target_value . "'>" . $target_name . "</option>";
                }

            }
            $html .= "</select>";
            $html .= "</td>";
            $html .= "<td>" . displaySelect('type', $value->type, $value->id) . "</td>";
            $html .= "<td>" . displaySelect('operator', $value->operator, $value->id) . "</td>";
            $html .= "<td>";
            $html .= "<input  type='text' name='conditions[" . $value->id . "][value]' size='30' maxlength='255' value='" . $value->value . "' title=''/>";
            $html .= "</td>";
            $html .= "<td>" . displaySelect('conditional', $value->conditional, $value->id) . "</td>";
            $html .= "<td>";
            $html .= '<input type="button" class="button btn_deleteCondition" value="' . $mod_strings['LBL_CONDIT_DELETE'] . '" onclick="deleteCondition(' . "'" . 'condition_' . $value->id . "'" . ')" />';
            $html .= "</td>";
            $html .= "</tr>";
        }
    }
    $html .= "</tbody>";
    $html .= "</table>";
    $html .= "</div>";
    $html .= "<div style='padding-top:10px;padding-bottom:10px;'>";

    $html .= "<input type='button' class='button' value='" . $mod_strings['LBL_CONDIT_NEW'] . "' id='btn_addCondition' onclick='newCondition()'/>";
    $html .= "</div>";

    return $html;
}
