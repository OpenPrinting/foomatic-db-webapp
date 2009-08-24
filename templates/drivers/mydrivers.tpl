{include file="page_masthead.tpl"}

<div id="two_col_col_1">
	{include file="page_breadcrumbs.tpl"}

	<h1>Existing uploads</h1>
	
	<h2>Uploads currently being processed</h2>
	<p>The following driver uploads are either being processed by our system, waiting for administrator 
		approval, or require additional information. The respective status of each upload is shown.</p>
		
	<h2>Previously accepted uploads</h2>
	<p>The most recent versions of drivers you have uploaded in the past are shown here.</p>

	<h1>Upload new driver</h1>
		
	<p>Please take the time to review the documentation for creating driver packages before proceeding.</p>
	<ul class="bulleted">
		<li><a href="404.php?id=doc">Some documentation link</a></li>
		<li><a href="404.php?id=doc">Some other documentation link</a></li>
	</ul>
	
	{if $UNTRUSTED}
		<p><strong>Note:</strong> your driver will be placed in the moderation queue and will be reviewed by an administrator
			before being added to the database and made available for download.</p>
	{else}
		<p>Your new driver will be processed and, unless the packaging scripts detect a problem that needs your attention, will 
			automatically be made available for download.</p>	
	{/if}
		
	<form action="{$BASEURL}drivers/upload/?link" method="post">
		<fieldset>
			<legend>Add driver by URL</legend>
				<p>You may add a driver to our database by providing a link to the driver. Please enter the full path to the driver,
					including HTTP and your domain, and then submit the form.
				</p>
				
				<div>
					<input type="text" name="link"></input> <input style="font-weight: bold" type="submit" value="Submit" />
				</div>
		</fieldset>
	</form>	
	
	<form action="{$BASEURL}drivers/upload/?upload" method="post" enctype="multipart/form-data">
		<fieldset>
			<legend>Add driver by upload</legend>
				<p>Browse for the tarball on your local machine, then submit the form. 
					<strong>Please make sure your file name is in the format &lt;driver&gt;-&lt;version&gt;.tar.gz</strong>, otherwise 
					your upload will be rejected. Once your file has been uploaded, we will perform some checks to make sure everything 
					is okay.
				</p>
				
				<div>
					<input type="file" name="file"></input> <input style="font-weight: bold" type="submit" value="Submit" />
				</div>
		</fieldset>
	</form>	


		
</div>
{include file="page_rightcommon.tpl" classtype="two_col_col_2"}
{include file="page_conclusion.tpl"}