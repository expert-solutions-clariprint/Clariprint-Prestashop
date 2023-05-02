{*
* 2007-2013 EXPERT SOLUTIONS
*
* NOTICE OF LICENSE
* ALL RIGHT RESERVED TO EXPERT SOLUTIONS SARL

*  @author Expert Solutions SARL <contact@expert-solutions.fr>
*  @copyright  2013 EXPERT SOLUTIONS SARL
*  @license    proprietary
*}
<div class="card expandable removable">
	<div class="card-header">{l s='Enveloppe' mod='clariprint'} : 
		<input type="text" class="ProductName" name="{$product_key}[name]" value="{$product->name}" placeholder="{l s='nom du produit' mod='clariprint'}" />
	</div>

	<div class="card-block product_accordion clariprint_product px-3">
		<input type="hidden" value="envelope" name="{$product_key}[kind]">
		{include file='./productoptions.tpl'}
		{include file='./papers.tpl'}
		{include file='./quantities.tpl'}
		{include file='./models.tpl'}
		{include file='./colors.tpl'}
		{include file='./variables.tpl'}
		{include file='./finishing.tpl'}
		{include file='./makeready.tpl'}
		{include file='./wrapping.tpl'}
	</div>
</div>