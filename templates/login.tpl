{include file="page_masthead.tpl"}

<div id="two_col_col_1">
	{include file="page_breadcrumbs.tpl"}

	<h1>OpenPrinting Login</h1>

	<p>Please log in with your Linux Foundation username and password. If you need to register a new account, 
		recover your password, or validate your e-mail, please <a href="https://www.linuxfoundation.org/user/register">
		follow this link</a>.
	</p>
	

	{if $loginMessage}
		<div>
			<strong>{$loginMessage}</strong>
		</div>
	{/if}
		
	<p>Reminder to developer: this form is not using SSL yet!</p>
	<form id="login-form" method="post" action="?doLogin">
		<fieldset>
			<legend>User authentication</legend>
			<div class="clearfix">
				<div class="section left">
					<label for="username"><abbr title="Your LinuxFoundation.org username">Username:</abbr></label>
					<input type="text" name="username" id="username" />
				</div>
				<div class="section left">
					<label for="password">Password:</label>
					<input type="password" name="password" id="password" />
				</div>
			</div>
			<div class="section">
				<input type="submit" value="Log in to OpenPrinting" />
			</div>
		</fieldset>
	</form>

	
	<script type="text/javascript">
		document.getElementsByName('username')[0].focus();
	</script>
	
</div>

{include file="page_rightcommon.tpl" classtype="two_col_col_2"}
{include file="page_conclusion.tpl"}
