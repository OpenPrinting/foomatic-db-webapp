{include file="page_masthead.tpl"}

<div id="two_col_col_1">
	{include file="page_breadcrumbs.tpl"}
	
	{if $driver}

		<h1>Driver information for: {$driver.name}</h1>
		
		<h2>Comments</h2>
		<div>{$driver.comments|default:"No comments available."}<br /><br /></div>
		
		<h2>Printer list</h2>
		<ul>
			{foreach from=$printers item=p}
				<li><a href="{$BASEURL}printers/{$p.id}/">{$p.make|escape} {$p.model|escape}</a></li>
			{foreachelse}
				<li>No known printers.</li>
			{/foreach}
		</ul>
		
	{else}
		<h1>Driver not found.</h1>
		<p>We're sorry, but the driver ID you provided was not found in our database.</p>
	{/if}
</div>
{include file="page_rightcommon.tpl" classtype="two_col_col_2"}
{include file="page_conclusion.tpl"}