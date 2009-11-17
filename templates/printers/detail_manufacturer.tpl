{include file="page_masthead.tpl"}

<div id="two_col_col_1">
	{include file="page_breadcrumbs.tpl"}

	<h1>{$manufacturer}</h1>

	<br>
	<h3 style="border-bottom:1px solid #eee;"><font color="green">Perfectly</font> <img src="/images/icons/Linuxyes.png"><img src="/images/icons/Linuxyes.png"><img src="/images/icons/Linuxyes.png"></h3>
	loop here
	
	<h3 style="border-bottom:1px solid #eee;"><font color="green">Mostly</font> <img src="/images/icons/Linuxyes.png"><img src="/images/icons/Linuxyes.png"></h3>
	loop here
	
	<h3 style="border-bottom:1px solid #eee;"><font color="orange">Partially</font> <img src="/images/icons/Linuxyes.png"></h3>
	loop here
	
	<h3 style="border-bottom:1px solid #eee;"><font color="black">Unknown</font> <img src="/images/icons/Linuxyes.png"> <sup>???</sup></h3>
	loop here
	
	<h3 style="border-bottom:1px solid #eee;"><font color="red">Paperweight</font> <img src="/images/icons/Linuxno.png"></h3>
	loop here
	
</div>
{include file="page_rightcommon.tpl" classtype="two_col_col_2"}
{include file="page_conclusion.tpl"}