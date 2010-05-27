{include file="page_masthead.tpl"}

<div id="two_col_col_1">
	{include file="page_breadcrumbs.tpl"}

	<h1>My Uploads</h1>
	
  {if $isUploader || $isTrustedUploader || $isAdmin }
      <a href="{$BASEURL}drivers/upload" >Upload New Driver</a>
  {/if}&nbsp;&nbsp;
  {if $isTrusted || $isAdmin }
      <a href="{$BASEURL}printers/upload" >Upload New Printer</a>
	{/if}
  <br/>
	<div id="tabs">
			<ul>
				<li><a href="{$BASEURL}account_printeruploads.php">Printers</a></li>
				<li><a href="{$BASEURL}account_driveruploads.php">Drivers</a></li>
			</ul>
			<div id="tabs-1">

			</div>

			<div id="tabs-2">
			
			</div>

	</div>

	<br><br>
	<!--
	<h2>Uploads currently being processed</h2>
	<p>The following driver uploads are either being processed by our system, waiting for administrator 
		approval, or require additional information. The respective status of each upload is shown.</p>
		
	<h2>Previously accepted uploads</h2>
	<p>The most recent versions of drivers you have uploaded in the past are shown here.</p>
	-->


		
</div>
{include file="page_rightcommon.tpl" classtype="two_col_col_2"}
{include file="page_conclusion.tpl"}