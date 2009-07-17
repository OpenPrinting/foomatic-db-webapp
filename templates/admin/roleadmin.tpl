{include file="page_masthead.tpl"}

<div id="two_col_col_1">
	{include file="page_breadcrumbs.tpl"}

	{if $roleID}
		<div class="section">
			<h1>Editing role: {$roleName}</h1>
		
			<div class="left halfwidth">
				<h2>Set permissions for role</h2>
				<form method="post" action="?roleID={$roleID}&amp;savePrivs">
					<table class="data">
						<tr>
							<th>Setting</th><th>Permission</th>
						</tr>
						{foreach from=$permissions item=perm}
							<tr class="{cycle values="alt,"}">
								<td>
									{html_options name=priv_`$perm.privName` options=$priv_opts selected=$perm.value}
								</td>
								<td>{$perm.title}</td>
							</tr>
						{/foreach}
					</table>
					<br /><input type="submit" value="Update permissions" />
				</form>
				
			</div>
			<div class="right halfwidth">
				<h2>Assign user to this role</h2>
				<form method="post" action="?roleID={$roleID}&amp;addMember">
					<label>Username: <input type="text" name="userName" size="15" /></label> <input type="submit" value="Add" />
				</form>
				
				<h2>Current members</h2>
				<form method="post" action="?roleID={$roleID}&amp;removeMembers">
					<ul>
						{foreach from=$members item=m}
							<li><label><input type="checkbox" name="userName[]" value="{$m|escape}" /> {$m}</label></li>
						{foreachelse}
							<li>No members assigned.</li>
						{/foreach}
					</ul>
					<div><input type="submit" value="Remove selected members" /></div>
				</form>
			</div>
			
			<div class="clearfix"></div>
		</div>
		<br /><br />
	{/if}
	<h1>Add/edit roles</h1>
	<p>Select a role from the list below, and then choose "edit" to manage the permissions and user
		assignments for that role. To delete a role, choose "delete" instead. You can also create a 
		new role.</p>
		

	<form method="post" action="?">
		<fieldset>
			<legend>Edit a role</legend>
			<label for="rolelist"><strong>Select a role:</strong></label>
			<select id="rolelist" name="roleID">
				{foreach from=$roles item=role}
					<option value="{$role.roleID}">{$role.roleName|escape}</option>
				{foreachelse}
					<option value="0">No roles exist.</option>
				{/foreach}
			</select>
			<input type="submit" name="editRole" value="Edit role" />
			<input type="submit" name="deleteRole" value="Delete role" />
		</fieldset>
	</form>
	
	<form method="post" action="?createRole">
		<fieldset>
			<legend>Create a new role</legend>
			<label for="roleName"><strong>Enter the role name:</strong></label>
			<input type="text" name="roleName" id="roleName" />
			<input type="submit" value="Add" />
		</fieldset>
	</form>
	
	
</div>
{include file="page_rightcommon.tpl" classtype="two_col_col_2"}
{include file="page_conclusion.tpl"}
