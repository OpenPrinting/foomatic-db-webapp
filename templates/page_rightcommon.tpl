	<div id="{$classtype|default:two_col_col_2}">

		<form id="search-block-form" method="post" accept-charset="UTF-8" action="{$MAINURL}/">
			<div>
				<label style="display: none" for="edit-search-block-form-1">Search this site: </label>
				<input type="text" class="form-text" title="Enter the terms you wish to search for." value="" size="15" id="edit-search-block-form-1" name="search_block_form" maxlength="128"/>
				<input type="submit" class="form-submit" value="Search" id="edit-submit" name="op"/>
				<input type="hidden" value="form-26fbc938e83ac4b4c2b953d986702f36" id="form-26fbc938e83ac4b4c2b953d986702f36" name="form_build_id"/>
				<input type="hidden" value="search_block_form" id="edit-search-block-form" name="form_id"/>
			</div>
		</form>	
	
		{if $isLoggedIn == "1"}
		        <div class="section">
		        	<h4 class="color_site_1 b_color_site_1">My Account</h4>
					<div>
						<ul>
							<li> <a href="{$BASEURL}account/myuploads" >My Uploads</a></li>
							<li> <a href="{$BASEURL}drivers/upload" >Upload New Driver</a></li>
							<li> <a href="{$BASEURL}printers/upload" >Upload New Printer</a></li>

							{if $isAdmin }
							<li><a href="{$BASEURL}admin/queue" >Queue Administration</a></li>
							<li><a href="{$BASEURL}admin/roleadmin" >Roles Administration</a></li>
							{/if}
						</ul>
						<br>
					</div>
		        </div>
		{/if}

        <div class="section">
        	<!--<h4>Resources</h4>
            <ul class="list_none left halfwidth">
            	
            	<li><a class="list-link none" href="#">gravida</a></li>
                <li><a class="list-link none" href="#">consequat</a></li>

                <li><a class="list-link none" href="#">pellentesque</a></li>
            </ul>
            <ul class="list_none left halfwidth">
            	
            	<li><a class="list-link none" href="#">gravida</a></li>
                <li><a class="list-link none" href="#">consequat</a></li>
                <li><a class="list-link none" href="#">pellentesque</a></li>
            </ul>-->
			<div style="border: 1px solid #ccc;">
			<script type='text/javascript'><!--//<![CDATA[
			   var m3_u = (location.protocol=='https:'?'https://ads.linuxfoundation.org/www/delivery/ajs.php':'http://ads.linuxfoundation.org/www/delivery/ajs.php');
			   var m3_r = Math.floor(Math.random()*99999999999);
			   if (!document.MAX_used) document.MAX_used = ',';
			   document.write ("<scr"+"ipt type='text/javascript' src='"+m3_u);
			   document.write ("?zoneid=36");
			   document.write ('&amp;cb=' + m3_r);
			   if (document.MAX_used != ',') document.write ("&amp;exclude=" + document.MAX_used);
			   document.write (document.charset ? '&amp;charset='+document.charset : (document.characterSet ? '&amp;charset='+document.characterSet : ''));
			   document.write ("&amp;loc=" + escape(window.location));
			   if (document.referrer) document.write ("&amp;referer=" + escape(document.referrer));
			   if (document.context) document.write ("&context=" + escape(document.context));
			   if (document.mmm_fo) document.write ("&amp;mmm_fo=1");
			   document.write ("'><\/scr"+"ipt>");
			//]]>--></script><noscript><a href='http://ads.linuxfoundation.org/www/delivery/ck.php?n=afa953c1&amp;cb=INSERT_RANDOM_NUMBER_HERE' target='_blank'><img src='http://ads.linuxfoundation.org/www/delivery/avw.php?zoneid=36&amp;cb=INSERT_RANDOM_NUMBER_HERE&amp;n=afa953c1' border='0' alt='' /></a></noscript>
			</div>
			<br>
        </div>
			
        <div class="section">
        	<h4>News &amp; announcements 
				<a href="http://forums.freestandards.org/rss.php?21"><img src="{$BASEURL}images/icons/rss.png" alt="RSS" title="RSS" /></a>
			</h4>
			
			<div class="section">
			{foreach from=$AnnouncementsRSS item=i}
				<p>
					<a href="{$i.link}">{$i.title}</a><br /><small>({$i.pubdate} by {$i.author})</small>
				</p>
				 
			{/foreach}
			</div>
			
            {*<ul>
            	<li><a class="list-link-2 youtube" href="#">Linux Foundation Channel</a></li>
                <li><a class="list-link-2 twitter" href="#">Follow us on Twitter</a></li>
                <li><a class="list-link-2 facebook" href="#">Linux Foundation Group on Facebook</a></li>
                <li><a class="list-link-2 rss" href="#">Linux Foundation News Feeds</a></li>

            </ul>*}
        </div>

        <div class="section">
        	<h4 class="color_site_1 b_color_site_1">Latest Comments</h4>
			<div>
				{literal}
					<!--<script src='http://www.intensedebate.com/widgets/acctComment/164938/5' defer="defer" type='text/javascript'> </script>-->
					<div id="recentcomments" class="dsq-widget"><script type="text/javascript" src="http://disqus.com/forums/openprintingorg/recent_comments_widget.js?num_items=5&hide_avatars=0&avatar_size=32&excerpt_length=200"></script></div><a href="http://disqus.com/">Powered by Disqus</a>
				{/literal}
			</div>
        </div>
		

	</div>
