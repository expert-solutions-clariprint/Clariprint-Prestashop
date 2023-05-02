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
	<div class="card-header">{l s='Calculation' mod='clariprint'}</div>
	<div id="calculation" class="calculation card-block">
	<fieldset>
		<legend>{l s='Dynamic pricing'}</legend>
		<input type="radio" name="{$product_key}[options][solving]" value="" id="{$product_key}_options_solving" {if !$product->options->solving}checked{/if}/><label for="{$product_key}_options_solving">{l s='System préference' mod='clariprint'}</label>
		<div class="clear"></div>
		<input type="radio" name="{$product_key}[options][solving]" value="dynamic" id="{$product_key}_options_solving_dynamic" {if $product->options->solving == 'dynamic'}checked{/if}/><label for="{$product_key}_options_solving_dynamic">{l s='Dynamic' mod='clariprint'}</label>
		<div class="clear"></div>
		<input type="radio" name="{$product_key}[options][solving]" value="ondemand" id="{$product_key}_options_solving_ondemand" {if $product->options->solving == 'ondemand'}checked{/if}/><label for="{$product_key}_options_solving_ondemand">{l s='On demand' mod='clariprint'}</label>

		<div class="clear"></div>
		<label for="{$product_key}_options_solving_server">{l s='Special server' mod='clariprint'}</label>
		<select name="{$product_key}[solving_server]" id="clariprint_solver_id">
			<option value="">{l s='auto'}</option>

			{foreach from=$direct_servers item=serv}
			<option value="{$serv.id}" {if $serv.id == $product->solving_server}selected{/if}>{$serv.name}</option>
			{/foreach}
		</select>
	</fieldset>
	<fieldset>
		<legend>{l s='Market Place'}</legend>
		<input type="checkbox" name="{$product_key}[options][marketplace]" value="1" id="{$product_key}_options_marketplace" {if $product->options->marketplace}checked{/if}/><label for="{$product_key}_options_marketplace">{l s='Activate marketplace' mod='clariprint'}</label>
		<select name="{$product_key}[solving_marketplace]" id="clariprint_marketplace_id">
			<option value="">{l s='auto'}</option>
			{foreach from=$marketplaces item=serv}
			<option value="{$serv.id}" {if in_array($serv.id,$product->solving_marketplaces)}selected{/if}>{$serv.name}</option>
			{/foreach}
		</select>
	</fieldset>
</div>
</div>
