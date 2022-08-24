<section class="featured-categories">
  {foreach from=$categories item="category" key=key}
    {include file="module:featuredcategories/views/templates/hook/featuredcategory.tpl" category=$category k=$key}
  {/foreach}
</section>
