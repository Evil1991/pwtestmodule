{extends file='page.tpl'} 

{block name='page_content'}
    {if isset($text) && $text}
        {$text}
    {/if}
{/block}

