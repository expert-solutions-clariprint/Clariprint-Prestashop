{*
* 2007-2013 EXPERT SOLUTIONS
*
* NOTICE OF LICENSE
* ALL RIGHT RESERVED TO EXPERT SOLUTIONS SARL

*  @author Expert Solutions SARL <contact@expert-solutions.fr>
*  @copyright  2013 EXPERT SOLUTIONS SARL
*  @license    proprietary
*}

{if !$one_product}
<div class="card product-card">
<h2 class="accordion_header card-header">{if $product->name}{l s=$product->name mod='clariprint'}{else}{l s='Folded' mod='clariprint'}{/if}{if $remove_product}<span class="remove_product ui-button-icon ui-icon ui-icon-closethick" style="float: right">{/if}</h2>{/if}
<div class="product_accordion clariprint_product {if !$one_product}card-body p-1{/if}">
	<input type="hidden" value="folded" name="{$product_key}[kind]">
	{include file='./quantities.tpl'}
	{include file='./models.tpl'}
	{include file='./dimensions.tpl'}
	{include file='./folding.tpl'}
	{include file='./papers.tpl'}
	{include file='./colors.tpl'}
	{include file='./variables.tpl'}
	{include file='./finishing.tpl'}
	{include file='./makeready.tpl'}
	{include file='./wrapping.tpl'}
</div>
{if !$one_product}</div>{/if}