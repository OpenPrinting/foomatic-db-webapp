{include file="page_masthead.tpl"}

{literal}
<script type="text/javascript">
$(function(){
  $('#remove').click(function() {
    $('#removeform').submit();
  });
});
</script>
{/literal}

<div id="two_col_col_1">
	{include file="page_breadcrumbs.tpl"}

	{if isset($roleID)}
		<div class="section">
     <h1>Editing role: {$roleName}</h1>
     <div class="members">
       <h2>Users assigned to {$roleName} role</h2> 
       <div id ="assign">
				<form method="post" action="?roleID={$roleID}&amp;addMember">
					<label>Username: <input type="text" name="userName" size="15" /></label> <input type="submit" value="Add" 
				</form>
      </div>
      
      <div id="remove"><input type="submit" value="Remove selected members" /></div>
      
        <div class="memberlist">
          <form method="post" action="?roleID={$roleID}&amp;removeMembers" id="removeform">
           <table width="100%">
            <tr>
              <th></th>
              <th>Username</th>
              <th>Full name</th>
              <th>Last Login</th>
              <th>Blocked</th>
            </tr>
            
            {foreach from=$members item=m}
             <tr style="background: {cycle values="#F5F5F5,#EEEEEE"}">
                <td><input type="checkbox" name="userName[]" value="{$m.uid|escape}" /></td> 
                <td>{$m.uid}</td>
                <td>{$m.name}</td>
                <td>{$m.lastlogin}</td>
                <td>{$m.block}</td>
              </tr>  
              {foreachelse}
               <tr><td colspan="5">No members assigned.</td></tr>
              {/foreach}
            </table>
          </form>
          
          {if $membertotal > $pagesize }
            <div id="paginate"> 
              {if $offset > 0}
              <a href="{$BASEURL}admin/roleadmin?offset={$offset-$pagesize}&amp;roleID={$roleID}">PREV</a>
              {else}
              PREV
              {/if}
               &nbsp;|&nbsp;
              {$offset+1}-{if $offset+$pagesize < $membertotal} {$offset+$pagesize} {else} {$membertotal} {/if} of {$membertotal} members
              &nbsp;|&nbsp;
              {if $offset <= $membertotal-$pagesize}
              <a href="{$BASEURL}admin/roleadmin?offset={$offset+$pagesize}&amp;roleID={$roleID}">NEXT</a>
              {else}
               NEXT
              {/if}
            </div>
            {/if}
        </div>
        
			</div>
      
      
			<div>
				<form method="post" action="?roleID={$roleID}&amp;savePrivs">
        <fieldset>
			    <legend>Set permissions for {$roleName} role</legend> 
					<table class="data">
						<tr>
							<th>Setting</th><th>Permission</th>
						</tr>
						{foreach from=$permissions item=perm}
							<tr class="{cycle values="alt,"}">
								<td>
									{html_options name="priv_`$perm.privName`" options=$priv_opts selected=$perm.value}
								</td>
								<td>{$perm.title}</td>
							</tr>
						{/foreach}
					</table>
					<br /><input type="submit" value="Update permissions" />
        </fieldset>
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
