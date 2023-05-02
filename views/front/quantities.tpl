{if $product->options->quantity != 'hidden'}

<div class="card">

	<h3 class="card-header">{l s='Quantities' mod='clariprint'} :</h3>

	<div id="quantities" class="quantities card-block">

	{if $product->options->quantity == 'free'}
		{if true}
		<div class="input-group">
			<span class="input-group-addon" id="{$product_key}_quantity_txt">{l s='Quantity :' mod='clariprint'}</span>
			<input type="number"
					name="{$product_key}[quantity]"
					value="{$product->quantity}"
					class="CLInt form-control"
					aria-describedby="{$product_key}_quantity_txt"
					cl_max="{$product->options->quantity_to}"
					cl_min="{$product->options->quantity_from}"
					{if $product->options->quantity_from}min="{$product->options->quantity_from}"{/if}
					{if $product->options->quantity_to}max="{$product->options->quantity_to}"{/if}
					{if $product->options->quantity_step}
					step="{(int)($product->options->quantity_step)}"
					{else}
					step="{(int)($product->quantity/10)}"
					{/if} />
			<span class="input-group-addon">{l s='exemplaires' mod='clariprint'}</span>
		</div>
		{else}
		<input type="number" name="{$product_key}[quantity]" class="CLInt" cl_max="{$product->options->quantity_to}" cl_min="{$product->options->quantity_from}" value="{$product->quantity}" id="clariprint_quantity" class="numeric" step="{(int)$product->quantity/10}"/>
		{/if}
		{if $product->options->quantities}
		<select onclick="$('clariprint_quantity').val(this.value);">
			{assign var=quantities value="\n"|explode:$product->options->quantities} 
			{foreach $quantities as $qt}
				<option {if $qt == $product->quantity}selected{/if}>{$qt}</option>
			{/foreach}
		</select>
		{/if}
	{elseif $product->options->quantity == 'list'} 
	<select name="{$product_key}[quantity]">
		{assign var=quantities value="\n"|explode:$product->options->quantities} 
		{foreach $quantities as $qt}
			<option {if $qt == $product->quantity}selected{/if}>{$qt}</option>
		{/foreach}
	</select>
	{elseif $product->options->quantity == 'range'}
	<select name="{$product_key}[quantity]">
	{for $i=$product->options->quantity_from to $product->options->quantity_to step $product->options->quantity_step}
	<option {if $i == $product->quantity}selected{/if}>{$i}</option>
	{/for}
	</select>
	{/if}
	{if $product->options->quantities_info}
	<div class="alert alert-info" role="alert">{$product->options->quantities_info nofilter}</div>
	{/if}
	{if $product->options->quantities_info_cms}
		{displayCMS cms=$product->options->quantities_info_cms}
	{else}
		{displayCMS cms='product-quantity'}
	{/if}
	
</div>
</div>
{else}
	<input type='hidden' name="{$product_key}[quantity]" value="{$product->quantity}" id="clariprint_quantity"/>
{/if}
<script type="text/javascript" charset="utf-8">
	/*
	jQuery(function($){
		jQuery("#quantity_wanted_p").hide();
	});
	*/
</script>