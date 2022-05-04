<html
<head>
    <title>Kanban</title>
    <script src="modules/Kanban/js/kanban.js"></script>
    <link href="modules/Kanban/css/kanban.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<div id="kanban-form"></div>
<div>
    <ul class="kanban-panel-menu" id="kanban-panel-menu-items">{foreach from=$contextmenu item=menuitem}
            <li class="{$menuitem.class}">{$menuitem.label}</li>{/foreach}
    </ul>
</div>
<div id="kaban">
    <div id="kaban-nav">
                <span style="float: left;">
                    {if $name}
                        <span style="font-weight: bold;">{$name}</span>
                        <span class="myArea">|</span>
                    {/if}
                    <a href="index.php?module=Kanban">
                        {sugar_translate label="LBL_KANBAN" module="Kanban"}
                    </a> 
                    <span class="myArea">|</span>
                    {if $target_module}
                        <a href="index.php?module={$target_module}&action=index">
                        {sugar_translate label="LBL_LIST_TARGET_MODULE" module="Kanban"}
                    </a>
                        <span class="myArea">|</span>
                    {/if}
                    <a href="index.php?module=Kanban&action=EditView&record={$record}">
                        {sugar_translate label="LBL_LIST" module="Kanban"}
                    </a>
                     
                </span>
        <span style="float: right">

                </span>
        <div style="clear:both"></div>
    </div>
    <!-- maguilar: Se comenta para quitar el menú del DetailView 1-->
    <!--INICIO DEL MENU
            <div id="kanban-modal" style="width: 500px;">{if !empty($kanbans)}
                <table class="table-condensed" width='100%'>{foreach from=$kanbans item=kanban}
                    <tr>
                        <td width='80%'>
                            <a class="btn btn-default {if $kanban.id == $record}active{/if}" href="index.php?module=Kanban&action=DetailView&record={$kanban.id}" onclick="$('#kanban-modal').hide();SUGAR.ajaxUI.showLoadingPanel();">{if !empty($kanban.self_name)}{$kanban.self_name}{else}{$kanban.name}{/if}</a>
                        </td>
                        <td align="right" width='20%'>{if !empty($admin) OR $kanban.type == 'personal'}
                            <a class="btn btn-default" href="index.php?module=Kanban&action=EditView&record={$kanban.id}" onclick="$('#kanban-modal').hide();SUGAR.ajaxUI.showLoadingPanel();">
                                <img src="index.php?entryPoint=getImage&imageName=icon_EditView.png" width="14" height="14" class="iconed_dull">
                            </a>
                            <a class="btn btn-default" href="index.php?module=Kanban&action=Delete&record={$kanban.id}&return_module=Kanban&return_action=index" onclick="$('#kanban-modal').hide();SUGAR.ajaxUI.showLoadingPanel();">
                                <img src="index.php?entryPoint=getImage&imageName=icon_Delete.png" width="14" height="14" class="iconed_dull">
                            </a>{/if}
                        </td>
                    </tr>{/foreach}
                </table>
                <hr/>{/if}
                <a class="btn btn-default" href="index.php?module=Kanban&action=EditView">
                    {sugar_translate label="LBL_NEW" module="Kanban"}
                </a><br>
                <a class="btn btn-default" id="kanban-modal-close" value="">{sugar_translate label="LBL_CLOSE_BUTTON" module="Kanban"}</a>
            </div>
            FIN DEL MENU -->
    <div id="kanban-filters" style="">
    </div>
    {foreach from=$lines key=line_name item=line}
        <div id="kanban-board" class="kanban-board kanban-board-horizontal" data-module="{$target_module}"
             data-field-key="{$line.field_key}" data-field-key-value="{$line.field_key_value}">
            <h1 id="{$line_name}_header"><a href="#{$line_name}">{$line.label}</a></h1>
            <table class="table table-bordered">
                <tr class="kanban-board-header">
                    {foreach from=$line.columns key=name item=column}
                        <th style="background-color: #{$color_values[$column.value]}55 !important;"
                            class="column text-center"
                            data-module="{$target_module}"
                            data-field="{$column.field}"
                            data-value="{$column.value}"
                            data-kanban="{$record}"
                        >
                            <b>{$column.title}</b>
                        </th>
                    {/foreach}
                </tr>
                <span class="kanban_bookmark" id="{$line_name}"
                      style="display: block; height: 115px; /*same height as header*/ margin-top: -115px; /*same height as header*/  visibility: hidden;"></span>
                <tr class="line" id="{$line_name}">{foreach from=$line.columns key=name item=column}
                    <td class="column"
                        data-field="{$column.field}"
                        data-value="{$column.value}">
                        <div

                                data-field="{$column.field}"
                                data-value="{$column.value}"
                                class="sortable {$line_name}"
                                style="background:none;"
                        >{foreach from=$column.items name=itemName item=item}
                            <div
                                    class="kanban-panel ui-state-default"
                                    data-module="{$item.module}"
                                    data-id="{$item.id}"
                                    data-name="{$item.name}"
                                    data-kanban="{$record}"
                                    {* aromero: añado el orden a un dato para poder acceder desde javascript *}
                                    data-order="{$item.sort_index}"
                            >
                                <div class="kanban-panel-heading" style="background-color:{$item.kanban_header_color};">
                                    <h3 class="kanban-panel-title">
                                        {$item.title}
                                    </h3>
                                </div>
                                <div class="kanban-panel-body">
                                    <table width="100%" height="100%">
                                        <tr>
                                            <td valign="middle" class="kanban-panel-body-text">
                                                {$item.content}
                                            </td>
                                            <td valign="middle" align="center"
                                                class="kanban-panel-body-mark mark-{$item.mark}">

                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                </div>{/foreach}
                        </div>
                        </td>{/foreach}
                </tr>
            </table>
        </div>
    {/foreach}
    <div id="kanban-board-close">
        <table class="table table-bordered">
            <tr class="kanban-board-header">
                {foreach from=$closeColumns key=name item=column}
                    <th style="background-color: #{$color_values[$column.value]} !important;"
                        class="column text-center"
                        data-module="{$target_module}"
                        data-field="{$column.field}"
                        data-value="{$column.value}"
                        data-kanban="{$record}"

                    >
                        <b>{$column.title}</b>
                    </th>
                {/foreach}
            </tr>
            <tr id="0" class="line">
                {foreach from=$closeColumns key=name item=column}
                    <td style="background-color: #{$color_values[$column.value]}22 !important;"
                        class="column" data-field="{$column.field}" data-value="{$column.value}">
                        <div data-field="{$column.field}" data-value="{$column.value}"
                             class="sortable {foreach from=$lines key=id_name item=line} {$id_name}{/foreach} ui-sortable"
                             style="background:none;">
                        </div>
                    </td>
                {/foreach}
            </tr>
        </table>
    </div>
</div>
</body>
</html>