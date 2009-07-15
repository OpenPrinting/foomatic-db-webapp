{include file="page_masthead.tpl"}

<div id="two_col_col_1">
	{include file="page_breadcrumbs.tpl"}


	<h1>Upload new driver</h1>

	<p>Thank you for taking the time to upload a printer driver tarball through our web interface.
		Your uploaded driver will be made available for download through our website, but also made 
		available to repositories and Linux distros supporting RPM and DEB packages.</p>
		
	<p>This system is, for the most part, fully automated. If you are a "trusted" contributor, 
		your upload will automatically be made available for download once it has been processed unless 
		we detect a problem. Otherwise, an OpenPrinting moderator will review your upload (including 
		installing it on a test machine) before it is included in the archives and made available.</p>


	<h2>Documentation</h2>
	<p>Please take the time to review the documentation for creating driver packages before proceeding.</p>
	<ul class="bulleted">
		<li><a href="404.php?id=doc">Some documentation link</a></li>
		<li><a href="404.php?id=doc">Some other documentation link</a></li>
	</ul>

	<h2>Upload your tarball</h2>
	{if $SESSION->isloggedIn()}
		{if $USER->isUploader() || $USER->isTrustedUploader}
			<p>Browse for the tarball on your local machine, then submit the form. 
			<strong>Please make sure your file name is in the format &lt;driver&gt;-&lt;version&gt;.tar.gz</strong>, otherwise 
				your upload will be rejected. Once your file has been uploaded, we will perform some checks to make sure everything 
				is okay.
			</p>
			
			{if !$USER->isTrustedUploader() }
				<p><strong>Note:</strong> your driver will be placed in the moderation queue and will be reviewed by an administrator
					before being added to the database.</p>
			{/if}
				
		<form action="?submitFile" method="post" enctype="multipart/form-data">
			<input type="file"></input> <input type="submit" value="Submit" />
		</form>	
			
		{else}
			<p>You do not have permissions to upload drivers.</p>
		{/if}
	{else}
		<p>Please log in.</p>
	{/if}
		
</div>
{include file="page_rightcommon.tpl" classtype="two_col_col_2"}
{include file="page_conclusion.tpl"}