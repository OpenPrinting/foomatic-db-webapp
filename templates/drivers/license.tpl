{include file="page_masthead.tpl"}

<div id="two_col_col_1">
	{include file="page_breadcrumbs.tpl"}

	{if $driver}

		<h1>License for the driver {$driver.name}</h1>

		{if strlen($driver.license) > 0}
		    <p>&nbsp;</p>
		    <h2>{$driver.license}</h2>
		{/if}

		{if strlen($driver.licenselink) > 0}
		    <p></p>
		    <p><a href="{$driver.licenselink}">
		    License text on the driver supplier's web site</a></p>
		{/if}

		{if strlen($driver.licensetext) > 0}
		    <p></p>
		    <p><pre>{$driver.licensetext}</pre></p>
		{/if}

	{else}
		<h1>Driver not found.</h1>
		<p>We're sorry, but the driver ID you provided was not found in our database.</p>
	{/if}
</div>
{include file="page_rightcommon.tpl" classtype="two_col_col_2"}
{include file="page_conclusion.tpl"}
