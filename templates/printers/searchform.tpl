{include file="page_masthead.tpl"}

<div id="two_col_col_1">
	{include file="page_breadcrumbs.tpl"}

	<h1>Printer Listings</h1>
	
	<p>Introduction text goes here.</p>
		
	<p>Please choose a printer manufacturer to search for. If you know the specific printer model you would like to view, 
		select the model number from the list as well. Otherwise, choose the "show all" option and all printers made by the 
		selected manufacturer will be listed on your screen.</p>
	
	{if $errorMessage}
		<div class="error">
			<strong>{$errorMessage}</strong>
		</div>
	{/if}
	<form class="small-form" method="post" action="/printers?action=search">
		<fieldset class="wide-label clearfix">
			<legend>Query printer database</legend>
			
				<!-- manufacturer combobox -->
			<table cellpadding="4" width="100%">
				<tr>
					<td width="45%" valign="top">
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
					<td width="15%">
						<input type="submit" value="Show this printer" />
					</td>
				</tr>

			</table>

		</fieldset>
	</form>
	

	<h2>Add a new printer</h2>
	<p>If you have experience with a printer that is not listed here, please contribute to our 
		database by filling out the <a href="/printers/upload">add new printer form</a> on this site.
		If you cannot find what you are looking for please send an email to:<br><br> openprinting [at] linuxfoundation [dot] org
	</p>
	
</div>
{include file="page_rightcommon.tpl" classtype="two_col_col_2"}
{include file="page_conclusion.tpl"}
