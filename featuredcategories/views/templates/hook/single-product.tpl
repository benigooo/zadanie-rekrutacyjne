<div class="product" {if $key > 2}style="display: none;"{/if} data-element-id="{$key}">
    <div class="product__image">
        <a href="{$product.link}"><img src="{$product.default_image.bySize.medium_default.url}" alt="{$product.name}"></a>
    </div>
    <div class="product__name">
        <a href="{$product.link}">{$product.name|truncate:40:'...'}</a>
    </div>
    <div class="product__action">
        <div class="product__price">
            {$product.price}
        </div>
        <div class="product-actions js-product-actions">
            <form action="{$urls.pages.cart}" method="post" id="add-to-cart-or-refresh">
                <input type="hidden" name="token" value="{$static_token}">
                <input type="hidden" name="id_product" value="{$product.id}" id="product_page_product_id">
                <input type="hidden" name="id_customization" value="{$product.id_customization}" id="product_customization_id" class="js-product-customization-id">

                {block name='product_add_to_cart'}
                    {include file='module:featuredcategories/views/templates/hook/simple-add-to-cart.tpl'}
                {/block}

                {block name='product_refresh'}{/block}
            </form>
        </div>
    </div>
</div>
