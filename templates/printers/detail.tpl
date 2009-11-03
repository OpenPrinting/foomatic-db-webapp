{include file="page_masthead.tpl"}

<div id="two_col_col_1">
	{include file="page_breadcrumbs.tpl"}

	<h1>Result for {$manufacturer} {$model}</h1>
	
	{section name=printer loop=$data}

	<p style="border: 1px solid #ccc; background: #eee; padding: 6px; margin-top: 20px; margin-bottom:20px;">
		Recommended Driver: <a href="{$BASEURL}driver/{$data[printer].default_driver}" title="{$data[printer].default_driver}">{$data[printer].default_driver}</a>
		( 
			<a href="{$data[printer].url}">Homepage</a> 
			<a href="/ppd-o-matic.php?driver={$data[printer].default_driver}&printer={$data[printer].id}&show=1">view PPD</a> 
			<a href="/ppd-o-matic.php?driver={$data[printer].default_driver}&printer={$data[printer].id}&show=1">download PPD</a> 
		)
		<br>
		Contrib URL: <a href="{$data[printer].contrib_url}">{$data[printer].contrib_url}</a>
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