{include file="page_masthead.tpl"}

<div id="two_col_col_1">
	{include file="page_breadcrumbs.tpl"}

	<h1>Printer Listings</h1>
	
	<p>Please choose a printer manufacturer to search for. If you know the specific printer model you would like to view, 
		select the model number from the list as well. Otherwise, choose the "show all" option and all printers made by the 
		selected manufacturer will be listed on your screen.</p>
	
	{if isset($errorMessage)}
		<div class="error">
			<strong>{$errorMessage}</strong>
		</div>
	{/if}
	
		<fieldset class="wide-label clearfix">
			<legend>Query printer database</legend>
			
			<form class="small-form" method="post" action="/printers?action=search">
			<!-- manufacturer combobox -->
			<table cellpadding="4" width="100%" style="border-bottom:1px solid #ddd;">
				<tr>
					<td width="45%" valign="bottom">
						<b>Manufacturer </b><br>
						<select id="manufacturer" name="manufacturer">
						<option value="">--select manufacturer--</option>
						{foreach from=$makes item=make}
							<option value="{$make|escape}">{$make|escape}</option>
						{foreachelse}
							<option value="0">None Avail</option>
						{/foreach}
						</select>
					</td>
					<td width="40%" valign="top">
						<b>Model </b><br>
						<!-- modal combobox is chained by manufacturer combobox-->
						<select name="model" id="model" style="display:none"></select>
					</td>
					<td width="15%" valign="bottom">
						<input type="submit" value="Show this printer" />
					</td>
				</tr>

			</table>
			</form>
			<form class="small-form" method="post" action="/printers?action=searchall">
			<table cellpadding="4">
				<tr>
					<td valign="bottom">
						<b>List by Manufacturer </b><br>
						<select id="showby_manufacturer" name="showby_manufacturer">
						<option value="">--select manufacturer--</option>
						{foreach from=$makes item=make}
							<option value="{$make|escape}">{$make|escape}</option>
						{foreachelse}
							<option value="0">None Avail</option>
						{/foreach}
						</select>
					</td>
					<td valign="bottom">
						<input type="submit" value="Show All" />
					</td>
				</tr>
			</table>
			</form>
		</fieldset>

	

	<h2>Add a new printer</h2>
  {* 05-23-2010 PCN Added logic to toggle message for loggedin and not loggedin users *}
	
</div>
{include file="page_rightcommon.tpl" classtype="two_col_col_2"}
{include file="page_conclusion.tpl"}
