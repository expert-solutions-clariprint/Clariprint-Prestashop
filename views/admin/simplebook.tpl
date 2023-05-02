{*
* 2007-2013 EXPERT SOLUTIONS
*
* NOTICE OF LICENSE
* ALL RIGHT RESERVED TO EXPERT SOLUTIONS SARL

*  @author Expert Solutions SARL <contact@expert-solutions.fr>
*  @copyright  2013 EXPERT SOLUTIONS SARL
*  @license    proprietary
*}
	<input type="hidden" value="simplebook" name="{$product_key}[kind]">
	<h4>{l s='Simple book' mod='clariprint'}</h4>
	{include file='./quantities.tpl'}
	{include file='./binding.tpl'}
	{include file='./dimensions.tpl'}
	{include file='./paging.tpl'}
	{include file='./colors.tpl'}
	{include file='./finishing.tpl'}
	{include file='./papers.tpl'}
	{include file='./makeready.tpl'}

	<fieldset>
		<h3 class="accordion_header">{l s='Cover' mod='clariprint'}</h3>
		<div class="cover">
			{include file='./colors.tpl'}
			{include file='./finishing.tpl'}
			{include file='./papers.tpl'}
			{include file='./makeready.tpl'}

			{assign var=main_product_key value=$product_key}
			{assign var=product_key value="$main_product_key[cover]"}
			{assign var=main_product value=$product}
			{assign var=product value=$product->cover}
			{include file='./cover.tpl'}
			{assign var=product_key value=$main_product_key}
			{assign var=product value=$main_product}
		</div>
	</fieldset>
