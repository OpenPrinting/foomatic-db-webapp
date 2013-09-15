{include file="page_masthead.tpl"}

<div id="two_col_col_1">
  {include file="page_breadcrumbs.tpl"}
	
  <h1>Edit driver</h1>
  
  <form action="{$BASEURL}driver/{$driver.id}/edit" method="post">
    <fieldset>
      <input type="submit" name="submit" value="Save Changes" />
      <input type="submit" name="approve" value="Approve" />
      <input type="submit" name="reject" value="Reject" />
      <a href="{$BASEURL}admin/queue">Cancel</a>
    </fieldset>
    
    <fieldset>
      <legend>Driver</legend>
      <table>
        <tr>
          <td><label for="comments">Comments</label></td>
          <td><textarea name="comment" id="comments" rows="10" cols='30'>{$driver.comment}</textarea></td>
        </tr>
        <tr>
          <td><label for="datepicker">Release Date:</label></td> 
          <td><input type="text" size="10" id="datepicker" name="release_date" value="{$driver.showentry}" /></td>
        </tr>
        <tr>
          <td><label for="driver-edit-name">Driver name:</label></td>
          <td><input type="text" id="driver-edit-name" name="driver_name" value="{$driver.name}" disabled="disabled" /></td>
        </tr>
        <tr>
          <td><label for="download_url">Driver URL:</label></td>
          <td><input type="text" name="download_url" id="download_url" value="{$driver.url}" /></td>
        </tr>
        <tr>
    			<td>Obsolete/Replacement </td>
    			<td>
    				<select name="obsolete" id="obsolete">
    					<option value="">Not Obsolete</option>
      				{foreach from=$drivers item=d}
      					<option value="{$d.id}"{if $d.id == $driver.obsolete} selected="selected"{/if}>{$d.name|escape}</option>
      				{/foreach}
    				</select>
          </td>
        </tr>
        <tr>
          <td><label for="discription">Short description:</label></td>
          <td><input type="text" name="description" id="discription" value="{$driver.shortdescription}" /></td>
        </tr>
        <tr>
          <td><label for="">Supplier</label></td>
          <td><input type="text" name="supplier" id="supplier" value="{$driver.supplier}" /></td>
        </tr>
        <tr>
          <td><label for="manufacturersupplied">Manufacturer supplied?</label></td>
          <td><input type="checkbox" name="manufacturersupplied[]" id="manufacturersupplied" value="1"{if $driver.manufacturersupplied} checked="checked"{/if} /></td>
        </tr>
        <tr>
          <td><label for="license">Lisence</label></td>
          <td>
            <select name="license" id="license">
              <option name="">--select a license type--</option>
              <option name="GPLv1"{if $driver.license == 'GPLv1'} selected="selected"{/if}>GPLv1</option>
              <option name="GPLv2"{if $driver.license == 'GPLv2'} selected="selected"{/if}>GPLv2</option>
              <option name="GPLv3"{if $driver.license == 'GPLv3'} selected="selected"{/if}>GPLv3</option>
              <option name="Commercial"{if $driver.license == 'Commercial'} selected="selected"{/if}>Commercial</option>
              <option name="BSD"{if $driver.license == 'BSD'} selected="selected"{/if}>BSD</option>
              <option name="MPL"{if $driver.license == 'MPL'} selected="selected"{/if}>Mozilla Public License</option>
              {if $driver.license != 'GPLv1' and $driver.license != 'GPLv2' and $driver.license != 'Commercial' and $driver.license != 'GPLv3' and $driver.license != 'BSD' and $driver.license != 'MPL'}
                <option name="{$driver.license}" selected="selected">{$driver.license}</option>
              {/if}
            </select>
          </td>
        </tr>
        <tr>
          <td><label for="nonfreesoftware">Non-free software?</label></td>
          <td><input type="checkbox" name="nonfreesoftware" id="nonfreesoftware" value="1"{if $driver.nonfreesoftware} checked="checked"{/if} /></td>
        </tr>
        <tr>
          <td><label for="patents">Patent issues?</label></td>
          <td><input type="checkbox" name="patents" id="patents" value="1"{if $driver.patents} checked="checked"{/if} /></td>
        </tr>
        <tr>
          <td><label for="licensetext">License Text:</label></td>
          <td><textarea name="licensetext" id="licensetext" rows="8" cols="28">{$driver.licensetext}</textarea></td>
        </tr>
        <tr>
          <td><label for="licenselink">License link</label></td>
          <td><input type="text" name="licenselink" id="licenselink" value="{$driver.lisencelink}" /></td>
      </table>
    </fieldset>
    
    <fieldset>
      <legend>Support Contact</legend>
      <table>
        <tr>
          <td><label for="supportdescription">Support Description:</label></td>
          <td><textarea name="supportdescription" id="supportdescription" rows="1" cols="26">{$driver.support_description}</textarea></td>
        </tr>
        <tr>
          <td><label for="supporturl">Support URL:</label></td>
          <td><input type="text" name="supporturl" id="supporturl" value="{$driver.support_url}" /></td>
        </tr>
        <tr>
          <td><label for="supportlevel_1">Support Level:</label></td>
          <td>
  					<input type="radio" name="supportlevel" id="supportlevel_1" value="voluntary"{if $driver.support_level == 'voluntary'} checked="checked"{/if}> Voluntary
  					<br>
  					<input type="radio" name="supportlevel" id="supportlevel_2" value="commercial"{if $driver.support_level == 'commercial'} checked="checked"{/if}> Commercial
          </td>
        </tr>
      </table>
    </fieldset>
    
    <fieldset>
      <legend>General</legend>
      <table>
        <tr>
          <td><label for="">Driver Type:</label></td>
          <td>
  					<input type="radio" name="execution" id="execution-01" value="ghostscript"{if $driver.execution == 'ghostscript'} checked="checked"{/if} /> Ghostscript Built-in
  					<br>
  					<input type="radio" name="execution" id="execution-02" value="uniprint"{if $driver.execution == 'uniprint'} checked="checked"{/if} /> Ghostscript Uniprint
  					<br>
  					<input type="radio" name="execution" id="execution-03" value="filter"{if $driver.execution == 'filter'} checked="checked"{/if} /> Filter
  					<br>
  					<input type="radio" name="execution" id="execution-04" value="cups"{if $driver.execution == 'cups'} checked="checked"{/if} /> CUPS Raster
  					<br>
  					<input type="radio" name="execution" id="execution-05" value="ijs"{if $driver.execution == 'ijs'} checked="checked"{/if} /> IJS Plugin
  					<br>
  					<input type="radio" name="execution" id="execution-06" value="opvp"{if $driver.execution == 'opvp'} checked="checked"{/if} /> OpenPrinting Vector
  					<br>
  					<input type="radio" name="execution" id="execution-07" value="postscript"{if $driver.execution == 'postscript'} checked="checked"{/if} /> Postscript
          </td>
        </tr>
        <tr>
          <td><label for="max_res_x">Maximum Resolution:</label></td>
          <td>
            <input type="text" name="max_res_x" id="max_res_x" size="5" value="{$driver.max_res_x}" /> x 
            <input type="text" name="max_res_y" id="max_res_y" size="5" value="{$driver.max_res_y}" />
          </td>
        </tr>
        <tr>
          <td><label>Color:</label></td>
          <td>
  					<input type="checkbox" name="grayscale" id="color-02" value="1"{if $driver.color == '0'} checked="checked"{/if} /> Greyscale/Monochrome<br />
  					<input type="checkbox" name="color" id="color-01" value="1"{if $driver.color == '1'} checked="checked"{/if}> Color
          </td>
        </tr>
        <tr>
          <td><label>Suitability for</label></td>
          <td>
    				<table>
    					<tr>
    						<td>Text</td>
    						<td>{html_options name=text options=$scaleOption selected=$driver.text}</td>
    					</tr>
    					<tr>
    						<td>Lineart</td> 
    						<td>{html_options name=lineart options=$scaleOption selected=$driver.lineart}</td>
    					</tr>
    					<tr>
    						<td>Graphics</td> 
    						<td>{html_options name=graphics options=$scaleOption selected=$driver.graphics}</td>
    					</tr>
    					<tr>
    						<td>Photo</td> 
    						<td>{html_options name=photo options=$scaleOption selected=$driver.photo}</td>
    					</tr>
    					<tr>
    						<td>Load</td> 
    						<td>{html_options name=load_time options=$scaleOption selected=$driver.load_time}</td>
    					</tr>
    					<tr>
    						<td>Speed</td> 
    						<td>{html_options name=speed options=$scaleOption selected=$driver.speed}</td>
    					</tr>
    				</table>
          </td>
        </tr>
      </table>
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