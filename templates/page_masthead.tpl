<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<title>{$PAGE->getPageTitle()}</title>
		<link rel="shortcut icon" href="{$BASEURL}images/layout/favicon.png" type="image/x-icon" />
		
		<link href="{$BASEURL}stylesheets/style.css" rel="stylesheet" type="text/css" />
		<link href="{$BASEURL}stylesheets/ajax.css" rel="stylesheet" type="text/css" />
		
		<script src="{$BASEURL}javascript/jquery-1.3.2.js" type="text/javascript" charset="utf-8"></script>
		<link type="text/css" href="{$BASEURL}javascript/themes/cupertino/jquery-ui-1.7.2.custom.css" rel="stylesheet" />	
		<script src="{$BASEURL}javascript/ui/jquery-ui-1.7.2.custom.js" type="text/javascript" charset="utf-8"></script>
		
		<script src="{$BASEURL}javascript/jquery.chainedSelects.js" type="text/javascript" charset="utf-8"></script>
		<script src="{$BASEURL}javascript/ajax.js" type="text/javascript" charset="utf-8"></script>
		
		<script src="{$BASEURL}javascript/swfobject.js" type="text/javascript" charset="utf-8"></script>
		<script src="{$BASEURL}javascript/menu.js" type="text/javascript"></script>
		
		{if $PAGE->getActiveID() == "printer" || $PAGE->getActiveID() == "driver"}
		<script type="text/javascript" src="{$BASEURL}javascript/ui/ui.datepicker.js"></script>
		
			{literal}
			<script type="text/javascript">
			$(function() {
				$("#datepicker").datepicker({ dateFormat: 'yy-mm-dd' });
			});
			</script>
			{/literal}
		{/if}

		{if $PAGE->getActiveID() == "printer"}
		<script type="text/javascript" src="{$BASEURL}javascript/jquery.highlightFade.js"></script>
		
			{literal}
			<script type="text/javascript">
			function addFormField(drselect) {
				var id = document.getElementById("id").value;
			        var str = drselect;
			        str.replace("###", id);
				$("#divTxt").append("<p id='row" + id + "'><label for='dnameNew" + id + "'>Driver:&nbsp;&nbsp;</label>" + str + "<input type='text' size='20' name='dnameNew[]' id='dnameNew" + id + "' />&nbsp;&nbsp;<a href='#' onClick='removeFormField(\"#row" + id + "\"); return false;'>Remove</a><br><label for='dcommentNew" + id + "'>Comment:&nbsp;&nbsp;</label><textarea cols='20' rows='4' name='dcommentNew[]' id='dcommentNew" + id + "'></textarea><br><label for='dppdNew" + id + "'>PPD file URL:&nbsp;&nbsp;</label><input type='text' size='20' name='dppdNew[]' id='dppdNew" + id + "' /><br><label for='recommendedDriver" + id + "'>Recommended Driver:&nbsp;&nbsp;</label><input type='radio' name='recommendedRadio[]' id='recommendedRadio" + id + "' value='1' onclick='addValue(\"#recommendedDriver" + id + "\");' ><input type='hidden' name='recommendedDriver[]' id='recommendedDriver" + id + "' value='0'></p>");
				
				
				$('#row' + id).highlightFade({
					speed:1000
				});
				
				id = (id - 1) + 2;
				document.getElementById("id").value = id;
			}
			
			function removeFormField(id) {
				$(id).remove();
			}
			
			function addValue(id){

			 	$("input[name^='recommendedDriver']").val("0");
				$(""+id+"").val('1');
        //alert(id + " " + $(""+id+"").val());
									
			}
			function subtrValue(){
					//$(""+id+"").val('0');
					// $("input").find("span").css('color','red');
			}
											
			</script>
			{/literal}
		{/if}
				
		{if isset($showTabs) and $showTabs == "1"}
		<script type="text/javascript" src="{$BASEURL}javascript/ui/ui.tabs.js"></script>
			{literal}
			<script type="text/javascript">
        var offset = 1;
				$(function(){
          $("#tabs").tabs({
            load: function(event, ui) { 
              $('a', ui.panel).click(function() { 
                $(ui.panel).load(this.href); 
                return; 
              }); 
            } 
          });
        });
        
			</script>
			{/literal}
		{/if}
		
		
		
	</head>

	<body>
		<div id="loading">Loading ...</div>
		
		<div id="page">
			<div id="page-inner">
				<div id="page-header" class="clearfix">
					<div id="util-nav">
						<ul id="user-nav">
							{if $SESSION->isLoggedIn() }
								<li><a class="list-link login" href="https://identity.linuxfoundation.org/user" >{$SESSION->getUser()->getUserName()}</a></li>
								{if $SHOW_ADMIN_UI }
									<li><a class="list-link flag" href="{$BASEURL}admin/" >Site Admin</a></li>
								{/if}
								<li><a class="list-link logout" href="{$BASEURL}logout" >Logout</a></li>
							{else}
								<li><a class="list-link login" href="{$BASEURL}login" >Login</a></li>
								<li><a class="list-link signup" href="https://identity.linuxfoundation.org/user?destination=cas/login%3Fservice%3Dhttps%253A//www.openprinting.org/printers">Register</a></li>
							{/if}
						</ul>
					</div>
					
					<div id="site-logo">
						<a href="{$BASEURL}"></a>			
					</div>
					
					<div id="lf-logo">
						<a href="{$MAINURL}"></a>
					</div>				
				</div>
				
				<div id="navbar">
					<div id="sub-nav">
						<ul>
						{foreach from=$PAGE->getGNavItems() item=g}
							<li {if $PAGE->getActiveID() == $g->id}class="active"{/if} ><a href="{$g->link}" title="{$g->name|escape:'html'}"><span>{$g->name|escape:'html'}</span></a></li>
						{/foreach}
						</ul>
						<div style="clear: both"></div>
					</div>
					
				</div>
				
				<div id="content">

