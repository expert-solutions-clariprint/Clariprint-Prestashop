{*
* 2007-2013 EXPERT SOLUTIONS
*
* NOTICE OF LICENSE
* ALL RIGHT RESERVED TO EXPERT SOLUTIONS SARL

*  @author Expert Solutions SARL <contact@expert-solutions.fr>
*  @copyright  2013 EXPERT SOLUTIONS SARL
*  @license    proprietary
*}
{if $product->options->finishing != 'hidden'}
<div class="card clariprint-finishing">
	<h3 class="card-header">{if isset($clariprint_card_label)}{$clariprint_card_label}{else}{l s='Varnish & finishing' mod='clariprint'}{/if} :</h3>
	<div id="finishing" class="finishing card-block">
	{ClariprintSetupFinishingOptions product=$product}
	{if $product->options->finishing == 'unique'}
		<div class="form-group">
		<label for="">{l s='Front & Back finishing' mod='clariprint'} :</label>
		<select name="{$product_key}[finishing]" class="form-control">
			<option value="">{l s='none' mod='clariprint'}</option>
			{if count($front_inline_varnish) > 0}
			<optgroup label="{l s='inline' mod='clariprint'}">
				{foreach $front_inline_varnish as $kvel => $vel}
				<option value="{$kvel}" {if $kvel == $product->finishing_front}selected{/if}>{l s=$kvel|infoFinishing mod='clariprint'}</option>
				{/foreach}
			</optgroup>
			{/if}
			{if count($front_offline_varnish) > 0}
			<optgroup label="{l s='offline varnishing' mod='clariprint'}">
				{foreach $front_offline_varnish as $kvel => $vel}
				<option value="{$kvel}" {if $kvel == $product->finishing_front}selected{/if}>{$kvel|infoFinishing }</option>
				{/foreach}
			</optgroup>
			{/if}
			{if count($front_varnish_set) > 0}
			<optgroup label="{l s='association de vernis' mod='clariprint'}">
				{foreach $front_varnish_set as $kvel => $vel}
				<option value="{$kvel}" {if $kvel == $product->finishing_front}selected{/if}>{$kvel|infoFinishing}</option>
				{/foreach}
			</optgroup>
			{/if}
		</select>
	</div>
	{elseif $product->options->finishing}
		<div class="form-group">
			<label for="{$product_key}[finishing_front]">{l s='Front finishing' mod='clariprint'} :</label>
			<select name="{$product_key}[finishing_front]" class="custom-select form-control" id="{$product_key}[finishing_front]">
				<option value="">{l s='none' mod='clariprint'}</option>
				{if count($front_inline_varnish) > 0}
				<optgroup label="{l s='inline' mod='clariprint'}">
					{foreach $front_inline_varnish as $kvel => $vel}
					<option value="{$kvel}" {if $kvel == $product->finishing_front}selected{/if}>{l s=$kvel|infoFinishing mod='clariprint'}</option>
					{/foreach}
				</optgroup>
				{/if}
				{if count($front_offline_varnish) > 0}
				<optgroup label="{l s='offline varnishing' mod='clariprint'}">
					{foreach $front_offline_varnish as $kvel => $vel}
					<option value="{$kvel}" {if $kvel == $product->finishing_front}selected{/if}>{$kvel|infoFinishing }</option>
					{/foreach}
				</optgroup>
				{/if}
				{if count($front_varnish_set) > 0}
				<optgroup label="{l s='association de vernis' mod='clariprint'}">
					{foreach $front_varnish_set as $kvel => $vel}
					<option value="{$kvel}" {if $kvel == $product->finishing_front}selected{/if}>{$kvel|infoFinishing}</option>
					{/foreach}
				</optgroup>
				{/if}
			</select>
		</div>
			
		{if $product->options->finishing != 'simpleR'}
		<div class="clear"></div>
		<div class="form-group">
		<label>{l s='Back finishing' mod='clariprint'} :</label>
		<select name="{$product_key}[finishing_back]"  class="custom-select form-control">
			<option value="">{l s='none' mod='clariprint'}</option>
			{if count($back_inline_varnish) > 0}
			<optgroup label="{l s='inline' mod='clariprint'}">
				{foreach $back_inline_varnish as $kvel => $vel}
				<option value="{$kvel}" {if $kvel == $product->finishing_back}selected{/if}>{$kvel|infoFinishing}</option>
				{/foreach}
			</optgroup>
			{/if}
			{if count($back_offline_varnish) > 0}
			<optgroup label="{l s='offline varnishing' mod='clariprint'}">
				{foreach $back_offline_varnish as $kvel => $vel}
				<option value="{$kvel}" {if $kvel == $product->finishing_back}selected{/if}>{$kvel|infoFinishing }</option>
				{/foreach}
			</optgroup>
			{/if}
			{if count($front_varnish_set) > 0}
			<optgroup label="{l s='association de vernis' mod='clariprint'}">
				{foreach $front_varnish_set as $kvel => $vel}
				<option value="{$kvel}" {if $kvel == $product->finishing_back}selected{/if}>{$kvel|infoFinishing}</option>
				{/foreach}
			</optgroup>
			{/if}
		</select>
		</div>
		{/if}
	{else}
		<label>{l s='Front finishing' mod='clariprint'} : {$product->finishing_front|infoFinishing}</label>
		<div class="clear"></div>
		<label>{l s='Back finishing' mod='clariprint'} : {$product->finishing_back|infoFinishing}</label>
		
		<input type="hidden" value="{$product->finishing_front}" name="{$product_key}[finishing_front]"/>
		<input type="hidden" value="{$product->finishing_back}" name="{$product_key}[finishing_back]"/>
	{/if}
	{if $product->options->finishing_info}
	<div class="alert alert-info" role="alert">{$product->options->finishing_info nofilter}</div>
	{/if}
	{if $product->options->finishing_info_cms}
		{displayCMS cms=$product->options->finishing_info_cms}
	{else}
		{displayCMS cms='product-finishing'}
	{/if}
	
</div>
</div>
{else}
	<input type="hidden" value="{$product->finishing_front}" name="{$product_key}[finishing_front]"/>
	<input type="hidden" value="{$product->finishing_back}" name="{$product_key}[finishing_back]"/>
{/if}