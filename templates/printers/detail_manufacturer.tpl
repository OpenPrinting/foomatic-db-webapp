{include file="page_masthead.tpl"}

<div id="two_col_col_1">
	{include file="page_breadcrumbs.tpl"}

	<h1>{$manufacturer}</h1>

	<br>	
		
	<h3 style="border-bottom:1px solid #eee;"><font color="green">Perfectly</font> <img src="/images/icons/Linuxyes.png"><img src="/images/icons/Linuxyes.png"><img src="/images/icons/Linuxyes.png"></h3>
	{if $dataPerfectCnt != "0"}
		
		{section name=printerPerfect loop=$dataPerfect}
			
				
			{assign var='printerPerfectUrl' value="`$BASEURL`printer/`$dataPerfect[printerPerfect].make`/`$dataPerfect[printerPerfect].id`"}
			<a href="{$printerPerfectUrl|replace:" ":"+"}">{$dataPerfect[printerPerfect].model}</a><br />
	
		{/section}
	{else}
		None
	{/if}
	
	<h3 style="border-bottom:1px solid #eee;"><font color="green">Mostly</font> <img src="/images/icons/Linuxyes.png"><img src="/images/icons/Linuxyes.png"></h3>
	{if $dataMostlyCnt != "0"}

		{section name=printerMostly loop=$dataMostly}
				
			{assign var='printerMostlyUrl' value="`$BASEURL`printer/`$dataMostly[printerMostly].make`/`$dataMostly[printerMostly].id`"}
			<a href="{$printerMostlyUrl|replace:" ":"+"}">{$dataMostly[printerMostly].model}</a><br />
			
	
		{/section}
	{else}
		None
	{/if}
	
	<h3 style="border-bottom:1px solid #eee;"><font color="orange">Partially</font> <img src="/images/icons/Linuxyes.png"></h3>
	{if $dataPartiallyCnt != "0"}

		{section name=printerPartially loop=$dataPartially}
				
			{assign var='printerPartiallyUrl' value="`$BASEURL`printer/`$dataPartially[printerPartially].make`/`$dataPartially[printerPartially].id`"}
			<a href="{$printerPartiallyUrl|replace:" ":"+"}">{$dataPartially[printerPartially].model}</a><br />
		
		{/section}
	{else}
		None
	{/if}
		
	<h3 style="border-bottom:1px solid #eee;"><font color="black">Unknown</font> <img src="/images/icons/Linuxyes.png"> <sup>???</sup></h3>
	{if $dataUnknownCnt != "0"}

		{section name=printerUnknown loop=$dataUnknown}
				
			{assign var='printerUnknownUrl' value="`$BASEURL`printer/`$data[printerUnknown].make`/`$dataUnknown[printerUnknown].id`"}
			<a href="{$printerUnknownUrl|replace:" ":"+"}">{$dataUnknown[printerUnknown].model}</a><br />
			
		{/section}
	{else}
		None
	{/if}
	
	<h3 style="border-bottom:1px solid #eee;"><font color="red">Paperweight</font> <img src="/images/icons/Linuxno.png"></h3>
	{if $dataPaperweightCnt != "0"}

		{section name=printerPaperweight loop=$dataPaperweight}
				
			{assign var='printerPaperweightUrl' value="`$BASEURL`printer/`$dataPaperweight[printerPaperweight].make`/`$dataPaperweight[printerPaperweight].id`"}
			<a href="{$printerPaperweightUrl|replace:" ":"+"}">{$dataPaperweight[printerPaperweight].model}</a><br />
			
	
		{/section}
	{else}
		None
	{/if}
	
</div>
{include file="page_rightcommon.tpl" classtype="two_col_col_2"}
{include file="page_conclusion.tpl"}