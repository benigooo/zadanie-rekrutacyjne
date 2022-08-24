<div class="product-add-to-cart js-product-add-to-cart">
    {if !$configuration.is_catalog}
        {block name='product_quantity'}
            <div class="product-quantity clearfix">
                <div class="qty">
                    <input
                            type="number"
                            name="qty"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            {if $product.quantity_wanted}
                                value="{$product.quantity_wanted}"
                                min="{$product.minimal_quantity}"
                            {else}
                                value="1"
                                min="1"
                            {/if}
                            aria-label="{l s='Quantity' d='Shop.Theme.Actions'}"
                    >
                </div>

                <div class="add">
                    <button
                            class="btn btn-primary add-to-cart"
                            data-button-action="add-to-cart"
                            type="submit"
                            {if !$product.add_to_cart_url}
                                disabled
                            {/if}
                    >
                        <i class="material-icons shopping-cart">&#xE547;</i>
                    </button>
                </div>
            </div>
        {/block}
    {/if}
</div>
