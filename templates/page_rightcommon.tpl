	<div id="{$classtype|default:two_col_col_2}">

		<form id="search-block-form" method="post" accept-charset="UTF-8" action="/">
			<div>
				<label style="display: none" for="edit-search-block-form-1">Search this site: </label>
				<input type="text" class="form-text" title="Enter the terms you wish to search for." value="" size="15" id="edit-search-block-form-1" name="search_block_form" maxlength="128"/>
				<input type="submit" class="form-submit" value="Search" id="edit-submit" name="op"/>
				<input type="hidden" value="form-26fbc938e83ac4b4c2b953d986702f36" id="form-26fbc938e83ac4b4c2b953d986702f36" name="form_build_id"/>
				<input type="hidden" value="search_block_form" id="edit-search-block-form" name="form_id"/>
			</div>
		</form>	
			
        <div class="section">
        	<h4 class="color_site_1 b_color_site_1">Sponsored by</h4>
			<div style="text-align: center">
				<a href="http://www.hp.com"><img src="{$BASEURL}images/hp.png" alt="HP" title="HP" /></a>
			</div>
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
        	<h4>Recently updated drivers</h4>
			
			<ul>
				<li>News item 1</li>
				<li>News item 2</li>
				<li>News item 3</li>
			</ul>
			
            {*<ul class="list_none">
            	<li><a class="list-link-2 youtube" href="#">Linux Foundation Channel</a></li>
                <li><a class="list-link-2 twitter" href="#">Follow us on Twitter</a></li>
                <li><a class="list-link-2 facebook" href="#">Linux Foundation Group on Facebook</a></li>
                <li><a class="list-link-2 rss" href="#">Linux Foundation News Feeds</a></li>

            </ul>*}
        </div>
        
        <div class="section">
        	<h4>Resources</h4>
            <ul class="list_none left halfwidth">
            	
            	<li><a class="list-link none" href="#">gravida</a></li>
                <li><a class="list-link none" href="#">consequat</a></li>

                <li><a class="list-link none" href="#">pellentesque</a></li>
            </ul>
            <ul class="list_none left halfwidth">
            	
            	<li><a class="list-link none" href="#">gravida</a></li>
                <li><a class="list-link none" href="#">consequat</a></li>
                <li><a class="list-link none" href="#">pellentesque</a></li>
            </ul>

        </div>

	</div>
