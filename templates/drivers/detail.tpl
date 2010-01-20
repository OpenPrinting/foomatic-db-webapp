{include file="page_masthead.tpl"}

<div id="two_col_col_1">
	{include file="page_breadcrumbs.tpl"}
	
	{if $driver}

		<h1>{$driver.name}</h1>
		<h2>{$driver.shortdescription}</h2>
		
		<p style="border: 1px solid #ccc; background: #eee; padding: 6px; margin-top: 20px; margin-bottom:20px;">
		This driver is 
		{if $driver.nonfreesoftware =="1"}
			<b>non-free</b> 
		{else}
			<b>free</b>
		{/if}	
		software <br>
		{if $driver.supplier}Supplier: {$driver.supplier}<br>{/if}
		Output: 
			{if $driver.color == "1"}
				Color
			{/if}
			{if $driver.color == "0"}
				Grayscale
			{/if}
			{if $driver.color == ""}
				Unkown
			{/if}
		<br>
		Type: {$driver.execution}<br>
		{if $contact.description}User Support: <a href="{$contact.url}">{$contact.description}</a> ({$contact.level})<br>{/if}
		
		
		
		</p>
		
		{if $driver.prototype != "" }
			<br>
			Generic Instructions: 
			<a href="/cups-doc.html">CUPS</a>,  
			<a href="/ppr-doc.html">PPR</a>, 
			<a href="/lpd-doc.html">LPD/LPRng/GNUlpr</a>, 
			<a href="/pdq-doc.html">PDQ</a>, 
			<a href="/direct-doc.html">no spooler</a>,
			<a href="/ppd-doc.html">PPD aware applications/clients</a>,
			<br><br>
			Important for Windows clients: The CUPS PostScript driver for Windows has a bug which makes 
			it choking on PPD files which contain GUI texts longer than 39 characters. Therefore it is 
			recommended to use Adobe's PostScript driver. If you still want to use the CUPS driver, 
			please mark "GUI texts limited to 39 characters" to get an appropriate PPD file.
			<br><br>
			
			<div style="border: 1px solid #ddd; background: #f5f5f5; padding: 6px; margin-bottom:10px;">
			<form enctype="application/x-www-form-urlencoded" action="{$BASEURL}ppd-o-matic.cgi" method="get">
				<input type="hidden" value="epson" name="driver"/> Select printer: 
				<select tabindex="1" name="printer">
					{foreach from=$printers item=p}	
						<option value="{$p.id}">{$p.make|escape} {$p.model|escape}</option>
					{foreachelse}
						<option>No known printers.</option>
					{/foreach}
				</select> 
				<input type="submit" value="Generate PPD file" name=".submit" tabindex="2"/> 
				<br/> 
				<label><input type="radio" tabindex="3" checked="checked" value="0" name="show"/>download</label> 
				<label><input type="radio" tabindex="4" value="1" name="show"/>display</label> the PPD file 
				<br/> 
				<label><input type="checkbox" tabindex="5" value="on" name="shortgui"/>GUI texts limited to 39 characters</label> 
				<div> 
					<input type="hidden" value="show" name=".cgifields"/> 
					<input type="hidden" value="shortgui" name=".cgifields"/> 
				</div> 
			</form>
			</div>
		{/if}
		
		
		<h2>Comments</h2>
		<div>{$driver.comments|default:"No comments available."}<br /><br /></div>


		{literal}
		<!--<script>
		var idcomments_acct = '55674d13107a5286f1294f678e67e116';
		var idcomments_post_id;
		var idcomments_post_url;
		</script>
		<span id="IDCommentsPostTitle" style="display:none"></span>
		<script type='text/javascript' src='http://www.intensedebate.com/js/genericCommentWrapperV2.js'></script>
		-->
		<div id="disqus_thread"></div>
		<script type="text/javascript">
		    var disqus_developer = true; 
		</script>
		<script type="text/javascript" src="http://disqus.com/forums/openprintingorg/embed.js"></script>
		<noscript><a href="http://disqus.com/forums/openprintingorg/?url=ref">View the discussion thread.</a></noscript>
		<a href="http://disqus.com" class="dsq-brlink">blog comments powered by <span class="logo-disqus">Disqus</span></a>
		{/literal}

		<h2>Printer list</h2>
		<ul>
			{foreach from=$printers item=p}
			{assign var='printerUrl' value="`$BASEURL`printer/`$p.make`/`$p.id`"}	
				<li><a href="{$printerUrl|replace:" ":"+"}">{$p.make|escape} {$p.model|escape}</a></li>
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