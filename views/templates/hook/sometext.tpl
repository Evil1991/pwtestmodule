<div class="footer-block">
  <p>
    {if isset($sometext) && $sometext}
      {$sometext}
    {else}
      {l s='Hello World!' mod='pwtestmodule'}Привет
    {/if}
  </p>
  <a href="{$frontcontrollerlink}">{l s='Click this link to see what happen' mod='pwtestmodule'}</a>
</div>