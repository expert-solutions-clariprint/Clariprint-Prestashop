<div class="card expandable">
	<div class="card-header">{l s='Options' mod='clariprint'}</div>
	<div class="card-block options">
		<label for="{$product_key}[is_model]">{l s='Use as model :' }</label>
		<input type="checkbox" name="{$product_key}[is_model]" value="1" id="{$product_key}[is_model]" {if $product->is_model}checked{/if}/>
		<div class="clear"></div>					
		<label for="{$product_key}[model_name]">{l s='Product name :' }</label>
		<input type="text" name="{$product_key}[model_name]" value="{$product->model_name}" id="{$product_key}[model_name]"/>
	</div>
</div>