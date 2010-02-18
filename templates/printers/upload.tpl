{include file="page_masthead.tpl"}

<div id="two_col_col_1">
	{include file="page_breadcrumbs.tpl"}

{if $msg=="success"}
<h1>Add a New Printer</h1>
<div class="success">
	The printer specifications have been uploaded to the system!
</div>	
<a href="/printers/upload">Add a New Printer</a>

{elseif $msg=="error"}
<h1>Add a New Printer</h1>
<div class="error">
	{if $type=='exists'}
	Printer already exists in the database!
	{/if}
</div>	
<a href="/printers/upload">Add a New Printer</a>

{else}
<h1>Add a New Printer</h1>

<p>There are a fair number of things kept in this database about each printer. Unfortunately, this makes for a somewhat long form.</p>

<p>Please take a few minutes and fill out anything you can. Note that your printer entry is only useful if the reader can see how he gets the printer to work (driver, where to get the driver if it is not yet listed at OpenPrinting, to which printer is yours compatible, special tricks, ...) or if he gets the message that this model definitely does not work.</p>

<!--<p>Note that if you make a mistake you can edit everything at any time after submitting. So do not create another printer entry if you are not content. Simply correct your entry where needed.</p>-->
<br>	

{if $isLoggedIn == "1" }

<h2>Printer Information</h2>
<br>
<form action="/printers/upload" method="post" onsubmit='return validate_form(this)'>

	{if $isTrusted || $isAdmin }
		<input type="hidden" value="1" name="noqueue"/>
	{/if}	


	<input type="submit" name="submit" value="Add Printer"> <a href="/printers/upload">Cancel</a>	
	<br><br>
	<table cellpadding="4" style="background: #eee; border: 1px solid #ccc;">
		<tr bgcolor="#dfdfdf">
			<td align="right" width="20%" valign="top">Comments:</td> 
			<td width="45%" colspan=2><textarea id="comments" name="comments" cols="55" rows="8" /></textarea> <p>Comment about this upload. If you need someone to approve 
			your entry (you are not Trusted Uploader or Administrator) you can put some message for the 
			approver here. This field has the function of a whiteboard for the approval process then. 
			The approver will enter here what you need to correct in order to get approval, or the reasons 
			why he rejects your contribution. You can answer to the approver here then ... 
			If your entry gets automatically approved, please enter a comment here which should 
			appear in the ChangeLog files of the foomatic-db and foomatic-db-nonfree packages. 
			The comment does not need to contain your name, the date, or the list of added or 
			changed files. All this information is added to the overview table for the approvers 
			and to the ChangeLog files automatically.</p></td>
		</tr> 
		<tr bgcolor="#efefef">
			<td align="right" width="20%">Release Date:</td> 
			<td width="45%"><input type="text" size="10" tabindex="1" id="datepicker" name="release_date"/> </td>
			<td width="35%"><p>Future release date</p></td>
		</tr> 
		<tr bgcolor="#dfdfdf">
			<td align="right" valign="top">Manufacturer <font color="red"><b>*</b></font>:</td> 
			<td valign="top">
				<select tabindex="2" name="make">
					<option value="" selected="selected">--select manufacturer--</option>
					{foreach from=$makes item=make}
						<option value="{$make|escape}">{$make|escape}</option>
					{foreachelse}
						<option value="0">None Available</option>
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
				<td align="right" valign="top">Model <font color="red"><b>*</b></font>:</td> 
				<td valign="top"><input type="text" size="32" tabindex="5" name="model"/></td> 
				<td><p>Model name for the printer.  Please try
				 to follow the conventions used for
				 other printers in the same
				 family. DO NOT repeat the manufacturer's name
	             in this field.</p>
				</td>
			</tr> 
			<tr bgcolor="#dfdfdf">
				<td align="right"  valign="top">URL:</td> 
				<td valign="top"><input type="text" maxlength="128" size="32" tabindex="6" name="url"/></td> 
				<td><p>Manufacturer's web page for this specific printer.  The maker's home page will 
				already be linked to, so if you don't know where to find a page about this printer, 
				leave this blank. And do not forget the "http://" in the beginning of 
				the address.</p></td>
			</tr> 
			<tr bgcolor="#efefef">
				<td align="right" valign="top">Resolution (X x Y):</td> 
				<td valign="top"><input type="text" size="4" tabindex="7" name="resolution_x"/> x <input type="text" size="4" tabindex="8" name="resolution_y"/></td> 
				<td><p>Maximum X and Y resolution the printer can do.  Available Unix software may not support 
				the finest modes; if so, please say so in the notes.</p></td>
			</tr> 
			<tr bgcolor="#dfdfdf" valign="top">
				<td align="right">Color:</td> 
				<td><label><input type="checkbox" tabindex="9" value="on" name="color"/></label></td> 
				<td><p>Check the box if this printer can do color.  Some printers may not be able to do 
				so without vendor drivers; say so in the notes if so.</p></td>
			</tr> 
			<tr bgcolor="#efefef">
				<td align="right" valign="top">Mechanism:</td> 
				<td valign="top">
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
				<td align="right" valign="top">Language:</td> 
				<td valign="top"><label><input type="checkbox" tabindex="12" value="on" name="postscript"/>PostScript</label> level <input type="text" size="4" tabindex="13" name="postscript_level"/> <br/>
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
			<tr bgcolor="#efefef">
				<td align="right" valign="top">ASCII:</td> 
				<td valign="top"><label><input type="checkbox" tabindex="30" value="on" name="ascii"/></label></td> 
				<td><p>This printer will print text if you just send it plain ascii.  
				Uncheck for printers that <b>only</b> work with Ghostscript and a driver or the like.</p></td>
			</tr> 
			<tr bgcolor="#dfdfdf">
				<td align="right" valign="top">PJL:</td> 
				<td valign="top"><label><input type="checkbox" tabindex="31" value="on" name="pjl"/></label></td> 
				<td><p>Check the box if this printer supports HP's Printer Job Language (PJL).</p></td>
			</tr> 
			<tr bgcolor="#efefef">
				<td align="right" valign="top">Functionality:</td> 
				<td valign="top">
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
			<tr bgcolor="#dfdfdf">
				<td align="right" valign="top">
					Drivers:
					<p><a href="#" onClick="addFormField('{$driverselect|escape}'); return false;">Add</a></p>
				</td> 
				<td  valign="top">
				<input type="hidden" id="id" value="1">
				<div id="divTxt"></div>
				</td>
				<td valign="top"><p>Drivers with which this
					printer is known to work. Comments on
					using the drivers with this
					printer. If you have a PPD file which
					works with this printer and this
					driver, make it available and supply
					the URL here. Choose the driver which
					works best as recommended driver.</p></td>
			</tr>

			<tr bgcolor="#efefef">
				<td align="right" valign="top">Un*x URL:</td> 
				<td valign="top"><input type="text" size="32" tabindex="36" name="contrib_url"/></td> 
				<td><p>Web address for important info about using this printer with Unix-like 
					operating systems/free software; website with special tricks, mini-HOWTO, 
					a user's experience, or whatever else helps to make it going. Do not forget the 
					"http://" in the beginning of the address.</p></td>
			</tr> 
			<tr bgcolor="#dfdfdf">
					<td valign="top" align="right">Notes:</td> 
					<td colspan="2" valign="top"><p><font size="-1" color="#202020">This is HTML that just gets pasted
					into the table; <font color="#000000">watch those &lt;
					signs!</font> Anything big can be
					either linked to with the Un*x
					URL/More Info link, or you can mail us
					the comments to set up as a locally
					served page.</font></p>
					</td>
				</tr> 
				<tr bgcolor="#dfdfdf">
					<td> </td> 
					<td colspan="2"><textarea cols="50" rows="10" tabindex="37" name="notes"/></textarea></td>
				</tr> 
				<tr bgcolor="#efefef">
					<td valign="top" align="right">Auto-detect:</td> 
					<td colspan="2"><p><font size="-1">Auto-detection info for this printer (if you 
					have the possibility to connect your printer in different ways, 
					try all to gather as much auto-detection info as possible), 
					do not fill this in if you are not able to get this data from 
					the actual printer:</font></p><p><font size="-1"><b>Parallel port:</b>
					If you have CUPS running on your machine, simply try to run
					/usr/lib/cups/backend/parallel (run it as root if you do not get sufficient
					output) in a terminal window. Look for an output line corresponding to your
					printer. It should contain a string like this:<br>

					<tt>
					MFG:Hewlett-Packard;CMD:PJL,BIDI-ECP,PCLXL,PCL,PDF,PJL,POSTSCRIPT;MDL:HP 
					Color LaserJet CM3530 MFP;CLS:PRINTER;DES:Hewlett-Packard Color LaserJet CM3530 MFP;
					</tt><br>

					Please insert the complete string into the IEEE-1284 Device ID String
					field, preferably via copy and paste. Take care that all is on one line in the field.
					Put also the elements of the IEEE string into the appropriate fields "MANUFACTURER/MFG",
					"MODEL/MDL", ... (without the semicolon in the end).<br>
					Alternatively, you can also get this information directly from the Linux kernel.
					You find it in the files /proc/parport/#/autoprobe (kernel 2.2.x) or
					/proc/sys/dev/parport/parport#/autoprobe* (kernel 2.4.x and 2.6.x). "#" is the parallel
					port number; ie typically 0, "*" can be nothing or a number. Remove the leading MODEL:,
					MANUFACTURER:, DESCRIPTION:, and COMMAND SET:, and also remove the ending semicolon.
					If you had <tt>MODEL:Stylus Color 670;</tt>, for example, you'd put <tt>Stylus Color 
					670</tt> in the MODEL field here.</font></p>
					<p><font size="-1"><b>USB:</b>
					If you have CUPS on your system, proceed as for the parallel port but run
					/usr/lib/cups/backend/usb. If your printer is configured with HPLIP run the command
					/usr/lib/cups/backend/hp.<br>
					Alternatively download the 
					"<a href="/download/printing/getusbprinterid.pl">getusbprinterid.pl</a>" Perl script,
					make it executable ("chmod a+rx getusbprinterid.pl"), and then run (as "root") 
					"./getusbprinterid.pl /dev/usb/lp0" (or "/dev/usblp0", "/dev/usb/lp1", or whatever
					the USB device file to access your printer is). Proceed as described for the parallel
					port with this output.</font></p><p><font size="-1"><b>Network printer:</b>
					Auto-detection is done via SNMP (Simple Network Management Protocol) or DNS-SD. Run
					/usr/lib/cups/backend/snmp and /usr/lib/cups/backend/dnssd if you are using CUPS. Put
					the first string appearing in double quotes ("...") into the "Description" field.<br>
					Alternatively, you can also use "snmpwalk". Look for a "Description:" field in the
					output. Copy and paste its contents into the "Description" field below.</font></p>
					<p><font size="-1">
					In most cases the IEEE-1284 auto-detection data is the same for USB 
					and parallel port. So usually you should put this data into the "General" 
					section below. If you see any deviations, enter them in the "Parallel Port" and 
					"USB" sections. Leave fields blank if they are identical to the entry in the 
					"General" section, if they are blank, or if they do not exist in your observed 
					auto-detection data.</font></p></td>
				</tr> 
				<tr bgcolor="#efefef">
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
			if(thisform.make.value == "" && thisform.make_new.value == ""){			
				if (validate_required(make,"A Manufacturer is Required!")==false){
					make.focus();
					return false;
				}
			}
			if (validate_required(model,"Model is Required!")==false){
				model.focus();
				return false;
			}
	
		}
		
	}
    </script>
{/literal}

{/if}

{/if}
</div>
{include file="page_rightcommon.tpl" classtype="two_col_col_2"}
{include file="page_conclusion.tpl"}
