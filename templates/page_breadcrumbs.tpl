<p class="breadcrumb">
	{foreach from=$PAGE->getBreadcrumbs() item=b key=bk}
		{if $PAGE->getBreadcrumbsCount() == $bk}
			{$b->name}
		{else}
			<a href="{$b->link}">{$b->name}</a>
		{/if}
	{/foreach}
</p>