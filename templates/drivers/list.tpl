{include file="page_masthead.tpl"}

<div id="two_col_col_1">
	{include file="page_breadcrumbs.tpl"}

	<h1>Driver listings</h1>

	<p>This is our list of printer drivers for Linux and Unix. You may view information for any given driver and download
		available files by clicking the relevant links. If you know of any driver not represented here, please let us know!</p>
	
	<div class="section" id="for-devs">
		
		{if $UPLOAD_ALLOWED}
			<h2>Upload a driver tarball</h2>
			<p>To upload your driver or check the status of one of your previous uploads, please visit  
				<a href="{$BASEURL}drivers/mydrivers">uploads &amp; statuses</a>.
			</p>	
		{else}
			<p><strong>Driver developers/printer manufacturers:</strong> you may upload printer drivers directly through 
				our website to have them included on our download pages as well as repositories for Linux distros. Register 
				for a LinuxFoundation.org account and contact the OpenPrinting staff to enable this feature.</strong>
			</p>	
		{/if}
	</div>
	
	<h2>List of available drivers</h2>
	
	<table class="data">
		<tr>
			<th /><th>Driver</th><th></th><th>Type</th><th>Description</th>
		</tr>
		{foreach from=$drivers item=d}
			<tr class="{cycle values="alt,0"}">
				<td><img src="{$BASEURL}images/icons/download.png" alt="Download {$d.name|escape}" title="Download {$d.name|escape}" /></td>
				<td><a href="{$BASEURL}driver/{$d.id}/">{$d.name|escape}</a></td>
				<td>{$d.execution|escape}</td>
				<td>{$d.printerCount|default:0}</td>
				<td><small>
					{if $d.shortdescription}
						{$d.shortdescription|escape}
					{else}
						<em style="color: #CCCCCC">No description available.</em>
					{/if}
					</small></td>
			</tr>
		{/foreach}
	</table>
	
</div>
{include file="page_rightcommon.tpl" classtype="two_col_col_2"}
{include file="page_conclusion.tpl"}
