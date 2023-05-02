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
<div class="card">
	<h3 class="accordion_header card-header">{if $product->name}{$product->name}{else}{l s='Section' mod='clariprint'}{/if}
		<button type="button" class="close" aria-label="Close" role="remove-component">
			<span aria-hidden="true">&times;</span>
		</button>
	</h3>
	<div class="cl_accordion card-block">
	{/if}
	<input type="hidden" value="section" name="{$product_key}[kind]">
	{include file='./paging.tpl'}
	{include file='./dimensions.tpl'}
	{include file='./papers.tpl'}
	{include file='./colors.tpl'}
	{include file='./variables.tpl'}
	{include file='./finishing.tpl'}
	{include file='./makeready.tpl'}
	{if !$unique_component}
	</div>
</div>
	{/if}
	