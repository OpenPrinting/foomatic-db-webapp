{include file="page_masthead.tpl"}

<div id="two_col_col_1">
	{include file="page_breadcrumbs.tpl"}

	<h1>OpenPrinting Website Administration</h1>
	<p>This is the administration area for the OpenPrinting website.</p>
	
	<h2>Driver upload queue</h2>
	<p>Shown below are the drivers waiting to be approved by a site administrator. Click any driver for more information or to download it for testing.</p>
	
	{if $ALLOW_ROLE_ADMIN}
		<h2>User roles and permissions</h2>
		<p>To manage user roles, permissions, and memberships, visit the <a href="{$BASEURL}admin/roleadmin">roles administration page.</a></p>
	{/if}
	
</div>
{include file="page_rightcommon.tpl" classtype="two_col_col_2"}
{include file="page_conclusion.tpl"}
