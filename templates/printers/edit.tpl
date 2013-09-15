{include file="page_masthead.tpl"}

<div id="two_col_col_1">
  {include file="page_breadcrumbs.tpl"}
	
  <h1>Edit Printer</h1>
  
  <form action="{$BASEURL}printer/{$printer.make|replace:" ":"+"}/{$printer.id|replace:" ":"+"}/edit" method="post">
    <fieldset>
      <input type="submit" name="submit" value="Save Changes" />
      <input type="submit" name="approve" value="Approve" />
      <input type="submit" name="reject" value="Reject" />
      <a href="{$BASEURL}admin/queue">Cancel</a>
    </fieldset>
    <fieldset>
      <legend>General</legend>
      
      <table>
        <tr>
          <td>Status:</td>
          <td>
            {if $entry.approved != ""}
              Approved
            {elseif $entry.rejected != ""}
              Rejected
            {else}
              Submitted by {$printer.contributor} ({$printer.submitted})
            {/if}
          </td>
        </tr>
        <tr>
          <td><label for="comments">Comments:</label></td> 
          <td colspan="2"><textarea id="comments" name="comments" cols="55" rows="8" />{$printer.comment}</textarea></td>
        </tr>
        <tr>
          <td><label for="datepicker">Release Date:</label></td> 
          <td><input type="text" size="10" id="datepicker" name="release_date" value="{$printer.showentry}" /> </td>
        </tr>
        <tr>
          <td><label for="driver-edit-manufacturer">Manufacturer</label></td> 
          <td valign="top">
            <select name="make" id="driver-edit-manufacturer" disabled="disabled">
              <option value="" selected="selected">--select manufacturer--</option>
              {foreach from=$makes item=make}
                <option value="{$make.make|escape}"{if $make == $printer.make} selected="selected"{/if}>{$make.make|escape}</option>
              {foreachelse}
                <option value="0">None Available</option>
              {/foreach}
            </select>
          </td>
        </tr>
        <tr>
          <td><label for="driver-edit-model">Model</label></td>
          <td><input type="text" size="32" name="model" id="driver-edit-model" value="{$printer.model}" disabled="disabled" /></td>
        </tr>
        <tr>
          <td><label for="driver-edit-url">URL:</label></td>
          <td><input type="text" size="32" name="url" id="driver-edit-url" value="{$printer.url}" /></td>
        </tr>
        <tr>
          <td><label for="driver-edit-resolution">Resolution (X x Y):</label></td>
          <td><input id="driver-edit-resolution" type="text" size="4" name="resolution_x" value="{$printer.res_x}" /> x <input type="text" size="4" name="resolution_y" value="{$printer.res_y}" /></td>
        </tr>
        <tr>
          <td><label for="driver-edit-color">Color:</label></td>
          <td><input type="checkbox" value="on" name="color"{if $printer.color} checked="checked"{/if} /></td>
        </tr>
        <tr>
          <td><label for="driver-edit-mechanism">Mechanism:</label></td>
          <td>
            <select id="driver-edit-mechanism" name="type">
              <option value=""{if $printer.mechanism == ''} selected="selected"{/if}>Unknown/Other</option>
              <option value="dotmatrix"{if $printer.mechanism == 'dotmatrix'} selected="selected"{/if}>Dot Matrix</option>
              <option value="impact"{if $printer.mechanism == 'impact'} selected="selected"{/if}>Impact</option>
              <option value="inkjet"{if $printer.mechanism == 'inkjet'} selected="selected"{/if}>Inkjet</option>
              <option value="laser"{if $printer.mechanism == 'laser'} selected="selected"{/if}>Laser</option>
              <option value="led"{if $printer.mechanism == 'led'} selected="selected"{/if}>LED</option>
              <option value="sublimation"{if $printer.mechanism == 'sublimation'} selected="selected"{/if}>Dye Sublimation</option>
              <option value="transfer"{if $printer.mechanism == 'transfer'} selected="selected"{/if}>Thermal Transfer</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>Language:</td>
          <td>
            <label><input type="checkbox" value="on" name="postscript"{if $printer.postscript} checked="checked" {/if}/>PostScript</label> level <input type="text" size="4" tabindex="13" name="postscript_level" value="{$printer.postscript_level}" /> <br/>
            <label><input type="checkbox" value="on" name="pdf"{if $printer.escp2} checked="checked" {/if}/>PDF</label> level <input type="text" size="4" tabindex="16" name="pdf_level" value="{$printer.escp2_level}" /> <br/>  
            <label><input type="checkbox" value="on" name="lips"{if $printer.escp} checked="checked" {/if}/>LIPS</label> level <input type="text" size="4" tabindex="18" name="lips_level" value="{$printer.escp_level}" /> <br/>
            <label><input type="checkbox" value="on" name="pcl"{if $printer.pcl} checked="checked" {/if}/>PCL</label> level <input type="text" size="4" tabindex="20" name="pcl_level" value="{$printer.pcl_level}" /> <br/> 
            <label><input type="checkbox" value="on" name="escp"{if $printer.lips} checked="checked" {/if}/>ESC/P</label> level <input type="text" size="4" tabindex="22" name="escp_level" value="{$printer.lips_level}" /> <br/> 
            <label><input type="checkbox" value="on" name="escp2"{if $printer.pdf} checked="checked" {/if}/>ESC/P 2</label> level <input type="text" size="4" tabindex="24" name="escp2_level" value="{$printer.pdf_level}" /> <br/> 
            <label><input type="checkbox" value="on" name="hpgl2"{if $printer.hpgl2} checked="checked" {/if}/>HP-GL/2</label> level <input type="text" size="4" tabindex="26" name="hpgl2_level" value="{$printer.hpgl2_level}" /> <br/> 
            <label><input type="checkbox" value="on" name="tiff"{if $printer.tiff} checked="checked" {/if}/>TIFF</label> level <input type="text" size="4" tabindex="28" name="tiff_level" value="{$printer.tiff_level}" /> <br/> 
            <label><input type="checkbox" value="on" name="proprietary"{if $printer.proprietary} checked="checked" {/if}/>Proprietary</label>
          </td>
        </tr>
        <tr>
          <td><label for="driver-edit-ascii">ASCII:</label></td>
          <td><input type="checkbox" id="driver-edit-ascii" value="on" name="ascii"{if $printer.text} checked="checked"{/if} /></td>
        </tr>
        <tr>
          <td><label for="driver-edit-pjl">PJL:</label></td>
          <td><input type="checkbox" id="driver-edit-pjl" value="on" name="pjl"{if $printer.pjl} checked="checked"{/if} /></td>
        </tr>
        <tr>
          <td><label for="driver-edit-functionality">Functionality:</label></td>
          <td>
            <select tabindex="32" name="func">
              <option value="A"{if $printer.functionality == 'A'} selected="selected"{/if}>Perfectly</option>
              <option value="B"{if $printer.functionality == 'B'} selected="selected"{/if}>Mostly</option>
              <option value="D"{if $printer.functionality == 'D'} selected="selected"{/if}>Partially</option>
              <option value="F"{if $printer.functionality == 'F'} selected="selected"{/if}>Paperweight</option>
            </select>
          </td>
        </tr>
        <tr>
          <td><label for="driver-edit-unix-url">Un*x URL:</label></td>
          <td><input type="text" size="32" id="driver-edit-unix-url" name="contrib_url" value="{$printer.contrib_url}" /></td>
        </tr>
        <tr>
          <td><label for="driver-edit-notes">Notes:</label></td>
          <td><textarea cols="50" rows="10" id="driver-edit-notes" name="notes" value="{$printer.comments}" /></textarea></td>
        </tr>
        <!-- TODO: add default driver -->
      </table>
    </fieldset>
    
    <fieldset>
      <legend>Auto-detect</legend>
      <dl><dt><b>General (Parallel and USB)</b><br/>IEEE-1284 Device ID String</dt> 
      <dd><input type="text" size="50" tabindex="38" name="general_ieee"/></dd> 
      <dt>MANUFACTURER/MFG</dt> <dd><input type="text" size="32" tabindex="39" name="general_mfg" value="{$printer.general_manufacturer}" /></dd> 
      <dt>MODEL/MDL</dt> <dd><input type="text" size="32" tabindex="40" name="general_mdl" value="{$printer.general_model}" /></dd> 
      <dt>DESCRIPTION/DES</dt> <dd><input type="text" size="32" tabindex="41" name="general_des" value="{$printer.general_description}" /></dd> 
      <dt>COMMAND SET/CMD</dt> <dd><input type="text" size="32" tabindex="42" name="general_cmd" value="{$printer.general_commandset}" /></dd> <dt>
      <br/><b>Parallel Port</b>
      <br/>IEEE-1284 Device ID String</dt> 
      <dd><input type="text" size="50" tabindex="43" name="par_ieee" value="{$printer.par_ieee}" /></dd> 
      <dt>MANUFACTURER</dt> <dd><input type="text" size="32" tabindex="44" name="par_mfg" value="{$printer.parallel_manufacturer}" /></dd> 
      <dt>MODEL</dt> <dd><input type="text" size="32" tabindex="45" name="par_mdl" value="{$printer.parallel_model}" /></dd> 
      <dt>DESCRIPTION</dt> <dd><input type="text" size="32" tabindex="46" name="par_des" value="{$printer.parallel_description}" /></dd> 
      <dt>COMMAND SET</dt> <dd><input type="text" size="32" tabindex="47" name="par_cmd" value="{$printer.parallel_commandset}" /></dd> 
      <dt><br/><b>USB</b><br/>IEEE-1284 Device ID String</dt> 
      <dd><input type="text" size="50" tabindex="48" name="usb_ieee" value="{$printer.usb_ieee}" /></dd> 
      <dt>MANUFACTURER/MFG</dt> <dd><input type="text" size="32" tabindex="49" name="usb_mfg" value="{$printer.usb_manufacturer}" /></dd> 
      <dt>MODEL/MDL</dt> <dd><input type="text" size="32" tabindex="50" name="usb_mdl" value="{$printer.usb_model}" /></dd> 
      <dt>DESCRIPTION/DES</dt> <dd><input type="text" size="32" tabindex="51" name="usb_des" value="{$printer.usb_description}" /></dd> 
      <dt>COMMAND SET/CMD</dt> <dd><input type="text" size="32" tabindex="52" name="usb_cmd" value="{$printer.usb_commandset}" /></dd> 
      <dt><br/><b>Network Printer (SNMP)</b><br/>Description</dt> 
      <dd><input type="text" size="32" tabindex="53" name="snmp_des" value="{$printer.snmp_description}" /></dd></dl>
    </fieldset>
    
    <fieldset>
      <input type="submit" name="submit" value="Save Changes" />
      <input type="submit" name="approve" value="Approve" />
      <input type="submit" name="reject" value="Reject" />
      <a href="{$BASEURL}admin/queue">Cancel</a>
    </fieldset>
  </form>
</div>
{include file="page_rightcommon.tpl" classtype="two_col_col_2"}
{include file="page_conclusion.tpl"}