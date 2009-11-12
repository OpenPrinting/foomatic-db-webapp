{include file="page_masthead.tpl"}

<div id="two_col_col_1">
	{include file="page_breadcrumbs.tpl"}

	<h1>Add a New Printer</h1>

<br>	

<h2>Printer Information</h2>
<br>
<form action="/printers/upload" method="post">
	<input type="hidden" value="1.1" name="basev"/>
	<input type="hidden" value="" name="backupmake"/>
	<input type="hidden" value="" name="backupmodel"/>
	<input type="hidden" value="" name="backupid"/>

	<input type="submit" name="submit" value="Add Printer"> <a href="/printers/upload">Cancel</a>	
	<br><br>
	<table cellpadding="4" style="background: #eee; border: 1px solid #ccc;">
		<tr bgcolor="#dfdfdf">
			<td align="right">Your e-Mail:</td> 
			<td><input type="text" size="32" tabindex="1" name="email"/> </td>
			<td><p>Your e-mail address. Not required, but if you supply it, we can ask you in the 
			case that something is not clear in your new printer entry. We will not give your 
			address to any third party, nor will we display it on web pages without any protection.</p></td>
		</tr> 
		<tr bgcolor="#efefef">
			<td align="right">New entry:</td> 
			<td><label><input type="checkbox" checked="checked" tabindex="2" value="on" name="newentry"/></label> </td>
			<td><p>Check the box if you intend to create a new printer entry. If you have modified manufacturer and/or 
			 model name, the previous entry is conserved. If you want to rename an existing entry, uncheck 
			 this box.</p></td>
		</tr> 
		<tr bgcolor="#dfdfdf">
			<td align="right">Manufacturer:</td> 
			<td>
				<select tabindex="3" name="mmake">
					<option value="" selected="selected">Choose here</option>
					<option value="Alps">Alps</option>
					<option value="Anitech">Anitech</option>
					<option value="Apollo">Apollo</option>
					<option value="Apple">Apple</option>
					<option value="Avery">Avery</option>
					<option value="Brother">Brother</option>
					<option value="CalComp">CalComp</option>
					<option value="Canon">Canon</option>
					<option value="Casio">Casio</option>
					<option value="Citizen">Citizen</option>
					<option value="CItoh">CItoh</option>
					<option value="Compaq">Compaq</option>
					<option value="DEC">DEC</option>
					<option value="Dell">Dell</option>
					<option value="Dymo-CoStar">Dymo-CoStar</option>
					<option value="Epson">Epson</option>
					<option value="Fujifilm">Fujifilm</option>
					<option value="Fujitsu">Fujitsu</option>
					<option value="Generic">Generic</option>
					<option value="Genicom">Genicom</option>
					<option value="Gestetner">Gestetner</option>
					<option value="Heidelberg">Heidelberg</option>
					<option value="Hitachi">Hitachi</option>
					<option value="HP">HP</option>
					<option value="IBM">IBM</option>
					<option value="Imagen">Imagen</option>
					<option value="Imagistics">Imagistics</option>
					<option value="InfoPrint">InfoPrint</option>
					<option value="Infotec">Infotec</option>
					<option value="Kodak">Kodak</option>
					<option value="KONICA MINOLTA">KONICA MINOLTA</option>
					<option value="Kyocera">Kyocera</option>
					<option value="Lanier">Lanier</option>
					<option value="LaserMaster">LaserMaster</option>
					<option value="Lexmark">Lexmark</option>
					<option value="Minolta">Minolta</option>
					<option value="Minolta QMS">Minolta QMS</option>
					<option value="Mitsubishi">Mitsubishi</option>
					<option value="NEC">NEC</option>
					<option value="NRG">NRG</option>
					<option value="Oce">Oce</option>
					<option value="Oki">Oki</option>
					<option value="Olivetti">Olivetti</option>
					<option value="Olympus">Olympus</option>
					<option value="Panasonic">Panasonic</option>
					<option value="PCPI">PCPI</option>
					<option value="Pentax">Pentax</option>
					<option value="Printrex">Printrex</option>
					<option value="QMS">QMS</option>
					<option value="Raven">Raven</option>
					<option value="Ricoh">Ricoh</option>
					<option value="Samsung">Samsung</option>
					<option value="Savin">Savin</option>
					<option value="Seiko">Seiko</option>
					<option value="Sharp">Sharp</option>
					<option value="SiPix">SiPix</option>
					<option value="Sony">Sony</option>
					<option value="Star">Star</option>
					<option value="Tally">Tally</option>
					<option value="Tektronix">Tektronix</option>
					<option value="Texas Instruments">Texas Instruments</option>
					<option value="Toshiba">Toshiba</option>
					<option value="Xante">Xante</option>
					<option value="Xerox">Xerox</option>
					</select>  
					OR  
					<input type="text" size="16" tabindex="4" name="make"/>
				</td> 
				<td><p>Manufacturer name for the printer. If there
	                         are already printers of this manufacturer,
	                         choose the manufacturer name from the menu
	                         and leave the input field blank.<br/>
	                         DO NOT write the model name into the input
	                         field, the model name goes into the field below. 
	                         Use the input field for manufacturers which are
	                         not listed yet.</p></td>
			</tr> 
			<tr bgcolor="#efefef">
				<td align="right">Model:</td> 
				<td><input type="text" size="32" tabindex="5" name="model"/></td> 
				<td><p>Model name for the printer.  Please try
				 to follow the conventions used for
				 other printers in the same
				 family. DO NOT repeat the manufacturer's name
	             in this field.</p>
				</td>
			</tr> 
			<tr bgcolor="#dfdfdf">
				<td align="right">URL:</td> 
				<td><input type="text" maxlength="128" size="32" tabindex="6" name="url"/></td> 
				<td><p>Manufacturer's web page for this specific printer.  The maker's home page will 
				already be linked to, so if you don't know where to find a page about this printer, 
				leave this blank. And do not forget the "http://" in the beginning of 
				the address.</p></td>
			</tr> 
			<tr bgcolor="#efefef">
				<td align="right">Resolution (X x Y):</td> 
				<td><input type="text" size="4" tabindex="7" name="resolution_x"/> x <input type="text" size="4" tabindex="8" name="resolution_y"/></td> 
				<td><p>Maximum X and Y resolution the printer can do.  Available Unix software may not support 
				the finest modes; if so, please say so in the notes.</p></td>
			</tr> 
			<tr bgcolor="#dfdfdf">
				<td align="right">Color:</td> 
				<td><label><input type="checkbox" tabindex="9" value="on" name="color"/></label></td> 
				<td><p>Check the box if this printer can do color.  Some printers may not be able to do 
				so without vendor drivers; say so in the notes if so.</p></td>
			</tr> 
			<tr bgcolor="#efefef">
				<td align="right">Mechanism:</td> 
				<td>
					<select tabindex="10" name="type">
						<option value="">Unknown/Other</option>
						<option value="dotmatrix">Dot Matrix</option>
						<option value="impact">Impact</option>
						<option value="inkjet">Inkjet</option>
						<option value="laser">Laser</option>
						<option value="led">LED</option>
						<option value="sublimation">Dye Sublimation</option>
						<option value="transfer">Thermal Transfer</option>
					</select>
				</td> 
				<td><p>What sort of printing mechanism does this printer use?</p></td>
			</tr> 
			<tr bgcolor="#dfdfdf">
				<td align="right">Refill:</td> 
				<td><input type="text" size="32" tabindex="11" name="refill"/></td> 
				<td><p>A short description of the non-paper consumable(s): cartridge, ribbon, toner, 
				printhead, etc.  Ballpark refill pricing would be nice, too, if known.</p></td>
			</tr> 
			<tr bgcolor="#efefef">
				<td align="right">Language:</td> 
				<td><label><input type="checkbox" tabindex="12" value="on" name="postscript"/>PostScript</label> level <input type="text" size="4" tabindex="13" name="postscript_level"/> 
				<br/>    
				URL for manufacturer's PPD <br/>     
				<input type="text" size="30" tabindex="14" name="ppdurl"/> <br/> 
				<label><input type="checkbox" tabindex="15" value="on" name="pdf"/>PDF</label> level <input type="text" size="4" tabindex="16" name="pdf_level"/> <br/>  
				<label><input type="checkbox" tabindex="17" value="on" name="lips"/>LIPS</label> level <input type="text" size="4" tabindex="18" name="lips_level"/> <br/>
				<label><input type="checkbox" tabindex="19" value="on" name="pcl"/>PCL</label> level <input type="text" size="4" tabindex="20" name="pcl_level"/> <br/> 
				<label><input type="checkbox" tabindex="21" value="on" name="escp"/>ESC/P</label> level <input type="text" size="4" tabindex="22" name="escp_level"/> <br/> 
				<label><input type="checkbox" tabindex="23" value="on" name="escp2"/>ESC/P 2</label> level <input type="text" size="4" tabindex="24" name="escp2_level"/> <br/> 
				<label><input type="checkbox" tabindex="25" value="on" name="hpgl2"/>HP-GL/2</label> level <input type="text" size="4" tabindex="26" name="hpgl2_level"/> <br/> 
				<label><input type="checkbox" tabindex="27" value="on" name="tiff"/>TIFF</label> level <input type="text" size="4" tabindex="28" name="tiff_level"/> <br/> 
				<label><input type="checkbox" tabindex="29" value="on" name="proprietary"/>Proprietary</label></td> 
				<td><p>The printer control language spoken by this printer, and level or version if known.  Mail us and add a remark in the 
				"Notes:" field if we've forgotten any languages.</p></td>
			</tr> 
			<tr bgcolor="#dfdfdf">
				<td align="right">ASCII:</td> 
				<td><label><input type="checkbox" tabindex="30" value="on" name="ascii"/></label></td> 
				<td><p>This printer will print text if you just send it plain ascii.  
				Uncheck for printers that <b>only</b> work with Ghostscript and a driver or the like.</p></td>
			</tr> 
			<tr bgcolor="#efefef">
				<td align="right">PJL:</td> 
				<td><label><input type="checkbox" tabindex="31" value="on" name="pjl"/></label></td> 
				<td><p>Check the box if this printer supports HP's Printer Job Language (PJL).</p></td>
			</tr> 
			<tr bgcolor="#dfdfdf">
				<td align="right">Functionality:</td> 
				<td>
					<select tabindex="32" name="func">
						<option value="A">Perfectly</option>
						<option value="B">Mostly</option>
						<option value="D">Partially</option>
						<option value="F" selected="selected">Paperweight</option>
					</select> 
					<font color="red"/></td> 
				<td><p>How well does this printer work using Un*x software (ie ghostscript).  Put details 
				into the "Notes:" field.  Mostly means it prints, but minor things are missing. Partially 
				means it prints, but major things are missing.<br/>If you choose a non-Paperweight rating, 
				choose/enter a driver and/or enter in the "Notes:" field how you made this printer working.</p></td>
			</tr> 
			<tr bgcolor="#efefef">
				<td align="right">
					<input type="hidden" value="1" name="dnumber"/>
					<input type="hidden" value="on" name="dactive0"/>
					Driver:</td> 
				<td>
					<select tabindex="33" name="dname0">
						<option value="" selected="selected">No driver</option>
						<option value="ap3250">ap3250</option>
						<option value="appledmp">appledmp</option>
						<option value="bj8gc12f.upp">bj8gc12f.upp</option>
						<option value="bj8hg12f.upp">bj8hg12f.upp</option>
						<option value="bj8oh06n.upp">bj8oh06n.upp</option>
						<option value="bj8pa06n.upp">bj8pa06n.upp</option>
						<option value="bj8pp12f.upp">bj8pp12f.upp</option>
						<option value="bj8ts06n.upp">bj8ts06n.upp</option>
						<option value="bj10">bj10</option>
						<option value="bj10e">bj10e</option>
						<option value="bj10v">bj10v</option>
						<option value="bj10vh">bj10vh</option>
						<option value="bj200">bj200</option>
						<option value="bjc250gs">bjc250gs</option>
						<option value="bjc600">bjc600</option>
						<option value="bjc610a0.upp">bjc610a0.upp</option>
						<option value="bjc610a1.upp">bjc610a1.upp</option>
						<option value="bjc610a2.upp">bjc610a2.upp</option>
						<option value="bjc610a3.upp">bjc610a3.upp</option>
						<option value="bjc610a4.upp">bjc610a4.upp</option>
						<option value="bjc610a5.upp">bjc610a5.upp</option>
						<option value="bjc610a6.upp">bjc610a6.upp</option>
						<option value="bjc610a7.upp">bjc610a7.upp</option>
						<option value="bjc610a8.upp">bjc610a8.upp</option>
						<option value="bjc610b1.upp">bjc610b1.upp</option>
						<option value="bjc610b2.upp">bjc610b2.upp</option>
						<option value="bjc610b3.upp">bjc610b3.upp</option>
						<option value="bjc610b4.upp">bjc610b4.upp</option>
						<option value="bjc610b6.upp">bjc610b6.upp</option>
						<option value="bjc610b7.upp">bjc610b7.upp</option>
						<option value="bjc610b8.upp">bjc610b8.upp</option>
						<option value="bjc800">bjc800</option>
						<option value="bjc800j">bjc800j</option>
						<option value="bjc880j">bjc880j</option>
						<option value="bjc6000a1.upp">bjc6000a1.upp</option>
						<option value="bjc6000b1.upp">bjc6000b1.upp</option>
						<option value="c2050">c2050</option>
						<option value="c2070">c2070</option>
						<option value="capt">capt</option>
						<option value="cdj500">cdj500</option>
						<option value="cdj550">cdj550</option>
						<option value="cdj550.upp">cdj550.upp</option>
						<option value="cdj670">cdj670</option>
						<option value="cdj850">cdj850</option>
						<option value="cdj880">cdj880</option>
						<option value="cdj890">cdj890</option>
						<option value="cdj970">cdj970</option>
						<option value="cdj1600">cdj1600</option>
						<option value="cdnj500">cdnj500</option>
						<option value="chp2200">chp2200</option>
						<option value="cjet">cjet</option>
						<option value="cljet5">cljet5</option>
						<option value="cljet5c">cljet5c</option>
						<option value="cp50">cp50</option>
						<option value="cZ11">cZ11</option>
						<option value="cZ11somsom">cZ11somsom</option>
						<option value="declj250">declj250</option>
						<option value="deskjet">deskjet</option>
						<option value="dj505j">dj505j</option>
						<option value="djet500">djet500</option>
						<option value="dnj650c">dnj650c</option>
						<option value="dplix">dplix</option>
						<option value="drv_x125">drv_x125</option>
						<option value="drv_z42">drv_z42</option>
						<option value="eplaser">eplaser</option>
						<option value="eplaser-jp">eplaser-jp</option>
						<option value="eps9high">eps9high</option>
						<option value="eps9mid">eps9mid</option>
						<option value="epson">epson</option>
						<option value="epsonc">epsonc</option>
						<option value="epsonepl">epsonepl</option>
						<option value="escpage">escpage</option>
						<option value="fmlbp">fmlbp</option>
						<option value="fmpr">fmpr</option>
						<option value="foo2hiperc">foo2hiperc</option>
						<option value="foo2hp">foo2hp</option>
						<option value="foo2kyo">foo2kyo</option>
						<option value="foo2lava">foo2lava</option>
						<option value="foo2oak">foo2oak</option>
						<option value="foo2oak-z1">foo2oak-z1</option>
						<option value="foo2qpdl">foo2qpdl</option>
						<option value="foo2slx">foo2slx</option>
						<option value="foo2xqx">foo2xqx</option>
						<option value="foo2zjs">foo2zjs</option>
						<option value="gdi">gdi</option>
						<option value="gimp-print">gimp-print</option>
						<option value="gutenprint">gutenprint</option>
						<option value="hl7x0">hl7x0</option>
						<option value="hl1250">hl1250</option>
						<option value="hpdj">hpdj</option>
						<option value="hpijs-pcl3">hpijs-pcl3</option>
						<option value="hpijs-pcl5c">hpijs-pcl5c</option>
						<option value="hpijs-pcl5e">hpijs-pcl5e</option>
						<option value="hplip">hplip</option>
						<option value="ibmpro">ibmpro</option>
						<option value="imagen">imagen</option>
						<option value="iwhi">iwhi</option>
						<option value="iwlo">iwlo</option>
						<option value="iwlq">iwlq</option>
						<option value="jetp3852">jetp3852</option>
						<option value="jj100">jj100</option>
						<option value="la50">la50</option>
						<option value="la70">la70</option>
						<option value="la70t">la70t</option>
						<option value="la75">la75</option>
						<option value="la75plus">la75plus</option>
						<option value="laserjet">laserjet</option>
						<option value="lbp8">lbp8</option>
						<option value="lbp310">lbp310</option>
						<option value="lbp320">lbp320</option>
						<option value="lbp660">lbp660</option>
						<option value="lbp800">lbp800</option>
						<option value="lex5700">lex5700</option>
						<option value="lex7000">lex7000</option>
						<option value="lips2p">lips2p</option>
						<option value="lips3">lips3</option>
						<option value="lips4">lips4</option>
						<option value="lips4v">lips4v</option>
						<option value="lj4dith">lj4dith</option>
						<option value="lj4dithp">lj4dithp</option>
						<option value="lj5gray">lj5gray</option>
						<option value="lj5mono">lj5mono</option>
						<option value="lj250">lj250</option>
						<option value="ljet2p">ljet2p</option>
						<option value="ljet3">ljet3</option>
						<option value="ljet3d">ljet3d</option>
						<option value="ljet4">ljet4</option>
						<option value="ljet4d">ljet4d</option>
						<option value="ljetplus">ljetplus</option>
						<option value="lm1100">lm1100</option>
						<option value="ln03">ln03</option>
						<option value="lp2000">lp2000</option>
						<option value="lp2563">lp2563</option>
						<option value="lp8000">lp8000</option>
						<option value="lpstyl">lpstyl</option>
						<option value="lq850">lq850</option>
						<option value="lx5000">lx5000</option>
						<option value="lxm3200">lxm3200</option>
						<option value="lxm3200c">lxm3200c</option>
						<option value="lxm3200m">lxm3200m</option>
						<option value="lxm3200p">lxm3200p</option>
						<option value="lxm3200-tweaked">lxm3200-tweaked</option>
						<option value="lxm5700m">lxm5700m</option>
						<option value="lxx74">lxx74</option>
						<option value="lz11-V2">lz11-V2</option>
						<option value="m2300w">m2300w</option>
						<option value="m2400w">m2400w</option>
						<option value="m8510">m8510</option>
						<option value="md1xMono">md1xMono</option>
						<option value="md2k">md2k</option>
						<option value="md5k">md5k</option>
						<option value="md50Eco">md50Eco</option>
						<option value="md50Mono">md50Mono</option>
						<option value="min12xxw">min12xxw</option>
						<option value="mj500c">mj500c</option>
						<option value="mj700v2c">mj700v2c</option>
						<option value="mj6000c">mj6000c</option>
						<option value="mj8000c">mj8000c</option>
						<option value="ml85p">ml85p</option>
						<option value="ml600">ml600</option>
						<option value="necp2x.upp">necp2x.upp</option>
						<option value="necp2x6.upp">necp2x6.upp</option>
						<option value="necp6">necp6</option>
						<option value="npdl">npdl</option>
						<option value="nwp533">nwp533</option>
						<option value="oce9050">oce9050</option>
						<option value="oki4drv">oki4drv</option>
						<option value="oki4w">oki4w</option>
						<option value="oki182">oki182</option>
						<option value="okiibm">okiibm</option>
						<option value="omni">omni</option>
						<option value="paintjet">paintjet</option>
						<option value="pbm2l7k">pbm2l7k</option>
						<option value="pbm2l2030">pbm2l2030</option>
						<option value="pbm2lwxl">pbm2lwxl</option>
						<option value="pbm2ppa">pbm2ppa</option>
						<option value="pbmtozjs">pbmtozjs</option>
						<option value="pcl3">pcl3</option>
						<option value="pegg">pegg</option>
						<option value="pentaxpj">pentaxpj</option>
						<option value="picty180">picty180</option>
						<option value="pj">pj</option>
						<option value="pjetxl">pjetxl</option>
						<option value="pjxl">pjxl</option>
						<option value="pjxl300">pjxl300</option>
						<option value="PM760p.upp">PM760p.upp</option>
						<option value="PM760pl.upp">PM760pl.upp</option>
						<option value="PM820p.upp">PM820p.upp</option>
						<option value="PM820pl.upp">PM820pl.upp</option>
						<option value="pnm2ppa">pnm2ppa</option>
						<option value="Postscript">Postscript</option>
						<option value="Postscript1">Postscript1</option>
						<option value="Postscript2-Oce">Postscript2-Oce</option>
						<option value="Postscript-Brother">Postscript-Brother</option>
						<option value="Postscript-Dell">Postscript-Dell</option>
						<option value="Postscript-Epson">Postscript-Epson</option>
						<option value="Postscript-Genicom">Postscript-Genicom</option>
						<option value="Postscript-Gestetner">Postscript-Gestetner</option>
						<option value="Postscript-HP">Postscript-HP</option>
						<option value="Postscript-InfoPrint">Postscript-InfoPrint</option>
						<option value="Postscript-Infotec">Postscript-Infotec</option>
						<option value="Postscript-KONICA_MINOLTA">Postscript-KONICA_MINOLTA</option>
						<option value="Postscript-Kyocera">Postscript-Kyocera</option>
						<option value="Postscript-Lanier">Postscript-Lanier</option>
						<option value="Postscript-Lexmark">Postscript-Lexmark</option>
						<option value="Postscript-NRG">Postscript-NRG</option>
						<option value="Postscript-Oce">Postscript-Oce</option>
						<option value="Postscript-Oce-KM">Postscript-Oce-KM</option>
						<option value="Postscript-Oki">Postscript-Oki</option>
						<option value="Postscript-Ricoh">Postscript-Ricoh</option>
						<option value="Postscript-Savin">Postscript-Savin</option>
						<option value="Postscript-Sharp">Postscript-Sharp</option>
						<option value="Postscript-Toshiba">Postscript-Toshiba</option>
						<option value="Postscript-Xerox">Postscript-Xerox</option>
						<option value="ppmtocpva">ppmtocpva</option>
						<option value="ppmtomd">ppmtomd</option>
						<option value="pr150">pr150</option>
						<option value="pr201">pr201</option>
						<option value="ptouch">ptouch</option>
						<option value="pxl1010">pxl1010</option>
						<option value="pxlcolor">pxlcolor</option>
						<option value="pxlcolor-Gestetner">pxlcolor-Gestetner</option>
						<option value="pxlcolor-InfoPrint">pxlcolor-InfoPrint</option>
						<option value="pxlcolor-Infotec">pxlcolor-Infotec</option>
						<option value="pxlcolor-Lanier">pxlcolor-Lanier</option>
						<option value="pxlcolor-NRG">pxlcolor-NRG</option>
						<option value="pxlcolor-Ricoh">pxlcolor-Ricoh</option>
						<option value="pxlcolor-Savin">pxlcolor-Savin</option>
						<option value="pxldpl">pxldpl</option>
						<option value="pxljr">pxljr</option>
						<option value="pxlmono">pxlmono</option>
						<option value="pxlmono-Gestetner">pxlmono-Gestetner</option>
						<option value="pxlmono-InfoPrint">pxlmono-InfoPrint</option>
						<option value="pxlmono-Infotec">pxlmono-Infotec</option>
						<option value="pxlmono-Lanier">pxlmono-Lanier</option>
						<option value="pxlmono-NRG">pxlmono-NRG</option>
						<option value="pxlmono-Ricoh">pxlmono-Ricoh</option>
						<option value="pxlmono-Savin">pxlmono-Savin</option>
						<option value="r4081">r4081</option>
						<option value="ras1.upp">ras1.upp</option>
						<option value="ras3.upp">ras3.upp</option>
						<option value="ras4.upp">ras4.upp</option>
						<option value="ras8m.upp">ras8m.upp</option>
						<option value="ras24.upp">ras24.upp</option>
						<option value="ras32.upp">ras32.upp</option>
						<option value="rastertokmXXXXdl">rastertokmXXXXdl</option>
						<option value="rpdl">rpdl</option>
						<option value="s400a1.upp">s400a1.upp</option>
						<option value="s400b1.upp">s400b1.upp</option>
						<option value="sharp.upp">sharp.upp</option>
						<option value="sipixa6.upp">sipixa6.upp</option>
						<option value="sj48">sj48</option>
						<option value="slap">slap</option>
						<option value="sparc">sparc</option>
						<option value="splix">splix</option>
						<option value="st640ih.upp">st640ih.upp</option>
						<option value="st640ihg.upp">st640ihg.upp</option>
						<option value="st640p.upp">st640p.upp</option>
						<option value="st640pg.upp">st640pg.upp</option>
						<option value="st640pl.upp">st640pl.upp</option>
						<option value="st640plg.upp">st640plg.upp</option>
						<option value="st800">st800</option>
						<option value="stc.upp">stc.upp</option>
						<option value="stc2_h.upp">stc2_h.upp</option>
						<option value="stc2s_h.upp">stc2s_h.upp</option>
						<option value="stc2.upp">stc2.upp</option>
						<option value="stc300bl.upp">stc300bl.upp</option>
						<option value="stc300bm.upp">stc300bm.upp</option>
						<option value="stc300.upp">stc300.upp</option>
						<option value="stc500p.upp">stc500p.upp</option>
						<option value="stc500ph.upp">stc500ph.upp</option>
						<option value="stc600ih.upp">stc600ih.upp</option>
						<option value="stc600p.upp">stc600p.upp</option>
						<option value="stc600pl.upp">stc600pl.upp</option>
						<option value="stc640p.upp">stc640p.upp</option>
						<option value="Stc670p.upp">Stc670p.upp</option>
						<option value="Stc670pl.upp">Stc670pl.upp</option>
						<option value="Stc680p.upp">Stc680p.upp</option>
						<option value="Stc680pl.upp">Stc680pl.upp</option>
						<option value="stc740ih.upp">stc740ih.upp</option>
						<option value="stc740p.upp">stc740p.upp</option>
						<option value="stc740pl.upp">stc740pl.upp</option>
						<option value="Stc760p.upp">Stc760p.upp</option>
						<option value="Stc760pl.upp">Stc760pl.upp</option>
						<option value="Stc777p.upp">Stc777p.upp</option>
						<option value="Stc777pl.upp">Stc777pl.upp</option>
						<option value="stc800ih.upp">stc800ih.upp</option>
						<option value="stc800p.upp">stc800p.upp</option>
						<option value="stc800pl.upp">stc800pl.upp</option>
						<option value="stc1520h.upp">stc1520h.upp</option>
						<option value="stcany.upp">stcany.upp</option>
						<option value="stc_h.upp">stc_h.upp</option>
						<option value="stc_l.upp">stc_l.upp</option>
						<option value="stcolor">stcolor</option>
						<option value="Stp720p.upp">Stp720p.upp</option>
						<option value="Stp720pl.upp">Stp720pl.upp</option>
						<option value="Stp870p.upp">Stp870p.upp</option>
						<option value="Stp870pl.upp">Stp870pl.upp</option>
						<option value="t4693d2">t4693d2</option>
						<option value="t4693d4">t4693d4</option>
						<option value="t4693d8">t4693d8</option>
						<option value="tek4696">tek4696</option>
						<option value="xes">xes</option>
					</select>  
						OR  
					<input type="text" size="16" tabindex="34" name="dname1"/></td> 
				<td><p>A driver known to work. </p></td>
			</tr> 			
			<tr bgcolor="#efefef">
				<td align="right">Driver notes:</td> 
				<td><textarea cols="35" rows="4" tabindex="35" name="dcomment0"></textarea></td> 
				<td><p>Comments on using the above driver with this printer.</p></td>
			</tr> 
			<tr bgcolor="#efefef">
				<td align="right"> </td> 
				<td><input type="submit" value="Add driver" name="action"/></td> 
				<td><p>Click here to get the driver into a separate place, so that you can add another driver.</p></td>
			</tr>
			<tr bgcolor="#dfdfdf">
				<td align="right">Un*x URL:</td> 
				<td><input type="text" size="32" tabindex="36" name="contrib_url"/></td> 
				<td><p>Web address for important info about using this printer with Unix-like 
					operating systems/free software; website with special tricks, mini-HOWTO, 
					a user's experience, or whatever else helps to make it going. Do not forget the 
					"http://" in the beginning of the address.</p></td>
			</tr> 
			<tr bgcolor="#efefef">
					<td valign="top" align="right">Notes:</td> 
					<td colspan="2"><p><font size="-1" color="#202020">This is HTML that just gets pasted
					into the table; <font color="#000000">watch those &lt;
					signs!</font> Anything big can be
					either linked to with the Un*x
					URL/More Info link, or you can mail us
					the comments to set up as a locally
					served page.</font></p>
					</td>
				</tr> 
				<tr bgcolor="#efefef">
					<td> </td> 
					<td colspan="2"><textarea cols="50" rows="10" tabindex="37" name="notes"/></textarea></td>
				</tr> 
				<tr bgcolor="#dfdfdf">
					<td valign="top" align="right">Auto-detect:</td> 
					<td colspan="2"><p><font size="-1">Auto-detection info for this printer (if you 
					have the possibility to connect your printer in different ways, 
					try all to gather as much auto-detection info as possible), 
					do not fill this in if you are not able to get this data from 
					the actual printer:</font></p><p><font size="-1"><b>Parallel port:</b> 
					Parport probe information for this printer. These should be exactly the 
					contents of the lines from /proc/parport/#/autoprobe (kernel 2.2.x) or 
					/proc/sys/dev/parport/parport#/autoprobe* (kernel 2.4.x and 2.6.x). 
					"#" is the parallel port number; ie typically 0, "*" can be nothing or a 
					number.  Remove the leading MODEL:, MANUFACTURER:, DESCRIPTION:, 
					and COMMAND SET:, and also remove the ending semicolon.  
					If you had <tt>MODEL: Stylus Color 670;</tt>, for example, 
					you'd put <tt>Stylus Color 670</tt> in the MODEL field here.</font></p>
					<p><font size="-1"><b>USB:</b> 
					Download the "<a href="/download/printing/getusbprinterid.pl">getusbprinterid.pl</a>" 
					Perl script, make it executable ("chmod a+rx getusbprinterid.pl"), 
					and then run (as "root") "./getusbprinterid.pl /dev/usb/lp0" 
					(or "/dev/usblp0", "/dev/usb/lp1", or whatever the USB device 
					file to access your printer is). If your printer is configured 
					with <a href="http://hpoj.sf.net/">HPOJ</a> use the "ptal-devid" command. 
					You will get the so-called device ID string as output. Cut and paste this into 
					the "IEEE-1284 Device ID String" field. Take care that all is on one line in the field. 
					Put also the elements of the IEEE string into the appropriate fields "MANUFACTURER/MFG", 
					"MODEL/MDL", ...</font></p><p><font size="-1"><b>Network printer:</b> 
					Auto-detection is done via SNMP (Simple Network Management Protocol). 
					Download and install <a href="http://www.ibr.cs.tu-bs.de/projects/scli/">SCLI</a> 
					and run "scli -c 'show printer info' &lt;host name of the printer&gt;". 
					Look for a "Description:" field in the output. Copy and paste its contents into the 
					"Description" field below.</font></p>
					<p><font size="-1">
						In most cases the IEEE-1284 auto-detection data is the same for USB 
						and parallel port. So usually you should put this data into the "General" 
						section below. If you see any deviations, enter them in the "Parallel Port" and 
						"USB" sections. Leave fields blank if they are identical to the entry in the 
						"General" section, if they are blank, or if they do not exist in your observed 
						auto-detection data.</font></p></td>
				</tr> 
				<tr bgcolor="#dfdfdf">
					<td> </td> 
					<td colspan="2">
						<dl><dt><b>General (Parallel and USB)</b><br/>IEEE-1284 Device ID String</dt> 
						<dd><input type="text" size="50" tabindex="38" name="general_ieee"/></dd> 
						<dt>MANUFACTURER/MFG</dt> <dd><input type="text" size="32" tabindex="39" name="general_mfg"/></dd> 
						<dt>MODEL/MDL</dt> <dd><input type="text" size="32" tabindex="40" name="general_mdl"/></dd> 
						<dt>DESCRIPTION/DES</dt> <dd><input type="text" size="32" tabindex="41" name="general_des"/></dd> 
						<dt>COMMAND SET/CMD</dt> <dd><input type="text" size="32" tabindex="42" name="general_cmd"/></dd> <dt>
							<br/><b>Parallel Port</b>
							<br/>IEEE-1284 Device ID String</dt> 
							<dd><input type="text" size="50" tabindex="43" name="par_ieee"/></dd> 
						<dt>MANUFACTURER</dt> <dd><input type="text" size="32" tabindex="44" name="par_mfg"/></dd> 
						<dt>MODEL</dt> <dd><input type="text" size="32" tabindex="45" name="par_mdl"/></dd> 
						<dt>DESCRIPTION</dt> <dd><input type="text" size="32" tabindex="46" name="par_des"/></dd> 
						<dt>COMMAND SET</dt> <dd><input type="text" size="32" tabindex="47" name="par_cmd"/></dd> 
						<dt><br/><b>USB</b><br/>IEEE-1284 Device ID String</dt> 
						<dd><input type="text" size="50" tabindex="48" name="usb_ieee"/></dd> 
						<dt>MANUFACTURER/MFG</dt> <dd><input type="text" size="32" tabindex="49" name="usb_mfg"/></dd> 
						<dt>MODEL/MDL</dt> <dd><input type="text" size="32" tabindex="50" name="usb_mdl"/></dd> 
						<dt>DESCRIPTION/DES</dt> <dd><input type="text" size="32" tabindex="51" name="usb_des"/></dd> 
						<dt>COMMAND SET/CMD</dt> <dd><input type="text" size="32" tabindex="52" name="usb_cmd"/></dd> 
						<dt><br/><b>Network Printer (SNMP)</b><br/>Description</dt> 
						<dd><input type="text" size="32" tabindex="53" name="snmp_des"/></dd></dl>
					</td>
				</tr>
			</table>
					

<br>
<input type="submit" name="submit" value="Add Printer"> <a href="/printers/upload">Cancel</a>
</form>

</div>
{include file="page_rightcommon.tpl" classtype="two_col_col_2"}
{include file="page_conclusion.tpl"}