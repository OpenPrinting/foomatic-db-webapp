{include file="page_masthead.tpl"}

<div id="two_col_col_1">
	{include file="page_breadcrumbs.tpl"}

	<h1>Printer listings</h1>
	
	<p>Introduction text goes here.</p>
		
	<p>Please choose a printer manufacturer to search for. If you know the specific printer model you would like to view, 
		select the model number from the list as well. Otherwise, choose the "show all" option and all printers made by the 
		selected manufacturer will be listed on your screen.</p>
	
	<form class="small-form" method="get" action="/printers/search">
		<fieldset class="wide-label clearfix">
			<legend>Query printer database</legend>
			
				<!-- manufacturer combobox -->
			<p>
				<label for="manufacturer">Manufacturer:</label>
				<select id="manufacturer" name="manufacturer">
					<option value="">--select manufacturer--</option>
					{foreach from=$makes item=make}
						<option value="{$make|escape}">{$make|escape}</option>
					{foreachelse}
						<option value="0">None Avail</option>
					{/foreach}
				</select>
			</p>
			<p>
				<label for="manufacturer">Model:</label>
				<!-- model combobox is chained by manufacturer combobox-->
				<select name="model" id="model" style="display:none"></select>
			</p>
			<p>
				<label>Submit query</label>
				<input type="submit" value="Show this printer" />
			</p>
		</fieldset>
	</form>
	

	<h2>Add a new printer</h2>
	<p>If you have experience with a printer that is not listed here, please contribute to our 
		database by filling out the <a href="#">new printer form</a>.
	</p>
	
</div>
{include file="page_rightcommon.tpl" classtype="two_col_col_2"}
{include file="page_conclusion.tpl"}
