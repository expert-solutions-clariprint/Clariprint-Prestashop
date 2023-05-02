{*
* 2007-2013 EXPERT SOLUTIONS
*
* NOTICE OF LICENSE
* ALL RIGHT RESERVED TO EXPERT SOLUTIONS SARL

*  @author Expert Solutions SARL <contact@expert-solutions.fr>
*  @copyright  2013 EXPERT SOLUTIONS SARL
*  @license    proprietary
*}
<div class="card expandable">
	<div class="card-header">{l s='Leaflet' mod='clariprint'} : <input type="text" class="ProductName" name="{$product_key}[name]" value="{$product->name}" placeholder="{l s='nom du produit' mod='clariprint'}" /><span class="remove_product ui-button-icon ui-icon ui-icon-closethick" style="float: right"></span></div>
	<div id="div_{$product_id}" class="component card-block">
		<input id="input_{$product_id}" type="hidden" value="section_leaflet" name="{$product_key}[kind]">
		{include file='./productoptions.tpl'}
		{include file='./dimensions.tpl'}
		{include file='./colors.tpl'}
		{include file='./papers.tpl'}
		{include file='./finishing.tpl'}
		{include file='./makeready.tpl'}
	</div>
</div>