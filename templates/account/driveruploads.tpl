<table width="100%" cellpadding="2" cellspacing="1" style="background: #ccc;">
    <tr style="background: #EEE;">
        <td>Driver Name
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
	
	{foreach from=$dataDrivers item=driver}
    <tr style="background: {cycle values="#F5F5F5,#EEEEEE"}">
        <td>{$driver.name}
        </td>
        <td>{$driver.contributor}
        </td>
        <td>{$driver.submitdate}
        </td>
        <td>{$driver.showentry}
        </td>
        <td>
        	{if $driver.approved != ""}
        		Approved
			{/if}	
        	{if $driver.rejected != ""}
        		Rejected
			{/if}	
        </td>
        <td>{$driver.approver}
        </td>
        <td> 
			{if $driver.approved != ""}
        		{$driver.approved}
			{/if}	
        	{if $driver.rejected != ""}
        		{$driver.rejected}
			{/if}
        </td>
    </tr>
	{/foreach}


 


</table>
