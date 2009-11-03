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
		
		<script src="{$BASEURL}javascript/jquery-1.2.6.js" type="text/javascript" charset="utf-8"></script>
		<script src="{$BASEURL}javascript/jquery.chainedSelects.js" type="text/javascript" charset="utf-8"></script>
		<script src="{$BASEURL}javascript/ajax.js" type="text/javascript" charset="utf-8"></script>
		
		<script src="{$BASEURL}javascript/swfobject.js" type="text/javascript" charset="utf-8"></script>
		<script src="{$BASEURL}javascript/menu.js" type="text/javascript"></script>
	</head>

	<body>
		<div id="loading">Loading ...</div>
		
		<div id="page">
			<div id="page-inner">
				<div id="page-header" class="clearfix">
					<div id="util-nav">
						<div id="lf-link">
							<a title="Linux Foundation - US" href="http://www.linuxfoundation.org">
								<img src="{$BASEURL}images/icons/flag_us.gif" alt="Linux Foundation - US" title="" />
							</a>
							<a title="Linux Foundation - Japan" href="http://www.linuxfoundation.jp">
								<img src="{$BASEURL}images/icons/flag_jp.gif" alt="Linux Foundation - Japan" title="" />
							</a>
							<a href="http://www.linuxfoundation.org">LinuxFoundation.org</a>
						</div>
						<ul id="lf-more-contain">
							<li><a href="http://www.linux.com">Linux.com</a></li>
							<li><a href="http://events.linuxfoundation.org">Events</a></li>
							<li><a href="http://video.linuxfoundation.org">Video</a></li>
							<li><a onmouseout="delayhidemenu()" onclick="return dropdownmenu(this, event, menu2, '100px')" id="lf-more" href="#">More</a></li>
						</ul>
						<ul id="user-nav">
							{if $SESSION->isLoggedIn() }
								<li><a class="list-link login" href="#" >{$USER->getUserName()}</a></li>
								{if $SHOW_ADMIN_UI }
									<li><a class="list-link flag" href="{$BASEURL}admin/" >Site Admin</a></li>
								{/if}
								<li><a class="list-link logout" href="{$BASEURL}logout" >Logout</a></li>
							{else}
								<li><a class="list-link login" href="{$BASEURL}login" >Login</a></li>
								<li><a class="list-link signup" href="https://www.linuxfoundation.org/user/register?destination=home">Register</a></li>
							{/if}
						</ul>
					</div>
					
					<div id="site-logo">
						<a href="{$BASEURL}"></a>			
					</div>
					
					<div id="lf-logo">
						<a href="http://linuxfoundation.org"></a>
					</div>				
				</div>
				
				<div id="navbar">
					<div id="primary-nav">
						<ul>
							<li><a href="{$MAINURL}/" id="activetab" title="Home"><span>Home</span></a></li>
							<li><a href="{$MAINURL}/about" title="About Us"><span>About Us</span></a></li>
							<li><a href="{$MAINURL}/news-media" title="News &amp; Media"><span>News &amp; Media</span></a></li>
							<li><a href="{$MAINURL}/programs" title="Programs"><span>Programs</span></a></li>
							<li><a href="{$MAINURL}/collaborate" title="Collaborate"><span>Collaborate</span></a></li>
							<li><a href="{$MAINURL}/participate" title="Participate"><span>Participate</span></a></li>
							<li><a href="{$MAINURL}/events" title="Events"><span>Events</span></a></li>
							<li><a href="{$MAINURL}/linux-training" title="Training"><span>Training</span></a></li>
						</ul>
					</div>
					
					<div id="sub-nav">
						<ul>
						{foreach from=$PAGE->getGNavItems() item=g}
							<li {if $PAGE->getActiveID() == $g->id}class="active"{/if} ><a href="{$g->link}" title="{$g->name}"><span>{$g->name}</span></a></li>
						{/foreach}
						</ul>
						<div style="clear: both"></div>
					</div>
					
				</div>
				
				<div id="content">

