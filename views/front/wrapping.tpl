{*
* 2007-2013 EXPERT SOLUTIONS
*
* NOTICE OF LICENSE
* ALL RIGHT RESERVED TO EXPERT SOLUTIONS SARL

*  @author Expert Solutions SARL <contact@expert-solutions.fr>
*  @copyright  2013 EXPERT SOLUTIONS SARL
*  @license    proprietary
*}
{if $product->options->wrapping_mode == 1}
<div class="card">
	<h3 class="accordion_header card-header">{l s='Wrapping' mod='clariprint'} :</h3>
	<div id="wrapping" class="wrapping card-block">

			{for $i=0 to 2}
				{assign var=wrapn value="wrapping_$i"}
				{assign var=available value=$product->options->$wrapn->available}
				{if $product->options->$wrapn->show}
				<div class="row">
						<div class="col-md-3 form-group">
							<label for="{$product_key}[wrapping_{$i}][method]">{l s='kind' mod='clariprint'}</label>
							<select name="{$product_key}[wrapping_{$i}][method]" class="form-control" id="{$product_key}[wrapping_{$i}][method]">
								<option value="">{l s='none' mod='clariprint'}</option>
								{foreach $wrapping as $wrap => $txt }
									{if in_array_silent($wrap,$available)||!$available}
									<option value="{$wrap}" {if $product->$wrapn->method == $wrap}selected{/if}>{l s=$txt mod='clariprint' 	}</option>
									{/if}
								{/foreach}
							</select>
						</div>
						<div class="col-md-5 form-group">
							<label for="{$product_key}[wrapping_{$i}][method]">{l s='quantity' mod='clariprint'}</label>
							<input type="text" class="form-control" name="{$product_key}[wrapping_{$i}][quantity]" value="{$product->$wrapn->quantity}" placeholder="{l s='auto' mod='clariprint'}"/>
						</div>
						<div class="col-md-4 form-group">
							<label for="{$product_key}[wrapping_{$i}][etiket]">{l s='Etiquettes' mod='clariprint'}</label>
							<select class="form-control" name="{$product_key}[wrapping_{$i}][etiket]" id="{$product_key}[wrapping_{$i}][etiket]">
								<option value="">{l s='sans étiquettes' mod='clariprint'}</option>
								<option value="1" {if $product->$wrapn->etiket}selected{/if}>{l s='avec étiquettes d\'identification' mod='clariprint'}</option>
							</select>

						</div>

				</div>
				{else}
					<input type="hidden" name="{$product_key}[wrapping_{$i}][method]" value="{$product->$wrapn->method}"/>
					<input type="hidden" name="{$product_key}[wrapping_{$i}][quantity]" value="{$product->$wrapn->quantity}"/>
				{/if}
			{/for}
	{if $product->options->wrapping_info}
	<div class="alert alert-info" role="alert">{$product->options->wrapping_info nofilter}</div>
	{/if}
	{if $product->options->wrapping_info_cms}
		{displayCMS cms=$product->options->wrapping_info_cms}
	{else}
		{displayCMS cms='product-wrapping'}
	{/if}
	
</div>
</div>
{else}
	{for $i=0 to 2}
		{assign var=wrapn value="wrapping_$i"}
		<input type="hidden" name="{$product_key}[wrapping_{$i}][method]" value="{$product->$wrapn->method}"/>
		<input type="hidden" name="{$product_key}[wrapping_{$i}][quantity]" value="{$product->$wrapn->quantity}"/>
		<input type="hidden" name="{$product_key}[wrapping_{$i}][etiket]" value="{$product->$wrapn->etiket}"/>
	{/for}
{/if}
