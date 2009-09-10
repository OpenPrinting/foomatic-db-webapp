{include file="page_masthead.tpl"}

<div id="two_col_col_1">
	{include file="page_breadcrumbs.tpl"}

	<h1>Printer listings</h1>
	
	<p>Introduction text goes here.</p>
		
	<p>Please choose a printer manufacturer to search for. If you know the specific printer model you would like to view, 
		select the model number from the list as well. Otherwise, choose the "show all" option and all printers made by the 
		selected manufacturer will be listed on your screen.</p>
	
	<form class="small-form" method="post" action="?search">
		<fieldset class="wide-label clearfix">
			<legend>Query printer database</legend>

			<div id="showmodel" style="display: none;">
				<div class="section left">
					<label for="manufacturer">Printer manufactuerer:</label>
					<select size="10" id="manufacturer" name="manufacturer">
						{foreach from=$makes item=make}
							<option value="{$make|escape}">{$make|escape}</option>
						{foreachelse}
							<option value="0">None</option>
						{/foreach}
					</select>
				</div>
				<div class="section left">
					<label for="model">Printer model:</label>
					<select size="10" id="model" name="model">
						
					</select>
				</div>
				<div class="section left">
					<label>Submit query</label>
					<input type="submit" value="Show this printer" />
				</div>
			</div>
			
		
			<!-- unhide the form if javascript is allowed -->
			<script type="text/javascript">
				document.getElementById('showmodel').style.display="block";
			</script>
			<noscript>
				<p><strong>JavaScript is currently disabled or not supported by your web browser.</strong> We cannot display the list of printer models for each manufacturer on this page without JavaScript. However, you can still show all printers by manufacturer using the option below.</p>
			</noscript>
			
			<!-- Onchange script -->
			<script type="text/javascript">
				
			</script>
		
		</fieldset>
	</form>
	

	<h2>Add a new printer</h2>
	<p>If you have experience with a printer that is not listed here, please contribute to our 
		database by filling out the <a href="#">new printer form</a>.
	</p>
	
</div>
{include file="page_rightcommon.tpl" classtype="two_col_col_2"}
{include file="page_conclusion.tpl"}
