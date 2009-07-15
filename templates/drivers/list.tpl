{include file="page_masthead.tpl"}

<div id="two_col_col_1">
	{include file="page_breadcrumbs.tpl"}

	<h1>Driver listings</h1>

	<p>Life will be great when this page actually talks to the database, eh?</p>
	
	<div class="section" id="for-devs">
	
		<h2>Upload a driver tarball</h2>
	
		<p>If you are a driver developer or printer manufacturer, you may upload your printer driver tarball 
			directly through our website. The driver will be made available for download on our website, but also
			included in repositories for Linux distibutions such as Ubuntu. To upload your driver or check the status 
			of one of your previous uploads, please <a href="{$BASEURL}drivers/mydrivers">follow this link.</a>
		</p>	
	</div>
	
	<h2>List of available drivers</h2>
	
	<table class="data">
		<tr>
			<th /><th /><th>Driver</th><th>Description</th><th>Type</th><th># Printers</th>
		</tr>
		<tr>
			<td><a href="#">View</a></td>
			<td></td>
			<td>eplaser</td>
			<td></td>
			<td>Ghostscript built-in</td>
			<td>28</td>
		</tr>
		<tr class="alt">
			<td><a href="#">View</a></td>
			<td></td>
			<td>epson</td>
			<td></td>
			<td>Ghostscript built-in</td>
			<td>17</td>
		</tr>
		<tr>
			<td><a href="#">View</a></td>
			<td></td>
			<td>foo2hp</td>
			<td></td>
			<td>Filter</td>
			<td>5</td>
		</tr>
		<tr class="alt">
			<td><a href="#">View</a></td>
			<td><img src="images/icons/download.png" alt="Downloads" title="Downloads" /></td>
			<td>min12xxw</td>
			<td></td>
			<td>Filter</td>
			<td>5</td>
		</tr>
		<tr>
			<td><a href="#">View</a></td>
			<td><img src="images/icons/download.png" alt="Downloads" title="Downloads" /></td>
			<td>Postscript-HP</td>
			<td style="font-size: 10px; width: 225px;">PPD files for HP's PostScript printers, supplied by HP</td>
			<td>PostScript</td>
			<td>121</td>
		</tr>
	</table>
	
</div>
{include file="page_rightcommon.tpl" classtype="two_col_col_2"}
{include file="page_conclusion.tpl"}
