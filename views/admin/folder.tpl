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
	<div class="card-header">{l s='Folder' mod='clariprint'} <input type="text" class="ProductName" name="{$product_key}[name]" value="{$product->name}" placeholder="{l s='nom du produit' mod='clariprint'}" /></div>
	<div class="product_accordion clariprint_product card-block">
		<input type="hidden" value="folder" name="{$product_key}[kind]">
		{include file='./productoptions.tpl'}
		{include file='./quantities.tpl'}
		{include file='./models.tpl'}
		{include file='./dimensions.tpl'}
		{include file='./folderdie.tpl'}
		{include file='./papers.tpl'}
		{include file='./colors.tpl'}
		{include file='./finishing.tpl'}
		{include file='./makeready.tpl'}
		{include file='./wrapping.tpl'}
	</div>
</div>