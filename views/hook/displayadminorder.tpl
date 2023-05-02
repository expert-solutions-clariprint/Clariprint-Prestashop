{*
* 2014 EXPERT SOLUTIONS SARL
*
*  @author EXPERT SOLUTIONS SARL <contact@expert-solutions.fr>
*  @copyright  2014 Expert Solutions sarl
*  @license    PROPRIETARY
*}

<fieldset>
	<legend>{l s='Dossiers de fabrications' mod='clariprint'}</legend>
	<table id="clariprint_process" class="std">
		<thead>
			<tr>
				<th class="cart_product first_item">{l s='Product' mod='clariprint'}</th>
				<th class="cart_description item">{l s='Description' mod='clariprint'}</th>
				<th class="cart_availability item">{l s='File' mod='clariprint'}</th>
			</tr>
		</thead>
		<tfoot>
		<tbody>
		{foreach from=$products item=product name=productLoop key=attid}
			{assign var='productId' value=$product.id_product}
			{assign var='odd' value=$product@iteration%2}
			{* Display the product line *}
			<tr id="od_{$product.id_order_detail}"
				class="cart_item{if isset($productLast) && $productLast && (!isset($ignoreProductLast) || !$ignoreProductLast)} last_item{/if}{if isset($productFirst) && $productFirst} first_item{/if} address_{$product.id_address_delivery|intval} {if $odd}odd{else}even{/if}"
				clariprint_order_detail_id="{{$product.id_order_detail}}">
				<td class="cart_description">
					<p class="s_title_block"><a href="{$link->getProductLink($product.id_product, $product.link_rewrite, $product.category, null, null, $product.id_shop, $product.id_product_attribute)|escape:'htmlall':'UTF-8'}">{$product.product_name|escape:'htmlall':'UTF-8'}</a></p>
				</td>
				<td class="cart_ref">{if $product.reference}{$product.reference|escape:'htmlall':'UTF-8'}{else}--{/if}</td>
				<td class="files">
						<a href="{$download_url}&processfile={$productId}">
							<img src="/modules/clariprintattachments/img/pdf.png" /><br>
						</a>
						
				</td>
			</tr>
			{* fin produit *}
		{/foreach}
		</tbody>
	</table>
</fieldset>
