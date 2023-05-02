{*
* 2007-2013 EXPERT SOLUTIONS
*
* NOTICE OF LICENSE
* ALL RIGHT RESERVED TO EXPERT SOLUTIONS SARL

*  @author Expert Solutions SARL <contact@expert-solutions.fr>
*  @copyright  2013 EXPERT SOLUTIONS SARL
*  @license    proprietary
*
* ADMIN 
*}
<div class="card expandable">
	<div class="card-header">{l s='Delivery time' mod='clariprint'}</div>
	<div id="delivery_time" class="card-block">
	{l s='Delivery time' mod='clariprint'}<br>
	
	<label>
		<input name="{$product_key}[options][delivery_time]" 
		placeholder="{l s='delivery time in days ' mod='clariprint'}" value="{$product->options->delivery_time}"/>
		{l s='delivery time in days added to calculated time by Clariprint' mod='clariprint'}</label>
	</div>
</div>