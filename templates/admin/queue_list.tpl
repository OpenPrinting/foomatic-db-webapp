{include file="page_masthead.tpl"}

<div id="two_col_col_1">
	{include file="page_breadcrumbs.tpl"}
	
<h1>Manage Queue</h1>

	<p style="border: 1px solid #ccc; background: #eee; padding: 6px; margin-top: 20px; margin-bottom:20px;">
		2 New Printers 4 New Drivers 10 Printers Pending 2 Drivers Pending
	</p>
	
	
	<div id="tabs">
			<ul>
				<li><a href="{$BASEURL}admin_printeruploads.php">Manage Printers</a></li>
				<li><a href="{$BASEURL}admin_driveruploads.php">Manage Drivers</a></li>
			</ul>
			<div id="tabs-1">

			</div>

			<div id="tabs-2">
			
			</div>

	</div>

	<br><br>

</div>
{include file="page_rightcommon.tpl" classtype="two_col_col_2"}
{include file="page_conclusion.tpl"}