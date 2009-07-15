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

			<div class="section left">
				<label for="manufacturer">Printer manufactuerer:</label>
				<select id="manufacturer" name="manufacturer">
					<option value="1">Manufacturer name</option>
				</select>
			</div>
			<div class="section left">
				<label for="model">Printer model:</label>
				<select id="model" name="model">
					<option value="1">List all models</option>
					<option value="1">Model #1</option>
					<option value="1">Model #2</option>
					<option value="1">Model #3</option>
				</select>
			</div>
			<div class="section left">
				<label>Submit query</label>
				<input type="submit" value="Show this printer" />
			</div>

		
		</fieldset>
	</form>
	

	<h2>Add a new printer</h2>
	<p>If you have experience with a printer that is not listed here, please contribute to our 
		database by filling out the <a href="#">new printer form</a>.
	</p>
	
</div>
{include file="page_rightcommon.tpl" classtype="two_col_col_2"}
{include file="page_conclusion.tpl"}
