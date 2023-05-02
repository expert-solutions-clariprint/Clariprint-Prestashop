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
<div class="card product_card">
<h2 class="accordion_header card-header">{if $product->name}{l s=$product->name mod='clariprint'}{else}{l s='Leaflet' mod='clariprint'}{/if}
{if $remove_product}<span class="remove_product ui-button-icon ui-icon ui-icon-closethick" style="float: right">{/if}</span>
</h2>{/if}
<div class="clariprint_product {if !$one_product}card-body p-1{/if}">	
	<input type="hidden" value="envelope" name="{$product_key}[kind]">
	{include file='./papers.tpl'}
	{include file='./quantities.tpl'}
	{include file='./models.tpl'}
	{include file='./colors.tpl'}
	{include file='./variables.tpl'}
	{include file='./finishing.tpl'}
	{include file='./makeready.tpl'}
	{include file='./wrapping.tpl'}
</div>
{if !$one_product}
</div>
{/if}