{include file="page_masthead.tpl"}

<div id="two_col_col_1">
  {include file="page_breadcrumbs.tpl"}
	
  <h1>Manage Queue</h1>
  
  {foreach from=$types item=type}
    <form action="" method="post">
      <table class="queue">
        <thead>
          <tr>
            <th class="actions" colspan="8">
              <select name="action" disabled="disabled">
                <option>Selected entries…</option>
                <option name="approve">Approve</option>
                <option name="approve">Merge</option>
                <option name="approve">Delete</option>
              </select>
            </th>
          </tr>
          <tr>
            <th>&nbsp;</th>
            <th>{$type|capitalize} Name</th>
            <th>Username</th>
            <th>Date Submitted</th>
            <th>Release Date</th>
            <th>Status</th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th class="actions" colspan="7">
              <select name="action" disabled="disabled">
                <option>Selected entries…</option>
                <option name="approve">Approve</option>
                <option name="approve">Merge</option>
                <option name="approve">Delete</option>
              </select>
            </th>
          </tr>
        </tfoot>
        <tbody>
          {cycle values="even,odd" name="entries" print=false reset=true}
          {foreach from=$queue[$type] item=entry}
            <tr class="{cycle values="even,odd" name="entries"}">
              <td><input type="checkbox" name="selected[]" value="selected" /></td>
              {if $entry.make}
                <td>{$entry.name}{$entry.make} {$entry.model}</td>
              {else}
                <td>{$entry.name}</td>
              {/if}
              <td>{$entry.contributor}</td>
              <td>{$entry.submitted}</td>
              <td>{$entry.showentry}</td>
              <td>
                {if $entry.approved != ""}
                  Approved
                {elseif $entry.rejected != ""}
                  Rejected
                {else}
                  Submitted
                {/if}
              </td>
              <td class="actions">
                {if $entry.make}
                  <a href="{$BASEURL}printer/{$entry.make|replace:" ":"+"}/{$entry.id|replace:" ":"+"}/delete">Delete</a> | 
                  <a href="{$BASEURL}printer/{$entry.make|replace:" ":"+"}/{$entry.id|replace:" ":"+"}/edit">Edit</a>
                {else}
                  <a href="{$BASEURL}driver/{$entry.id}/delete">Delete</a> | 
                  <a href="{$BASEURL}driver/{$entry.id}/edit">Edit</a>
                {/if}
              </td>
            </tr>
          {/foreach}
        </tbody>
      </table>
    </form>
  {/foreach}
</div>
{include file="page_rightcommon.tpl" classtype="two_col_col_2"}
{include file="page_conclusion.tpl"}
