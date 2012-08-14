{include file="page_masthead.tpl"}

<div id="two_col_col_1">
	{include file="page_breadcrumbs.tpl"}


	<h1>Add New Driver</h1>

	<p>Thank you for taking the time to upload a printer driver tarball through our web interface.
		Your uploaded driver will be made available for download through our website, but also made 
		available to repositories and Linux distros supporting RPM and DEB packages.</p>
		
	<p>This system is, for the most part, fully automated. If you are a "trusted" contributor, 
		your upload will automatically be made available for download once it has been processed unless 
		we detect a problem. Otherwise, an OpenPrinting moderator will review your upload (including 
		installing it on a test machine) before it is included in the archives and made available.</p>


	<h2>Documentation</h2>
	<p>Please take the time to review the documentation for creating driver packages before proceeding.</p>
	<ul class="bulleted">
		<li><a href="{$MAINURL}/collaborate/workgroups/openprinting/database/databaseintro">Database Introduction</a></li>
	</ul>

{if $isLoggedIn == "1" }
	
	{if $isUploader || $isTrustedUploader || $isAdmin }

	{if $msg=="success"}

		<div class="success">
			The driver specifications have been uploaded to the system!
		</div>	
		<a href="/drivers/upload">Add a New Driver</a>
		
	{elseif $msg=="error"}

		<div class="error">
			{$error}
		</div>	
		<a href="/drivers/upload">Add a New Driver</a>
	
	{else}
		
	<br>	
	<form method="post" action="/drivers/upload" enctype="multipart/form-data" onsubmit='return validate_form(this)'>
	
	<h2>Driver Information</h2>
	<table cellpadding="4" style="background: #eee; border: 1px solid #ccc;">
		<tr>
			<td width="20%">Upload Comment </td>
			<td width="40%"><textarea name="comment" id="comment" rows="10" cols='30'></textarea></td>
			<td width="40%">Comment about this upload. If you need someone to approve your entry 
			(you are not Trusted Uploader or Administrator) you can put some message for the approver 
			here. This field has the function of a whiteboard for the approval process then. 
			The approver will enter here what you need to correct in order to get approval, 
			or the reasons why he rejects your contribution. You can answer to the approver 
			here then ... If your entry gets automatically approved, please enter a comment 
			here which should appear in the ChangeLog files of the foomatic-db and foomatic-db-nonfree 
			packages. The comment does not need to contain your name, the date, or the list of added 
			or changed files. All this information is added to the overview table for the approvers 
			and to the ChangeLog files automatically.</td>
		</tr>
		<tr>
			<td>Release Date </td>
			<td><input type="text" size="12" name="release_date" id="datepicker"></td>
			<td>If entered this driver will not get shown on the web site or added to the Foomatic XML database before this date. Publication happens in the beginning of the specified day in UTC.</td>
		</tr>	
		<tr>
			<td width="20%">Driver name <font color="red"><b>*</b></font></td>
			<td width="40%"><input type="text" name="driver_name" id="driver_name"></td>
			<td width="40%">Name of the driver, for example "ljet4". Please do not use spaces (required).</td>
		</tr>
		<tr>
			<td>Download URL <font color="red"><b>*</b></font></td>
			<td><input type="text" name="download_url" id="download_url"></td>
			<td>URL of the driver's home page. It must give the user the possibility to download the driver and get more info about it. Make sure to start with something like "http://..." or "https://..." to not create a relative link within OpenPrinting.</td>
		</tr>
	
		<tr>
			<td>Obsolete/Replacement </td>
			<td>
				<select name="obsolete" id="obsolete">
					<option value="">Not Obsolete</option>
				{foreach from=$drivers item=driver}
					<option value="{$driver.id}">{$driver.name|escape}</option>
				{/foreach}	
				</select>
			</td>
			<td>If this driver is obsolete and it is recommended to use another driver, please choose the driver which users should use instead of this one.</td>
		</tr>
		<tr>
			<td>Short Description <font color="red"><b>*</b></font></td>
			<td><input type="text" name="description" id="discription" ></td>
			<td>Short description for the driver. About one line. Example: "Ghostscript driver for PCL 5c color laser printers"</td>
		</tr>
		<tr>
			<td>Supplier <font color="red"><b>*</b></font></td>
			<td><input type="text" name="supplier" id="supplier"></td>
			<td>Name of the supplier of the driver, for example "Ricoh", "HP", "SpliX project", ...</td>
		</tr>
		<tr>
			<td>Manufacturer supplied? </td>
			<td><input type="checkbox" name="manufacturersupplied[]" id="manufacturersupplied" value="1"> Yes</td>
			<td>Check this if the driver's supplier is the manufacturer of the printers which the driver is supposed to support.</td>
		</tr>
		<tr>
			<td>License <font color="red"><b>*</b></font></td>
			<td>
			{html_options name=license options=$licenseOptions selected=$licenseSelect}
			<br>or type license name:<br>
			<input type="text" name="licensecustom" id="licensecustom">
			</td>
			<td>Name of the license under which the driver is published, like "GPL", "GPL 2+", ... Use "Commercial" for proprietary closed-source licenses which do not have a special name.</td>
		</tr>
		<tr>
			<td>Non-free software? </td>
			<td><input type="checkbox" name="nonfreesoftware[]" id="nonfreesoftware" value="1"> Yes</td>
			<td>Check this if the driver is published under a license which is not considered a free-software license (see http://www.fsf.org/ or http://www.opensource.org/).</td>
		</tr>
		<tr>
			<td>Patent issues? </td>
			<td><input type="checkbox" name="patents[]" id="patents" value="1"> Yes</td>
			<td>Check this if the driver uses algorithms which are (possibly) patented. Please give more details in the "License Text" field. </td>
		</tr>
		<tr>
			<td>License Text </td>
			<td><textarea name="licensetext" id="licensetext" rows="8" cols="28"></textarea></td>
			<td>If the license is not a commonly known free software license, please paste the license text into this field. Especially if you want that a printer setup tool shows the license text before downloading your driver you must paste the license text here. If your driver has patent issues, please enter comments about that here, too, but always at the top, before the license text. Do not use HTML, use plain text in ASCII or UTF-8 encoding.</td>
		</tr>
		<tr>
			<td>License link </td>
			<td><input type="text" name="licenselink" id="licenselink"></td>
			<td>Instead of pasting your license text into the field above you can alternatively give a link to your license text on your site. It must also be plain text in ASCII or UTF-8.</td>
		</tr>
	</table>
	
	<br>
	<h2>Support Contact Information</h2>
	<table cellpadding="4" style="background: #eee; border: 1px solid #ccc;">
		<tr>
			<td width="20%">Support Description <font color="red"><b>*</b></font></td>
			<td width="40%"><textarea name="supportdescription" id="supportdescription" rows="1" cols="26"></textarea></td>
			<td width="40%">Short description for the support contact, like "SpliX user forum" or "Canon customer support"</td>
		</tr>
		<tr>
			<td>Support URL <font color="red"><b>*</b></font></td>
			<td><input type="text" name="supporturl" id="supporturl"></td>
			<td>URL to the web site supplying or explaining the support. Make sure to start with something like "http://..." or "https://..." to not create a relative link within OpenPrinting.</td>
		</tr>
		<tr>
			<td>Support Level <font color="red"><b>*</b></font></td>
			<td>
					<input type="radio" name="supportlevel" id="supportlevel_1" value="voluntary"> Voluntary
					<br>
					<input type="radio" name="supportlevel" id="supportlevel_2" value="commercial"> Commercial
			</td>
			<td>Select the support level. Commercial support is with guaranteed answers, usually financed by a fee to be payed by the customer or by the purchase price of the printer. Voluntary support is without any obligations by the supporter, for example support from free software developers or user-to-user support by web forums.</td>
		</tr>
	</table>
	
	<br>
	<h2>General Information</h2>
	<table cellpadding="4" style="background: #eee; border: 1px solid #ccc;">
		<tr>
			<td width="20%">Driver Type <font color="red"><b>*</b></font></td>
			<td width="40%">
					<input type="radio" name="execution" id="execution-01" value="ghostscript"> Ghostscript Built-in
					<br>
					<input type="radio" name="execution" id="execution-02" value="uniprint"> Ghostscript Uniprint
					<br>
					<input type="radio" name="execution" id="execution-03" value="filter"> Filter
					<br>
					<input type="radio" name="execution" id="execution-04" value="cups"> CUPS Raster
					<br>
					<input type="radio" name="execution" id="execution-05" value="ijs"> IJS Plugin
					<br>
					<input type="radio" name="execution" id="execution-06" value="opvp"> OpenPrinting Vector
					<br>
					<input type="radio" name="execution" id="execution-07" value="postscript"> Postscript
			</td>
			<td width="40%"></td>
		</tr>
		<tr>
			<td>Maximum Resolution </td>
			<td><input type="text" name="max_res_x" id="max_res_x" size="5"> x <input type="text" name="max_res_y" id="max_res_y" size="5"></td>
			<td>Enter the maximum resolution supported by the driver here.</td>
		</tr>
		<tr>
			<td>Color </td>
			<td>
					<input type="checkbox" name="grayscale[]" id="color-02" value="1"> Greyscale/Monochrome
					<input type="checkbox" name="color[]" id="color-01" value="1"> Color
					
			</td>
			<td>Check this if the driver supports greyscale and or color output</td>
		</tr>
		<tr>
			<td>Suitability for </td>
			<td>
				<table>
					<tr>
						<td>Text</td>
						<td>{html_options name=text options=$scaleOption selected=$scaleSelect}</td>
					</tr>
					<tr>
						<td>Lineart</td> 
						<td>{html_options name=lineart options=$scaleOption selected=$scaleSelect}</td>
					</tr>
					<tr>
						<td>Graphics</td> 
						<td>{html_options name=graphics options=$scaleOption selected=$scaleSelect}</td>
					</tr>
					<tr>
						<td>Photo</td> 
						<td>{html_options name=photo options=$scaleOption selected=$scaleSelect}</td>
					</tr>
					<tr>
						<td>Load</td> 
						<td>{html_options name=load_time options=$scaleOption selected=$scaleSelect}</td>
					</tr>
					<tr>
						<td>Speed</td> 
						<td>{html_options name=speed options=$scaleOption selected=$scaleSelect}</td>
					</tr>
				</table>
			</td>
			<td>
				Your impression (0: Unusable, 100: Perfect) on how well this driver performs with common printing tasks. Enter values here if the performance for this printer differs from the general values.
				<br>
				Your impression (0: Unusable, 100: Perfect) on how much system resources the driver needs. Enter only a value here if this printer's performance differs from the general value.
				<br>
				Your impression (0: Unusable, 100: Perfect) on how fast the driver renders print jobs. Enter only a value here if this printer's performance differs from the general value.
			</td>
		</tr>
	</table>
	
	<br>
	<h2>Upload your tarball</h2>
	
	<p>Browse for the tarball on your local machine, then submit the form. 
	<strong>Please make sure your file name is in the format &lt;driver&gt;-&lt;version&gt;.tar.gz</strong>, otherwise 
		your upload will be rejected. Once your file has been uploaded, we will perform some checks to make sure everything 
		is okay.
	</p>
	
	{if !$isTrustedUploader && !$isAdmin }
		<p><strong>Note:</strong> your driver will be placed in the moderation queue and will be reviewed by an administrator
			before being added to the database.</p>
			<input type="hidden" name="submitQueue" value="1" >
	{/if}
		
	
	<input type="hidden" name="MAX_FILE_SIZE" value="104857600" />
	<input type="file" name="payload">
	
	
	<br><br>
	<input type="submit" name="submit" value="Submit Driver"> <a href="/account/myuploads">Cancel</a>
	
	</form>
	
	{literal}
	  <script language="javascript" type="text/javascript">
	
	    
		function validate_required(field,alerttxt)
		{
			with (field)
			{
				if (value==null||value==""){
					alert(alerttxt);
					return false;
				}
				else {
					return true;
				}
			}
		}
			
		function validate_form(thisform)
		{
		
	
			
			with (thisform)
			{
								
				if (validate_required(driver_name,"Driver Name is Required!")==false){
					driver_name.focus();
					return false;
				}
				if (validate_required(download_url,"Download URL is Required!")==false){
					download_url.focus();
					return false;
				}
				if (validate_required(discription,"Description is Required!")==false){
					discription.focus();
					return false;
				}
				if (validate_required(supplier,"Supplier is Required!")==false){
					supplier.focus();
					return false;
				}
				
				if(thisform.license.value == "" && thisform.licensecustom.value == ""){			
					if (validate_required(license,"A License is Required!")==false){
						license.focus();
						return false;
					}
				}				
				if (validate_required(supportdescription,"Support Description is Required!")==false){
					supportdescription.focus();
					return false;
				}	
				if (validate_required(supporturl,"Support URL is Required!")==false){
					supporturl.focus();
					return false;
				}
									
				var supportlevel_chosen = "";	
				len = thisform.supportlevel.length;
	
				for (i = 0; i < len; i++) {
					if (supportlevel[i].checked) {
						supportlevel_chosen = supportlevel[i].value;
					}
				 }
		
				if (supportlevel_chosen == "") {
					alert("Support Level Required!");
					supportlevel[0].focus();
					return false;
				}
								
				var execution_chosen = "";	
				len = thisform.execution.length;
	
				for (i = 0; i < len; i++) {
					if (execution[i].checked) {
						execution_chosen = execution[i].value;
					}
				 }
		
				if (execution_chosen == "") {
					alert("Driver Type Required!");
					execution[0].focus();
					return false;
				}
			}
			
		}
	    </script>
	{/literal}
	
	{/if}
	
	{else}
		<p>You do not have permissions to upload drivers.</p>
	{/if}
	
{else}
	<p>Please log in.</p>
{/if}
		
</div>
{include file="page_rightcommon.tpl" classtype="two_col_col_2"}
{include file="page_conclusion.tpl"}
