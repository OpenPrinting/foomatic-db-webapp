{include file="page_masthead.tpl"}

<div id="two_col_col_1">

{if (!isset($data.noentry) or $data.noentry != "1" or count($driverinfoboxes) > 0)}
	{include file="page_breadcrumbs.tpl"}

	{$printerinfobox}
	{if !isset($data.noentry) or $data.noentry != "1"}
		{if $data.pjl == "1" or $data.text == "us-ascii" or
		$data.contrib_url != ""}	
			<h3>Miscellaneous</h3>
			<p>
		{/if}
		{if $data.pjl == "1"}
			Printer supports PJL.<br>
		{/if}
		{if $data.text == "us-ascii"}
			Printer supports direct text printing with the 'us-ascii' charset.<br>
		{/if}
		{if $data.contrib_url != ""}
			Contrib URL: <a href="{$data.contrib_url}">{$data.contrib_url}</a>
		{/if}
		{if $data.pjl == "1" or $data.text == "us-ascii" or
		$data.contrib_url != ""}	
			</p>
		{/if}
	
		<h3>Comments</h3>
		<p>{$data.comments}</p>
	{else}
		<b>The properties of this printer are not yet entered into the
		database</b><br>
		This printer is only listed here because it is in the list of
		supported printers of the entries for the drivers shown
		below.<br>
	{/if}

	{if count($driverinfoboxes) > 0}
	    <h3>Drivers</h3>
	    <p>The following driver(s) are known to drive this printer:</p>
	    <p>
	    {foreach from=$driverinfoboxes item=d}
		{$d}
	    {/foreach}
	    </p>
	{/if}
{else}
                <h1>Printer not found</h1>
                <p>We're sorry, but the printer ID you provided was not found
		in our database.</p>
{/if}
</div>
{include file="page_rightcommon.tpl" classtype="two_col_col_2"}
{include file="page_conclusion.tpl"}
