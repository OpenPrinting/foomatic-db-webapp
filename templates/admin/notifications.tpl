{include file="page_masthead.tpl"}

<div id="two_col_col_1">
	{include file="page_breadcrumbs.tpl"}

	<h1>Notifications</h1>
  
  <p>Select events you want to receive a notification for.</p>
  
	<form method="post" action="">
    <fieldset>
      <table cellpadding="4">
        <tr>
          <td><label for="notification-email">Email</label></td>
          <td><input type="text" name="email" id="notification-email" value="{$settings.email}" /></td>
          <td><small>Notifications are deliviered to this email adress.</small></td>
      </table>
    </fieldset>
    
		<fieldset>
			<legend>Receive notifications if…</legend>
      
      <table cellpadding="4">
    		<tr>
    			<td><input type="checkbox" name="printer_queue" id="printer-queue" value="1"  {if $settings.printer_queue}checked="checked" {/if}/></td>
    			<td><label for="printer-queue">…somebody uploads a new printer to queue.</label></td>
    		</tr>
    		<tr>
    			<td><input type="checkbox" name="printer_noqueue" id="printer-noqueue" value="1"  {if $settings.printer_noqueue}checked="checked" {/if}/></td>
    			<td><label for="printer-noqueue">…a trusted uploader uploads a new printer to queue.</label></td>
    		</tr>
    		<tr>
    			<td><input type="checkbox" name="driver_queue" id="driver-queue" value="1" {if $settings.driver_queue}checked="checked" {/if}/></td>
    			<td><label for="driver-queue">…somebody uploads a new driver to queue.</label></td>
    		</tr>
    		<tr>
    			<td><input type="checkbox" name="driver_noqueue" id="driver-noqueue" value="1"  {if $settings.driver_noqueue}checked="checked" {/if}/></td>
    			<td><label for="driver-noqueue">…a trusted uploader uploads a new driver to the site.</label></td>
    		</tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="submit" value="Save settings" /></td>
        </tr>
      </table>
		</fieldset>
	</form>
  
</div>
{include file="page_rightcommon.tpl" classtype="two_col_col_2"}
{include file="page_conclusion.tpl"}
