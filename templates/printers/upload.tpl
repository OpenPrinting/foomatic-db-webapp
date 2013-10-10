{include file="page_masthead.tpl"}

<div id="two_col_col_1">
	{include file="page_breadcrumbs.tpl"}
  
  <h1 class="upload">Add a New Printer</h1>

  <p class="upload-description">
    There are a fair number of things kept in this database about each printer. Unfortunately, this makes for a somewhat long form.
  </p>

  <p class="upload-description">
    Please take a few minutes and fill out anything you can. Note that your printer entry is only useful if the reader can see how he gets
    the printer to work (driver, where to get the driver if it is not yet listed at OpenPrinting, to which printer is yours compatible, special tricks, …)
    or if he gets the message that this model definitely does not work.
  </p>
  
  {*<p>Note that if you make a mistake you can edit everything at any time after submitting. So do not create another printer entry if you are not content. Simply correct your entry where needed.</p>*}
  
  {if $isLoggedIn == "1" }
    {if isset($msg) and $msg=="success"}
      <div class="success">
      	The printer specifications have been uploaded to the system!
      </div>	
      <a href="/printers/upload">Add another Printer</a>
    {elseif isset($msg) and $msg=="error"}
      <div class="error">
      	{if $type=='makemodel'}
        	The manufacturer and model fields must be filled!
      	{/if}
      	{if $type=='exists'}
        	Printer already exists in the database!
      	{/if}
      </div>	
      <a href="/printers/upload">Add a New Printer</a>
    {else}
    	<div class="upload-header">
    		<span class="step active" data-step="info">Printer Information</span>
    		<span class="step" data-step="auto">Auto-detect</span>
    		<span class="step last" data-step="add">Add</span>
    	</div>
      
      <form action="/printers/upload" method="post" onsubmit='return validate_form(this)'>
      	{if $isTrusted || $isAdmin }
      		<input type="hidden" value="1" name="noqueue" />
      	{/if}
        
      	<div class="step-fieldset" id="step-info">
        	<h2 class="upload">Printer Information</h2>
        	<table class="upload-form">
        		<tr>
        			<td class="upload-form-label">Upload Comment</td>
        			<td><textarea id="comments" name="comments" cols="55" rows="8" /></textarea></td>
        		</tr>
        		<tr class="desc">
        			<td></td>
        			<td>
                Comment about this upload. If you need someone to approve 
          			your entry (you are not Trusted Uploader or Administrator) you can put some message for the 
          			approver here. This field has the function of a whiteboard for the approval process then. 
          			The approver will enter here what you need to correct in order to get approval, or the reasons 
          			why he rejects your contribution. You can answer to the approver here then ... 
          			If your entry gets automatically approved, please enter a comment here which should 
          			appear in the ChangeLog files of the foomatic-db and foomatic-db-nonfree packages. 
          			The comment does not need to contain your name, the date, or the list of added or 
          			changed files. All this information is added to the overview table for the approvers 
          			and to the ChangeLog files automatically.
              </td>
        		</tr>
        		<tr>
        			<td class="upload-form-label">Release Date</td> 
        			<td><input type="text" size="10" tabindex="1" id="datepicker" name="release_date" /> </td>
            </tr>
            <tr class="desc">
              <td></td>
        			<td>Future release date</td>
        		</tr>
            <tr>
              <td class="upload-form-label">Manufacturer <font color="red"><b>*</b></font></td>
              <td>
        				<select tabindex="2" name="make">
        					<option value="" selected="selected">--select manufacturer--</option>
        					{foreach from=$makes item=make}
        						<option value="{$make|escape}">{$make|escape}</option>
        					{foreachelse}
        						<option value="0">None Available</option>
        					{/foreach}

        					</select>  
        					OR  
        					<input type="text" size="16" tabindex="4" name="make_new" />
              </td>
            </tr>
            <tr class="desc">
              <td></td>
              <td>
                Manufacturer name for the printer. If there
                        	                         are already printers of this manufacturer,
                        	                         choose the manufacturer name from the menu
                        	                         and leave the input field blank.<br/>
                        	                         DO NOT write the model name into the input
                        	                         field, the model name goes into the field below. 
                        	                         Use the input field for manufacturers which are
                        	                         not listed yet.
              </td>
            </tr>
            <tr>
              <td class="upload-form-label">Model <font color="red"><b>*</b></font></td>
              <td>
                <input type="text" size="32" tabindex="5" name="model" />
              </td>
            </tr>
            <tr class="desc">
              <td></td>
              <td>
                Model name for the printer.  Please try
                        				 to follow the conventions used for
                        				 other printers in the same
                        				 family. DO NOT repeat the manufacturer's name
                        	             in this field.
              </td>
            </tr>
            <tr>
              <td class="upload-form-label">URL</td>
              <td>
                <input type="text" size="32" tabindex="6" name="url" />
              </td>
            </tr>
            <tr class="desc">
              <td></td>
              <td>
                Manufacturer's web page for this specific printer.  The maker's home page will 
                        				already be linked to, so if you don't know where to find a page about this printer, 
                        				leave this blank. And do not forget the "http://" in the beginning of 
                        				the address.
              </td>
            </tr>
            <tr>
              <td class="upload-form-label">Resolution (X x Y)</td>
              <td>
                <input type="text" size="4" tabindex="7" name="resolution_x" /> x <input type="text" size="4" tabindex="8" name="resolution_y" />
              </td>
            </tr>
            <tr class="desc">
              <td></td>
              <td>
                Maximum X and Y resolution the printer can do.  Available Unix software may not support 
                        				the finest modes; if so, please say so in the notes.
              </td>
            </tr>
            <tr>
              <td class="upload-form-label">Color</td>
              <td>
                <input type="checkbox" tabindex="9" value="on" name="color" />
              </td>
            </tr>
            <tr class="desc">
              <td></td>
              <td>
                Check the box if this printer can do color.  Some printers may not be able to do 
                        				so without vendor drivers; say so in the notes if so.
              </td>
            </tr>
            <tr>
              <td class="upload-form-label">Mechanism</td>
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
            </tr>
            <tr class="desc">
              <td></td>
              <td>
                What sort of printing mechanism does this printer use?
              </td>
            </tr>
            <tr>
              <td class="upload-form-label">Language</td>
              <td>
                <label><input type="checkbox" tabindex="12" value="on" name="postscript" />PostScript</label> level <input type="text" size="4" tabindex="13" name="postscript_level" /> <br/>
                        				<label><input type="checkbox" tabindex="15" value="on" name="pdf" />PDF</label> level <input type="text" size="4" tabindex="16" name="pdf_level" /> <br/>  
                        				<label><input type="checkbox" tabindex="17" value="on" name="lips" />LIPS</label> level <input type="text" size="4" tabindex="18" name="lips_level" /> <br/>
                        				<label><input type="checkbox" tabindex="19" value="on" name="pcl" />PCL</label> level <input type="text" size="4" tabindex="20" name="pcl_level" /> <br/> 
                        				<label><input type="checkbox" tabindex="21" value="on" name="escp" />ESC/P</label> level <input type="text" size="4" tabindex="22" name="escp_level" /> <br/> 
                        				<label><input type="checkbox" tabindex="23" value="on" name="escp2" />ESC/P 2</label> level <input type="text" size="4" tabindex="24" name="escp2_level" /> <br/> 
                        				<label><input type="checkbox" tabindex="25" value="on" name="hpgl2" />HP-GL/2</label> level <input type="text" size="4" tabindex="26" name="hpgl2_level" /> <br/> 
                        				<label><input type="checkbox" tabindex="27" value="on" name="tiff" />TIFF</label> level <input type="text" size="4" tabindex="28" name="tiff_level" /> <br/> 
                        				<label><input type="checkbox" tabindex="29" value="on" name="proprietary" />Proprietary</label>
              </td>
            </tr>
            <tr class="desc">
              <td></td>
              <td>
                The printer control language spoken by this printer, and level or version if known.  Mail us and add a remark in the 
                        				"Notes:" field if we've forgotten any languages.
              </td>
            </tr>
            <tr>
              <td class="upload-form-label">ASCII</td>
              <td>
                <input type="checkbox" tabindex="30" value="on" name="ascii" />
              </td>
            </tr>
            <tr class="desc">
              <td></td>
              <td>
                This printer will print text if you just send it plain ascii.  
                        				Uncheck for printers that <strong>only</strong> work with Ghostscript and a driver or the like.
              </td>
            </tr>
            <tr>
              <td class="upload-form-label">PJL</td>
              <td>
                <input type="checkbox" tabindex="31" value="on" name="pjl" />
              </td>
            </tr>
            <tr class="desc">
              <td></td>
              <td>
                Check the box if this printer supports HP's Printer Job Language (PJL).
              </td>
            </tr>
            <tr>
              <td class="upload-form-label">Functionality</td>
              <td>
      					<select tabindex="32" name="func">
      						<option value="A">Perfectly</option>
      						<option value="B">Mostly</option>
      						<option value="D">Partially</option>
      						<option value="F" selected="selected">Paperweight</option>
      					</select>
              </td>
            </tr>
            <tr class="desc">
              <td></td>
              <td>
                How well does this printer work using Un*x software (ie ghostscript).  Put details 
                        				into the "Notes:" field.  Mostly means it prints, but minor things are missing. Partially 
                        				means it prints, but major things are missing.<br/>If you choose a non-Paperweight rating, 
                        				choose/enter a driver and/or enter in the "Notes:" field how you made this printer working.
              </td>
            </tr>
            <tr>
              <td class="upload-form-label">Drivers</td>
              <td>
                <a href="#" onClick="addFormField('{$driverselect|escape}'); return false;">Add driver</a>
                
                <input type="hidden" id="id" value="1">        				<div id="divTxt"></div>
              </td>
            </tr>
            <tr class="desc">
              <td></td>
              <td>
                Drivers with which this
                        					printer is known to work. Comments on
                        					using the drivers with this
                        					printer. If you have a PPD file which
                        					works with this printer and this
                        					driver, make it available and supply
                        					the URL here. Choose the driver which
                        					works best as recommended driver.
              </td>
            </tr>
            <tr>
              <td class="upload-form-label">Un*x URL</td>
              <td>
                <input type="text" size="32" tabindex="36" name="contrib_url" />
              </td>
            </tr>
            <tr class="desc">
              <td></td>
              <td>
                Web address for important info about using this printer with Unix-like 
                        					operating systems/free software; website with special tricks, mini-HOWTO, 
                        					a user's experience, or whatever else helps to make it going. Do not forget the 
                        					"http://" in the beginning of the address.
              </td>
            </tr>
            <tr>
              <td class="upload-form-label">Notes</td>
              <td>
                <textarea cols="50" rows="10" tabindex="37" name="notes" /></textarea>
              </td>
            </tr>
            <tr class="desc">
              <td></td>
              <td>
                This is HTML that just gets pasted
                        					into the table; watch those &lt;
                        					signs! Anything big can be
                        					either linked to with the Un*x
                        					URL/More Info link, or you can mail us
                        					the comments to set up as a locally
                        					served page.
              </td>
            </tr>
          </table>
        </div>
        
      	<div class="step-fieldset" id="step-auto">
        	<h2 class="upload">Auto-detect</h2>
          
        	<p class="upload-description">
            Auto-detection info for this printer (if you 
                  					have the possibility to connect your printer in different ways, 
                  					try all to gather as much auto-detection info as possible), 
                  					do not fill this in if you are not able to get this data from 
                  					the actual printer:
          </p>
          
          <h2 class="upload-description">Parallel port</h2>
          
          <p class="upload-description">
  					If you have CUPS running on your machine, simply try to run
  					/usr/lib/cups/backend/parallel (run it as root if you do not get sufficient
  					output) in a terminal window. Look for an output line corresponding to your
  					printer. It should contain a string like this:
          </p>
          <p class="upload-description">
  					<tt>
  					MFG:Hewlett-Packard;CMD:PJL,BIDI-ECP,PCLXL,PCL,PDF,PJL,POSTSCRIPT;MDL:HP 
  					Color LaserJet CM3530 MFP;CLS:PRINTER;DES:Hewlett-Packard Color LaserJet CM3530 MFP;
            </tt>
          </p>
          
          <p class="upload-description">
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
  					670</tt> in the MODEL field here.
          </p>
          
          <h2 class="upload-description">USB</h2>
          
          <p class="upload-description">
  					If you have CUPS on your system, proceed as for the parallel port but run
  					/usr/lib/cups/backend/usb. If your printer is configured with HPLIP run the command
  					/usr/lib/cups/backend/hp.<br>
  					Alternatively download the 
  					"<a href="/download/printing/getusbprinterid.pl">getusbprinterid.pl</a>" Perl script,
  					make it executable ("chmod a+rx getusbprinterid.pl"), and then run (as "root") 
  					"./getusbprinterid.pl /dev/usb/lp0" (or "/dev/usblp0", "/dev/usb/lp1", or whatever
  					the USB device file to access your printer is). Proceed as described for the parallel
  					port with this output.
          </p>
          
          <h2 class="upload-description">Network printer</h2>
          
          <p class="upload-description">
  					Auto-detection is done via SNMP (Simple Network Management Protocol) or DNS-SD. Run
  					/usr/lib/cups/backend/snmp and /usr/lib/cups/backend/dnssd if you are using CUPS. Put
  					the first string appearing in double quotes ("...") into the "Description" field.<br>
  					Alternatively, you can also use "snmpwalk". Look for a "Description:" field in the
  					output. Copy and paste its contents into the "Description" field below.
          </p>
          
          <p class="upload-description">
  					In most cases the IEEE-1284 auto-detection data is the same for USB 
  					and parallel port. So usually you should put this data into the "General" 
  					section below. If you see any deviations, enter them in the "Parallel Port" and 
  					"USB" sections. Leave fields blank if they are identical to the entry in the 
  					"General" section, if they are blank, or if they do not exist in your observed 
  					auto-detection data.
          </p>
          
        	<table class="upload-form">
            <tr>
              <td class="upload-form-label">Auto-detect</td>
              <td>
    						<dl><dt><b>General (Parallel and USB)</b><br/>IEEE-1284 Device ID String</dt> 
    						<dd><input type="text" size="50" tabindex="38" name="general_ieee" /></dd> 
    						<dt>MANUFACTURER/MFG</dt> <dd><input type="text" size="32" tabindex="39" name="general_mfg" /></dd> 
    						<dt>MODEL/MDL</dt> <dd><input type="text" size="32" tabindex="40" name="general_mdl" /></dd> 
    						<dt>DESCRIPTION/DES</dt> <dd><input type="text" size="32" tabindex="41" name="general_des" /></dd> 
    						<dt>COMMAND SET/CMD</dt> <dd><input type="text" size="32" tabindex="42" name="general_cmd" /></dd> <dt>
    							<br/><b>Parallel Port</b>
    							<br/>IEEE-1284 Device ID String</dt> 
    							<dd><input type="text" size="50" tabindex="43" name="par_ieee" /></dd> 
    						<dt>MANUFACTURER</dt> <dd><input type="text" size="32" tabindex="44" name="par_mfg" /></dd> 
    						<dt>MODEL</dt> <dd><input type="text" size="32" tabindex="45" name="par_mdl" /></dd> 
    						<dt>DESCRIPTION</dt> <dd><input type="text" size="32" tabindex="46" name="par_des" /></dd> 
    						<dt>COMMAND SET</dt> <dd><input type="text" size="32" tabindex="47" name="par_cmd" /></dd> 
    						<dt><br/><b>USB</b><br/>IEEE-1284 Device ID String</dt> 
    						<dd><input type="text" size="50" tabindex="48" name="usb_ieee" /></dd> 
    						<dt>MANUFACTURER/MFG</dt> <dd><input type="text" size="32" tabindex="49" name="usb_mfg" /></dd> 
    						<dt>MODEL/MDL</dt> <dd><input type="text" size="32" tabindex="50" name="usb_mdl" /></dd> 
    						<dt>DESCRIPTION/DES</dt> <dd><input type="text" size="32" tabindex="51" name="usb_des" /></dd> 
    						<dt>COMMAND SET/CMD</dt> <dd><input type="text" size="32" tabindex="52" name="usb_cmd" /></dd> 
    						<dt><br/><b>Network Printer (SNMP)</b><br/>Description</dt> 
    						<dd><input type="text" size="32" tabindex="53" name="snmp_des" /></dd></dl>
              </td>
            </tr>
          </table>
        </div>
        
      	<div class="step-fieldset" id="step-add">
        	<h2 class="upload">Add printer</h2>
        	<table class="upload-form">
            <tr>
              <td colspan="2">
                <input type="submit" name="submit" value="Add Printer"> <a href="/account/myuploads">Cancel</a>
              </td>
            </tr>
          </table>
        </div>
      
      	<div class="upload-nav">
      		<input type="submit" name="submit" value="Previous" id="upload-previous" disabled>
      		<input type="submit" name="submit" value="Next" id="upload-next" disabled>
      	</div>
      </form>
    {/if}
  {/if}

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
<script src="{$BASEURL}javascript/upload.js" type="text/javascript"></script>

</div>
{include file="page_rightcommon.tpl" classtype="two_col_col_2"}
{include file="page_conclusion.tpl"}
