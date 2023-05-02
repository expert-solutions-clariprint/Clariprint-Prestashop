{*
* 2007-2013 EXPERT SOLUTIONS
*
* NOTICE OF LICENSE
* ALL RIGHT RESERVED TO EXPERT SOLUTIONS SARL

*  @author Expert Solutions SARL <contact@expert-solutions.fr>
*  @copyright  2013 EXPERT SOLUTIONS SARL
*  @license    proprietary
*}
	{if !$unique_component}
	<div class="card book-component book-leaflet">
		<h3 class="accordion_header card-header">{if $product->name}{$product->name}{else}{l s='Leaflet' mod='clariprint'}{/if}
			<button type="button" class="close" aria-label="Close" role="remove-component">
			<span aria-hidden="true">&times;</span>
		</button></h3>
	<div class="cl_accordion card-body">
		{else}
	{/if}
	{$unique_component}
	<input type="hidden" value="section_leaflet" name="{$product_key}[kind]">
	{include file='./dimensions.tpl'}
	{include file='./colors.tpl'}
	{include file='./finishing.tpl'}
	{include file='./papers.tpl'}
	{if !$unique_component}
	</div>
</div>
	{/if}