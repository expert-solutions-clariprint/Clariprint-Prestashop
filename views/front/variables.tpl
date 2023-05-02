{if $product->options->show_variables}
<div class="card ClariprintColorWidget clariprint-variables">
	<h3 class="card-header">{l s='Variables' mod='clariprint'}</h3>
	<div class="card-block variables">
		<h4>{l s='Recto' mod='clariprint'}
		<div class="form-group">
			<label for="front_black_vars">{l s='count of variables printed in black' mod='clariprint'}</label>
			<input type="number" class="form-control" id="front_black_vars" name="{$product_key}[front_black_vars]" value="{$product->front_black_vars}" />
		</div>	
		<div class="form-group">	
			<label for="front_4colors_vars">{l s='count of variables printed in 4-color' mod='clariprint'}</label>
			<input type="number" class="form-control" id="front_4colors_vars" name="{$product_key}[front_4colors_vars]" value="{$product->front_4colors_vars}" placholder="" />
		</div>	
		<hr>
		<h4>{l s='Verso' mod='clariprint'}
		<div class="form-group">
			<label for="back_black_vars">{l s='count of variables printed in black' mod='clariprint'}</label>
			<input type="number" class="form-control" id="back_black_vars" name="{$product_key}[back_black_vars]" value="{$product->back_black_vars}" />
		</div>	
		<div class="form-group">
			<label for="back_4colors_vars">{l s='count of variables printed in 4-color' mod='clariprint'}</label>
			<input type="number" class="form-control" id="back_4colors_vars" name="{$product_key}[back_4colors_vars]" value="{$product->back_4colors_vars}" />
		</div>			
		<hr>
	<div class="clear"></div>
	
	{if $product->options->variables_info}
	<div class="alert alert-info" role="alert">{$product->options->variables_info nofilter}</div>
	{/if}
	{if $product->options->variables_info_cms}
		{displayCMS cms=$product->options->variables_info_cms}
	{else}
		{displayCMS cms='product-variables'}
	{/if}
		
	</div>
</div>
{else}
	<input type="hidden" name="{$product_key}[front_black_vars]" value="{$product->front_black_vars}"/>
	<input type="hidden" name="{$product_key}[front_4colors_vars]" value="{$product->front_4colors_vars}"/>
	<input type="hidden" name="{$product_key}[back_black_vars]" value="{$product->back_black_vars}" />
	<input type="hidden" name="{$product_key}[back_4colors_vars]" value="{$product->back_4colors_vars}" />
{/if}