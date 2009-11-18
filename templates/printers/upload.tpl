{include file="page_masthead.tpl"}

<div id="two_col_col_1">
	{include file="page_breadcrumbs.tpl"}

	<h1>Add a New Printer</h1>

<p>There are a fair number of things kept in this database about each printer. Unfortunately, this makes for a somewhat long form.</p>

<p>Please take a few minutes and fill out anything you can. Note that your printer entry is only useful if the reader can see how he gets the printer to work (driver, where to get the driver if it is not yet listed at OpenPrinting, to which printer is yours compatible, special tricks, ...) or if he gets the message that this model definitely does not work. If you get red warning/error messages, read them and try to fix the problems. Use "Save Anyway" only if you are absolutely sure that you have done it the right way.</p>

<p>Note that if you make a mistake you can edit everything at any time after submitting. So do not create another printer entry if you are not content. Simply correct your entry where needed.</p>
<br>	

{if $isLoggedIn == "1" }

<h2>Printer Information</h2>
<br>
<form action="/printers/upload" method="post">
	{if $isTrusted || $isAdmin }
		<input type="hidden" value="1" name="noqueue"/>
	{/if}	


	<input type="submit" name="submit" value="Add Printer"> <a href="/printers/upload">Cancel</a>	
	<br><br>
	<table cellpadding="4" style="background: #eee; border: 1px solid #ccc;">
		<tr bgcolor="#dfdfdf">
			<td align="right" width="20%">Release Date:</td> 
			<td width="45%"><input type="text" size="10" tabindex="1" id="datepicker" name="release_date"/> </td>
			<td width="35%"><p>Future release date</p></td>
		</tr> 
		<tr bgcolor="#dfdfdf">
			<td align="right">Manufacturer:</td> 
			<td>
				<select tabindex="2" name="make">
					<option value="" selected="selected">--select manufacturer--</option>
					{foreach from=$makes item=make}
						<option value="{$make|escape}">{$make|escape}</option>
					{foreachelse}
						<option value="0">None Avail</option>
					{/foreach}

					</select>  
					OR  
					<input type="text" size="16" tabindex="4" name="make_new"/>
				</td> 
				<td><p>Manufacturer name for the printer. If there
	                         are already printers of this manufacturer,
	                         choose the manufacturer name from the menu
	                         and leave the input field blank.<br/>
	                         DO NOT write the model name into the input
	                         field, the model name goes into the field below. 
	                         Use the input field for manufacturers which are
	                         not listed yet.</p></td>
			</tr> 
			<tr bgcolor="#efefef">
				<td align="right">Model:</td> 
				<td><input type="text" size="32" tabindex="5" name="model"/></td> 
				<td><p>Model name for the printer.  Please try
				 to follow the conventions used for
				 other printers in the same
				 family. DO NOT repeat the manufacturer's name
	             in this field.</p>
				</td>
			</tr> 
			<tr bgcolor="#dfdfdf">
				<td align="right">URL:</td> 
				<td><input type="text" maxlength="128" size="32" tabindex="6" name="url"/></td> 
				<td><p>Manufacturer's web page for this specific printer.  The maker's home page will 
				already be linked to, so if you don't know where to find a page about this printer, 
				leave this blank. And do not forget the "http://" in the beginning of 
				the address.</p></td>
			</tr> 
			<tr bgcolor="#efefef">
				<td align="right">Resolution (X x Y):</td> 
				<td><input type="text" size="4" tabindex="7" name="resolution_x"/> x <input type="text" size="4" tabindex="8" name="resolution_y"/></td> 
				<td><p>Maximum X and Y resolution the printer can do.  Available Unix software may not support 
				the finest modes; if so, please say so in the notes.</p></td>
			</tr> 
			<tr bgcolor="#dfdfdf">
				<td align="right">Color:</td> 
				<td><label><input type="checkbox" tabindex="9" value="on" name="color"/></label></td> 
				<td><p>Check the box if this printer can do color.  Some printers may not be able to do 
				so without vendor drivers; say so in the notes if so.</p></td>
			</tr> 
			<tr bgcolor="#efefef">
				<td align="right">Mechanism:</td> 
				<td>
					<select tabindex="10" name="type">
						<option value="">Unknown/Other</option>
						<option value="dotmatrix">Dot Matrix</option>
						<option value="impact">Impact</option>
						<option value="inkjet">Inkjet</option>
						<option value="laser">Laser</option>
						<option value="led">LED</option>
						<option value="sublimation">Dye Sublimation</option>
						<option value="transfer">Thermal Transfer</option>
					</select>
				</td> 
				<td><p>What sort of printing mechanism does this printer use?</p></td>
			</tr> 
			<tr bgcolor="#dfdfdf">
				<td align="right">Refill:</td> 
				<td><input type="text" size="32" tabindex="11" name="refill"/></td> 
				<td><p>A short description of the non-paper consumable(s): cartridge, ribbon, toner, 
				printhead, etc.  Ballpark refill pricing would be nice, too, if known.</p></td>
			</tr> 
			<tr bgcolor="#efefef">
				<td align="right">Language:</td> 
				<td><label><input type="checkbox" tabindex="12" value="on" name="postscript"/>PostScript</label> level <input type="text" size="4" tabindex="13" name="postscript_level"/> 
				<br/>    
				URL for manufacturer's PPD <br/>     
				<input type="text" size="30" tabindex="14" name="ppdurl"/> <br/> 
				<label><input type="checkbox" tabindex="15" value="on" name="pdf"/>PDF</label> level <input type="text" size="4" tabindex="16" name="pdf_level"/> <br/>  
				<label><input type="checkbox" tabindex="17" value="on" name="lips"/>LIPS</label> level <input type="text" size="4" tabindex="18" name="lips_level"/> <br/>
				<label><input type="checkbox" tabindex="19" value="on" name="pcl"/>PCL</label> level <input type="text" size="4" tabindex="20" name="pcl_level"/> <br/> 
				<label><input type="checkbox" tabindex="21" value="on" name="escp"/>ESC/P</label> level <input type="text" size="4" tabindex="22" name="escp_level"/> <br/> 
				<label><input type="checkbox" tabindex="23" value="on" name="escp2"/>ESC/P 2</label> level <input type="text" size="4" tabindex="24" name="escp2_level"/> <br/> 
				<label><input type="checkbox" tabindex="25" value="on" name="hpgl2"/>HP-GL/2</label> level <input type="text" size="4" tabindex="26" name="hpgl2_level"/> <br/> 
				<label><input type="checkbox" tabindex="27" value="on" name="tiff"/>TIFF</label> level <input type="text" size="4" tabindex="28" name="tiff_level"/> <br/> 
				<label><input type="checkbox" tabindex="29" value="on" name="proprietary"/>Proprietary</label></td> 
				<td><p>The printer control language spoken by this printer, and level or version if known.  Mail us and add a remark in the 
				"Notes:" field if we've forgotten any languages.</p></td>
			</tr> 
			<tr bgcolor="#dfdfdf">
				<td align="right">ASCII:</td> 
				<td><label><input type="checkbox" tabindex="30" value="on" name="ascii"/></label></td> 
				<td><p>This printer will print text if you just send it plain ascii.  
				Uncheck for printers that <b>only</b> work with Ghostscript and a driver or the like.</p></td>
			</tr> 
			<tr bgcolor="#efefef">
				<td align="right">PJL:</td> 
				<td><label><input type="checkbox" tabindex="31" value="on" name="pjl"/></label></td> 
				<td><p>Check the box if this printer supports HP's Printer Job Language (PJL).</p></td>
			</tr> 
			<tr bgcolor="#dfdfdf">
				<td align="right">Functionality:</td> 
				<td>
					<select tabindex="32" name="func">
						<option value="A">Perfectly</option>
						<option value="B">Mostly</option>
						<option value="D">Partially</option>
						<option value="F" selected="selected">Paperweight</option>
					</select> 
					<font color="red"/></td> 
				<td><p>How well does this printer work using Un*x software (ie ghostscript).  Put details 
				into the "Notes:" field.  Mostly means it prints, but minor things are missing. Partially 
				means it prints, but major things are missing.<br/>If you choose a non-Paperweight rating, 
				choose/enter a driver and/or enter in the "Notes:" field how you made this printer working.</p></td>
			</tr> 
			<tr bgcolor="#efefef">
				<td align="right">
					Driver:</td> 
				<td>
					<input type="hidden" value="1" name="dnumber"/>
					<input type="hidden" value="on" name="dactive0"/>
					
					<select tabindex="33" name="dname0">
						<option value="" selected="selected">No driver</option>
						{foreach from=$drivers item=driver}
							<option value="{$driver.id}">{$driver.name|escape}</option>
						{/foreach}	
					</select>  
						<br><br> OR 
					<input type="text" size="16" tabindex="34" name="dname1"/></td> 
				<td><p>A driver known to work. </p></td>
			</tr> 			
			<tr bgcolor="#efefef">
				<td align="right">Driver notes:</td> 
				<td><textarea cols="35" rows="4" tabindex="35" name="dcomment0"></textarea></td> 
				<td><p>Comments on using the above driver with this printer.</p></td>
			</tr> 

			<tr bgcolor="#dfdfdf">
				<td align="right">Un*x URL:</td> 
				<td><input type="text" size="32" tabindex="36" name="contrib_url"/></td> 
				<td><p>Web address for important info about using this printer with Unix-like 
					operating systems/free software; website with special tricks, mini-HOWTO, 
					a user's experience, or whatever else helps to make it going. Do not forget the 
					"http://" in the beginning of the address.</p></td>
			</tr> 
			<tr bgcolor="#efefef">
					<td valign="top" align="right">Notes:</td> 
					<td colspan="2"><p><font size="-1" color="#202020">This is HTML that just gets pasted
					into the table; <font color="#000000">watch those &lt;
					signs!</font> Anything big can be
					either linked to with the Un*x
					URL/More Info link, or you can mail us
					the comments to set up as a locally
					served page.</font></p>
					</td>
				</tr> 
				<tr bgcolor="#efefef">
					<td> </td> 
					<td colspan="2"><textarea cols="50" rows="10" tabindex="37" name="notes"/></textarea></td>
				</tr> 
				<tr bgcolor="#dfdfdf">
					<td valign="top" align="right">Auto-detect:</td> 
					<td colspan="2"><p><font size="-1">Auto-detection info for this printer (if you 
					have the possibility to connect your printer in different ways, 
					try all to gather as much auto-detection info as possible), 
					do not fill this in if you are not able to get this data from 
					the actual printer:</font></p><p><font size="-1"><b>Parallel port:</b> 
					Parport probe information for this printer. These should be exactly the 
					contents of the lines from /proc/parport/#/autoprobe (kernel 2.2.x) or 
					/proc/sys/dev/parport/parport#/autoprobe* (kernel 2.4.x and 2.6.x). 
					"#" is the parallel port number; ie typically 0, "*" can be nothing or a 
					number.  Remove the leading MODEL:, MANUFACTURER:, DESCRIPTION:, 
					and COMMAND SET:, and also remove the ending semicolon.  
					If you had <tt>MODEL: Stylus Color 670;</tt>, for example, 
					you'd put <tt>Stylus Color 670</tt> in the MODEL field here.</font></p>
					<p><font size="-1"><b>USB:</b> 
					Download the "<a href="/download/printing/getusbprinterid.pl">getusbprinterid.pl</a>" 
					Perl script, make it executable ("chmod a+rx getusbprinterid.pl"), 
					and then run (as "root") "./getusbprinterid.pl /dev/usb/lp0" 
					(or "/dev/usblp0", "/dev/usb/lp1", or whatever the USB device 
					file to access your printer is). If your printer is configured 
					with <a href="http://hpoj.sf.net/">HPOJ</a> use the "ptal-devid" command. 
					You will get the so-called device ID string as output. Cut and paste this into 
					the "IEEE-1284 Device ID String" field. Take care that all is on one line in the field. 
					Put also the elements of the IEEE string into the appropriate fields "MANUFACTURER/MFG", 
					"MODEL/MDL", ...</font></p><p><font size="-1"><b>Network printer:</b> 
					Auto-detection is done via SNMP (Simple Network Management Protocol). 
					Download and install <a href="http://www.ibr.cs.tu-bs.de/projects/scli/">SCLI</a> 
					and run "scli -c 'show printer info' &lt;host name of the printer&gt;". 
					Look for a "Description:" field in the output. Copy and paste its contents into the 
					"Description" field below.</font></p>
					<p><font size="-1">
						In most cases the IEEE-1284 auto-detection data is the same for USB 
						and parallel port. So usually you should put this data into the "General" 
						section below. If you see any deviations, enter them in the "Parallel Port" and 
						"USB" sections. Leave fields blank if they are identical to the entry in the 
						"General" section, if they are blank, or if they do not exist in your observed 
						auto-detection data.</font></p></td>
				</tr> 
				<tr bgcolor="#dfdfdf">
					<td> </td> 
					<td colspan="2">
						<dl><dt><b>General (Parallel and USB)</b><br/>IEEE-1284 Device ID String</dt> 
						<dd><input type="text" size="50" tabindex="38" name="general_ieee"/></dd> 
						<dt>MANUFACTURER/MFG</dt> <dd><input type="text" size="32" tabindex="39" name="general_mfg"/></dd> 
						<dt>MODEL/MDL</dt> <dd><input type="text" size="32" tabindex="40" name="general_mdl"/></dd> 
						<dt>DESCRIPTION/DES</dt> <dd><input type="text" size="32" tabindex="41" name="general_des"/></dd> 
						<dt>COMMAND SET/CMD</dt> <dd><input type="text" size="32" tabindex="42" name="general_cmd"/></dd> <dt>
							<br/><b>Parallel Port</b>
							<br/>IEEE-1284 Device ID String</dt> 
							<dd><input type="text" size="50" tabindex="43" name="par_ieee"/></dd> 
						<dt>MANUFACTURER</dt> <dd><input type="text" size="32" tabindex="44" name="par_mfg"/></dd> 
						<dt>MODEL</dt> <dd><input type="text" size="32" tabindex="45" name="par_mdl"/></dd> 
						<dt>DESCRIPTION</dt> <dd><input type="text" size="32" tabindex="46" name="par_des"/></dd> 
						<dt>COMMAND SET</dt> <dd><input type="text" size="32" tabindex="47" name="par_cmd"/></dd> 
						<dt><br/><b>USB</b><br/>IEEE-1284 Device ID String</dt> 
						<dd><input type="text" size="50" tabindex="48" name="usb_ieee"/></dd> 
						<dt>MANUFACTURER/MFG</dt> <dd><input type="text" size="32" tabindex="49" name="usb_mfg"/></dd> 
						<dt>MODEL/MDL</dt> <dd><input type="text" size="32" tabindex="50" name="usb_mdl"/></dd> 
						<dt>DESCRIPTION/DES</dt> <dd><input type="text" size="32" tabindex="51" name="usb_des"/></dd> 
						<dt>COMMAND SET/CMD</dt> <dd><input type="text" size="32" tabindex="52" name="usb_cmd"/></dd> 
						<dt><br/><b>Network Printer (SNMP)</b><br/>Description</dt> 
						<dd><input type="text" size="32" tabindex="53" name="snmp_des"/></dd></dl>
					</td>
				</tr>
			</table>
					

<br>
<input type="submit" name="submit" value="Add Printer"> <a href="/printers/upload">Cancel</a>
</form>
{/if}

</div>
{include file="page_rightcommon.tpl" classtype="two_col_col_2"}
{include file="page_conclusion.tpl"}