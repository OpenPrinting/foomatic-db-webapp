{if isset($loggedout) and $loggedout == "true"}
	<div class="error"><strong>You do not have the necessary permissions to access the requested page.</strong></div>
{else}
	
<table width="100%" cellpadding="2" cellspacing="1" style="background: #ccc;">
    <tr style="background: #EEE;">
        <td>Printer Name
        </td>
        <td>Username
        </td>
		<td>Date Submitted
        </td>
		<td>Release Date
        </td>
        <td>Status
        </td>
        <td>Approver
        </td>
        <td>Status Date
        </td>
    </tr>
	{foreach from=$dataPrinters item=printer}
    <tr style="background: {cycle values="#F5F5F5,#EEEEEE"}">
        <td>{$printer.make} {$printer.model}
        </td>
        <td>{$printer.contributor}
        </td>
        <td>{$printer.submitted}
        </td>
        <td>{$printer.showentry}
        </td>
        <td>
        	{if $printer.approved != ""}
        		Approved
			{/if}	
        	{if $printer.rejected != ""}
        		Rejected
			{/if}	
        </td>
        <td>{$printer.approver}
        </td>
        <td> 
			{if $printer.approved != ""}
        		{$printer.approved}
			{/if}	
        	{if $printer.rejected != ""}
        		{$printer.rejected}
			{/if}
        </td>
    </tr>
	<tr style="background: #F5F5F5"}">
		<td colspan="7">{$printer.comment}</td>
	</tr>
	{/foreach}



</table>
{/if}