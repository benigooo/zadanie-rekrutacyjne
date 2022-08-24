<div class="featured-category products-{$k}" data-count="{$category['products']|count}">
  <h4 class="featured-category--title">{$category['name']}</h4>
  {if $category['products']|count > 2}
  <div class="featured-category--swipe-icons">
    <span id="swipe-up-{$k}" class="swipe-up"></span>
    <span id="swipe-down-{$k}" class="swipe-down"></span>
  </div>
  {/if}
  <div class="products products-{$k}">
    {foreach from=$category['products'] item="product" key=key}
      {include file="module:featuredcategories/views/templates/hook/single-product.tpl" product=$product key=$key}
    {/foreach}
  </div>
  <a href="{$category.link}" class="link-to-category">{l s='więcej z tej kategorii' d='Modules.Featuredcategories.Shop'}</a>
</div>
