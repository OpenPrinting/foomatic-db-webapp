{include file="page_masthead.tpl"}

<div id="two_col_col_1">
	{include file="page_breadcrumbs.tpl"}

	<h1>Displaying driver</h1>
	
	<h2>Printer list</h2>
	<ul>
		{foreach from=$printers item=p}
			<li><a href="{$BASEURL}printers/{$p.id}/">{$p.make|escape} {$p.model|escape}</a></li>
		{foreachelse}
			<li>No known printers.</li>
		{/foreach}
	</ul>
</div>
{include file="page_rightcommon.tpl" classtype="two_col_col_2"}
{include file="page_conclusion.tpl"}