<div class="card expandable">
	<div class="card-header">{l s='Devices' mod='clariprint'}</div>
	<div id="devices" class="devices card-block">
		<h4>{l s='Exclude' mod='clariprint'}</h4>
		<textarea name="{$product_key}[excluded_devices]" rows="8" cols="40">{$product->excluded_devices}</textarea>
		<h4>{l s='Force' mod='clariprint'}</h4>
		<textarea name="{$product_key}[force_devices]" rows="8" cols="40">{$product->force_devices}</textarea>
	</div>
</div>