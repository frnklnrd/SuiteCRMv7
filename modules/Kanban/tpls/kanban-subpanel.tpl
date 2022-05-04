{if count($subpanel_data) > 0}
<table class="list view table default">
<tr>
    {foreach from=$headers key=key item=value}
    <th>{$value}</th>
    {/foreach}
</tr>
{foreach from=$subpanel_data key=id item=row}
    <tr>
        {foreach from=$row key=key item=value}
        <td>{$value}</td>
        {/foreach}
    </tr>
{/foreach}
</table>
{else}
<div class="kanban-modal-simpletext">
    <span>{sugar_translate label="LBL_EMPTY_YET" module="Kanban"}</span>
</div>
{/if}
