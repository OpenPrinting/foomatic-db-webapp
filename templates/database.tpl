{include file="page_masthead.tpl"}

<div id="two_col_col_1">
	{include file="page_breadcrumbs.tpl"}

	<h1>Printer Compatibility Database</h1>
	<p>The OpenPrinting database contains a wealth of information about specific printers, along with extensive driver information, the drivers themselves, basic specifications, and an associated set of configuration tools.</p>
	<ul>
    <li>You can find all the printer information from the printer listing page.</li>
    <li>You can find all the driver information and the drivers from the driver listing page.</li> 
	</ul>
	
	<h2>About the data</h2>
	
	<p>This database includes basic specifications for printers and details of how to make them go under normal unices that can run Ghostscript and/or filters like pnm2ppa. GNU/Linux, the BSDs and Mac OS X typify this category; users of other commercial unices can usually benefit from this information as well. The information here is not (very) useful for Windows users.</p>
	
	<p>For many drivers we ship also the driver itself as distribution-independent packages. These packages can be installed on all LSB-compliant distributions with CUPS, Ghostscript (ESP 8.15.3 or newer, GPL 8.60 or newer), Perl, and foomatic-rip. This covers the current versions of all major distributions. Appropriate links and links to the installation instructions you find on the driver overview page and on the appropriate driver and printer entry pages. The driver packages already contain their PPDs. So do not download and install the PPD in addition.</p>
	
	<p>We have also a web API for printer setup tools to be able to browse the database and to download driver packages. This allows fully automatic installation of detected printers which the local distribution does not support, and also updating drivers if the distribution does not ship an update. We provide enough data, that the tool can ask the user whether he really wants to install this driver: free/non-free, license, from manufacturer/third party, support contact and level, ...</p>
	
	<p>Printers are categorized according to how well they work under Linux and Unix. The ratings do not pertain to whether or not the printer will be auto-recognized or auto-configured, but merely to the highest level of functionality achieved.</p>
	
	<p><font color="green">Perfectly</font> <img src="/images/icons/Linuxyes.png" title="yes"> <img src="/images/icons/Linuxyes.png" title="yes"> <img src="/images/icons/Linuxyes.png" title="yes"></p>
	    <p>Perfect printers work perfectly; everything the printer can do is working also under Linux and Unix. For multifunction devices, this must include scanning/faxing/etc. </p>
	<p><font color="green">Mostly</font> <img src="/images/icons/Linuxyes.png" title="yes"> <img src="/images/icons/Linuxyes.png" title="yes"></p>
	    <p>These printers work almost perfectly - funny enhanced resolution modes may be missing, or the color is a bit off, but nothing that would make the printouts not useful.</p> 
	<p><font color="#ff9900">Partially</font> <img src="/images/icons/Linuxyes.png" title="yes"></p>
	    <p>These printers mostly don't work; you may be able to print only in black and white on a color printer, or the printouts look horrible.</p> 
	<p><font color="red">Paperweight</font> <img src="/images/icons/Linuxno.png" title="no"></p>
	    <p>These printers don't work at all. They may work in the future, but don't count on it.</p> 
	
	
	<p>This is an interactive database; if you know anything useful that isn't represented here, please add your knowledge to the pool -- post anything you know to the forum for your printer or use our add printer form, and one of our editors will incorporate your information into the data. If you printer is already listed, enter your contribution to the "User Notes" section of the existing entry.</p>
	
	<p>This entire database (except the driver packages) is available in an XML format as part of the Foomatic system, which provides configuration tools and filter scripts for a variety of spoolers. All driver packages and the LSB DDK are in this directory.</p> 
	
</div>
{include file="page_rightcommon.tpl" classtype="two_col_col_2"}
{include file="page_conclusion.tpl"}