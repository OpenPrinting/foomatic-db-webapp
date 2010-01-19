{include file="page_masthead.tpl"}

<div id="two_col_col_1">
	{include file="page_breadcrumbs.tpl"}

	<h1>{$manufacturer} {$model}</h1>
	
	{section name=printer loop=$data}

	<p style="border: 1px solid #ccc; background: #eee; padding: 6px; margin-top: 20px; margin-bottom:20px;">
		Supports: 
			{if $data[printer].color == "1"} <b><font color="#6B44B6">C</font><font color="#FFCC00">o</font><font color="#10DC98">l</font><font color="#1CA1C2">o</font><font color="#2866EB">r</font></b> {/if}
			{if $data[printer].color == "0"} <b>Black &amp; White</b> {/if}
			{if $data[printer].color == ""} Unknown {/if}
		<br>
		Functionality: 
			{if $data[printer].functionality == "A"} <font color="green">Perfectly</font> <img src="/images/icons/Linuxyes.png"><img src="/images/icons/Linuxyes.png"><img src="/images/icons/Linuxyes.png"> {/if}
			{if $data[printer].functionality == "B"} <font color="green">Mostly</font> <img src="/images/icons/Linuxyes.png"><img src="/images/icons/Linuxyes.png"> {/if}
			{if $data[printer].functionality == "D"} <font color="orange">Partially</font> <img src="/images/icons/Linuxyes.png"> {/if}
			{if $data[printer].functionality == ""} <font color="black">Unknown</font> <img src="/images/icons/Linuxyes.png"> <sup>???</sup> {/if}
			{if $data[printer].functionality == "F"} <font color="red">Paperweight</font> <img src="/images/icons/Linuxno.png"> {/if}
		
		{if $data[printer].default_driver != ""}
		<br>
		Recommended Driver: <a href="{$BASEURL}driver/{$data[printer].default_driver}" title="{$data[printer].default_driver}">{$data[printer].default_driver}</a>
		( 
			<!--<a href="{$data[printer].url}">Homepage</a> -->
			{if $driverUrl != ""}
			<a href="{$driverUrl}">Homepage</a> 
			{/if}
			{if $hasPPD == "1"}
			<a href="/ppd-o-matic.php?driver={$data[printer].default_driver}&printer={$data[printer].id}&show=1">view PPD</a> 
			<a href="/ppd-o-matic.php?driver={$data[printer].default_driver}&printer={$data[printer].id}&show=0">download PPD</a>
			{/if} 
		)
		{/if}
		
		{if $printer_assoc == "1"} 
			<br>Generic Instructions: 
			<a href="/cups-doc.html">CUPS</a>, 
			<a href="/lpd-doc.html">LPD</a>, 
			<a href="/lpd-doc.html">LPRng</a>, 
			<a href="/ppr-doc.html">PPR</a>, 
			<a href="/pdq-doc.html">PDQ</a>, 
			<a href="/direct-doc.html">no spooler</a>
		{/if}

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
	{if $data[printer].contrib_url != ""}
		Contrib URL: <a href="{$data[printer].contrib_url}">{$data[printer].contrib_url}</a>
	{/if}
	{/section}

</div>
{include file="page_rightcommon.tpl" classtype="two_col_col_2"}
{include file="page_conclusion.tpl"}