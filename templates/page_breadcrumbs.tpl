<p class="breadcrumb">
	{foreach from=$PAGE->getBreadcrumbs() item=b key=bk}
		{if $PAGE->getBreadcrumbsCount() == $bk}
			{$b->name}
		{else}
			<a href="{$b->link}">{$b->name|escape:'html'}</a>
		{/if}
	{/foreach}
</p>