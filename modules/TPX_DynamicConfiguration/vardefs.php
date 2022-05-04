<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */

$dictionary['TPX_DynamicConfiguration'] = array(
    'table' => 'tpx_dynamicconfiguration',
    'audited' => true,
    'inline_edit' => true,
    'fields' => array(
        'enabled' =>
            array(
                'required' => false,
                'name' => 'enabled',
                'vname' => 'LBL_ENABLED',
                'type' => 'bool',
                'massupdate' => 0,
                'default' => '1',
                'no_default' => false,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => false,
                'inline_edit' => true,
                'reportable' => true,
                'unified_search' => false,
                'merge_filter' => 'disabled',
                'len' => '255',
                'size' => '20',
            ),
        'code' =>
            array(
                'required' => true,
                'name' => 'code',
                'vname' => 'LBL_CODE',
                'type' => 'varchar',
                'massupdate' => 0,
                'no_default' => false,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => false,
                'inline_edit' => true,
                'reportable' => true,
                'unified_search' => false,
                'merge_filter' => 'disabled',
                'len' => '255',
                'size' => '20',
            ),
        'settings' =>
            array(
                'required' => false,
                'name' => 'settings',
                'vname' => 'LBL_SETTINGS',
                'type' => 'text',
                'massupdate' => 0,
                'no_default' => false,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => false,
                'inline_edit' => true,
                'reportable' => true,
                'unified_search' => false,
                'merge_filter' => 'disabled',
                'size' => '20',
                'studio' => 'visible',
                'rows' => '4',
                'cols' => '20',
            ),
        'var_type' =>
            array(
                'required' => true,
                'name' => 'var_type',
                'vname' => 'LBL_VAR_TYPE',
                'type' => 'enum',
                'massupdate' => 0,
                'default' => 'TEXT',
                'no_default' => false,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => false,
                'inline_edit' => true,
                'reportable' => true,
                'unified_search' => false,
                'merge_filter' => 'disabled',
                'len' => 100,
                'size' => '20',
                'options' => 'tpx_var_type_list',
                'studio' => 'visible',
                'dependency' => false,
            ),
        'value' =>
            array(
                'required' => false,
                'name' => 'value',
                'vname' => 'LBL_VALUE',
                'type' => 'text',
                'massupdate' => 0,
                'no_default' => false,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => false,
                'inline_edit' => true,
                'reportable' => true,
                'unified_search' => false,
                'merge_filter' => 'disabled',
                'size' => '20',
                'studio' => 'visible',
                'rows' => '2',
                'cols' => '20',
            ),
        'options' =>
            array(
                'required' => false,
                'name' => 'options',
                'vname' => 'LBL_OPTIONS',
                'type' => 'text',
                'massupdate' => 0,
                'no_default' => false,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => false,
                'inline_edit' => true,
                'reportable' => true,
                'unified_search' => false,
                'merge_filter' => 'disabled',
                'size' => '20',
                'studio' => 'visible',
                'rows' => '4',
                'cols' => '20',
            ),
        'default_value' =>
            array(
                'required' => false,
                'name' => 'default_value',
                'vname' => 'LBL_DEFAULT_VALUE',
                'type' => 'text',
                'massupdate' => 0,
                'no_default' => false,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => false,
                'inline_edit' => true,
                'reportable' => true,
                'unified_search' => false,
                'merge_filter' => 'disabled',
                'size' => '20',
                'studio' => 'visible',
                'rows' => '2',
                'cols' => '20',
            ),
        'html_value' =>
            array(
                'required' => false,
                'name' => 'html_value',
                'vname' => 'LBL_HTML_VALUE',
                'type' => 'text',
                //'editor' => 'html',
                'massupdate' => 0,
                'no_default' => false,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => false,
                'inline_edit' => true,
                'reportable' => true,
                'unified_search' => false,
                'merge_filter' => 'disabled',
                'size' => '20',
                'studio' => 'visible',
                'rows' => '4',
                'cols' => '20',
            ),
        'expose_to_web_assets' =>
            array(
                'required' => false,
                'name' => 'expose_to_web_assets',
                'vname' => 'LBL_EXPOSE_TO_WEB_ASSETS',
                'type' => 'bool',
                'massupdate' => 0,
                'default' => '0',
                'no_default' => false,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => false,
                'inline_edit' => true,
                'reportable' => true,
                'unified_search' => false,
                'merge_filter' => 'disabled',
                'len' => '255',
                'size' => '20',
            ),
        'parent_name' =>
            array(
                'required' => false,
                'source' => 'non-db',
                'name' => 'parent_name',
                'vname' => 'LBL_FLEX_RELATE',
                'type' => 'parent',
                'massupdate' => 0,
                'no_default' => false,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => false,
                'inline_edit' => true,
                'reportable' => true,
                'unified_search' => false,
                'merge_filter' => 'disabled',
                'len' => 25,
                'size' => '20',
                'options' => 'parent_type_display',
                'studio' => 'visible',
                'type_name' => 'parent_type',
                'id_name' => 'parent_id',
                'parent_type' => 'record_type_display',
            ),
        'parent_type' =>
            array(
                'required' => false,
                'name' => 'parent_type',
                'vname' => 'LBL_PARENT_TYPE',
                'type' => 'parent_type',
                'massupdate' => 0,
                'no_default' => false,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => 0,
                'audited' => false,
                'inline_edit' => true,
                'reportable' => true,
                'unified_search' => false,
                'merge_filter' => 'disabled',
                'len' => 255,
                'size' => '20',
                'dbType' => 'varchar',
                'studio' => 'hidden',
            ),
        'parent_id' =>
            array(
                'required' => false,
                'name' => 'parent_id',
                'vname' => 'LBL_PARENT_ID',
                'type' => 'id',
                'massupdate' => 0,
                'no_default' => false,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => 0,
                'audited' => false,
                'inline_edit' => true,
                'reportable' => true,
                'unified_search' => false,
                'merge_filter' => 'disabled',
                'len' => 36,
                'size' => '20',
            ),
        'global' =>
            array(
                'required' => false,
                'name' => 'global',
                'vname' => 'LBL_GLOBAL',
                'type' => 'bool',
                'massupdate' => 0,
                'default' => '0',
                'no_default' => false,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => false,
                'inline_edit' => true,
                'reportable' => true,
                'unified_search' => false,
                'merge_filter' => 'disabled',
                'len' => '255',
                'size' => '20',
            ),
    ),
    'relationships' => array(),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
    require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('TPX_DynamicConfiguration', 'TPX_DynamicConfiguration', array('basic', 'assignable', 'security_groups', 'file'));