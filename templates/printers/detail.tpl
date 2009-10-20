{include file="page_masthead.tpl"}

<div id="two_col_col_1">
	{include file="page_breadcrumbs.tpl"}

	<h1>Result for {$manufacturer} {$model}</h1>
	
	{section name=printer loop=$data}
		
	<p style="border: 1px solid #ccc; background: #eee; padding: 6px;">
		Recommended Driver: {$data[printer].default_driver}
	</p>
	
	<h3>Comments</h3>
	{$data[printer].comments}
	
	<h3>Miscellaneous</h3>
	{if $data[printer].pjl == "1"}
		Printer supports PJL.<br>
	{/if}
	{if $data[printer].text == "us-ascii"}
		Printer supports direct text printing with the 'us-ascii' charset.<br>
	{/if}
	
	{/section}

</div>
{include file="page_rightcommon.tpl" classtype="two_col_col_2"}
{include file="page_conclusion.tpl"}