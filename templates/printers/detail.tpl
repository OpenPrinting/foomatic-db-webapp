{include file="page_masthead.tpl"}

<div id="two_col_col_1">

{if ($data.noentry != "1" or count($driverinfoboxes) > 0) and
    $data.notreleased != "1"}
	{include file="page_breadcrumbs.tpl"}

	{$printerinfobox}
	{if $data.unverified == "1"}
		<p><b>User-contributed Printer Entry</b><br>
		This printer entry was contributed by a user but was not
		yet verified or proofread by the site administrators.
		Therefore it is not included in the <a
		href="http://www.openprinting.org/foomatic.html">
		Foomatic</a> packages.<br></p>
	{/if}
	{if strlen($forumurl) > 0 and strlen($forummake) > 0}
		<h3>Discussion forum</h3>
		<p>Look for help in our
		<a href="{$forumurl}">
		forum for printers from {$forummake}</a>.</p>
	{/if}
	{if $data.noentry != "1"}
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

	{literal}
	<!--<script>
	var idcomments_acct = '55674d13107a5286f1294f678e67e116';
	var idcomments_post_id;
	var idcomments_post_url;
	</script>
	<span id="IDCommentsPostTitle" style="display:none"></span>
	<script type='text/javascript' src='http://www.intensedebate.com/js/genericCommentWrapperV2.js'></script>-->
	<div id="disqus_thread"></div>
	<script type="text/javascript">
	    var disqus_developer = true; 
	</script>
	<script type="text/javascript" src="http://disqus.com/forums/openprintingorg/embed.js"></script>
	<noscript><a href="http://disqus.com/forums/openprintingorg/?url=ref">View the discussion thread.</a></noscript>
	<a href="http://disqus.com" class="dsq-brlink">blog comments powered by <span class="logo-disqus">Disqus</span></a>
	{/literal}

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
