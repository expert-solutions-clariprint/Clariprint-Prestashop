{*
* 2007-2013 EXPERT SOLUTIONS
*
* NOTICE OF LICENSE
* ALL RIGHT RESERVED TO EXPERT SOLUTIONS SARL

*  @author Expert Solutions SARL <contact@expert-solutions.fr>
*  @copyright  2013 EXPERT SOLUTIONS SARL
*  @license    proprietary
*}
	<div class="card-block clariprint_product">
		<input type="hidden" value="parametric" name="{$product_key}[kind]">
		<div class="form-group">
			<label>Paramters</label>
			<textarea class="form-control" name="{$product_key}[parameters]">{$parameters|json_encode}</textarea>
		</div>
		<input type="hidden" value="{$parameters|json_encode|escape}" name="{$product_key}[parameters_]">
		{if (isset($product->layout) && $product->layout)}
			{* assign var=pconf value=getParametricConfig($product->layout) *}
			{setupParametricConfig product=$product}
			<input type="hidden" value="{$product->layout}" name="{$product_key}[layout]">
			<div class="row">
			{foreach from=$parameters item=param}
				{assign var="param_name" value="{$param->name}"}
				{if isset($param->unit)}
					{if $param->unit=='file'}
					<div class="form-group col-3">
						<label for="">{l s=$param->label d="clariprint"}</label>
						<div class="input-group">
							<input class="form-control text-right" 
								type="file" name="{$product_key}[{$param->name}]"
							 	value=""/>
							{if isset($param->unit)}
							<div class="input-group-append">
								<span class="input-group-text">{$param->unit}</span>
							</div>
							{/if}
						</div>
					</div>
					{else }
					<div class="form-group col-3">
						<label for="">{l s=$param->label d="clariprint"}</label>
						<div class="input-group">
							<input class="form-control text-right" 
								{if isset($param->min)}min="{$param->min}"{/if}
								{if isset($param->max)}max="{$param->max}"{/if}
								type="text" name="{$product_key}[{$param->name}]"
							 	value="{if isset($product->$param_name)}{$product->$param_name}{elseif isset($param->default)}{$param->default}{/if}"/>
							{if isset($param->unit)}
							<div class="input-group-append">
								<span class="input-group-text">{$param->unit}</span>
							</div>
							{/if}
						</div>
					</div>
					{/if}
				{elseif isset($param->default->kind)}
					{getParametricTemplate kind=$param->default->kind}
					{if $parametric_tpl}
					<div class="col-12">
					{assign "product_key" "$parametric_product_key[{$param->name}]"}
					{assign "param_pro" $param->name}
					{assign "product" $product->$param_pro->value}
						<input type="hidden" name="{$product_key}[kind]" value="{$param->default->kind}">
					{assign "clariprint_card_label" $param->label}
					{assign "product_key" "$parametric_product_key[{$param->name}][value]"}

							{include file="./$parametric_tpl"}
						{assign "product_key" "$parametric_product_key"}
					{assign "product" $parametric_product}
					{assign "clariprint_card_label" null}
					</div>
					{else}
						no  template for {$param->default->kind}
					{/if}
				{elseif $param->default->kind == 'select'}
					<div class="form-group col-4">
						<label for="">{l s=$param->label d="clariprint"}</label>
						<select class="form-control" 
							name="{$product_key}[{$param->name}]">
							{foreach from=$param->options item='opt'}
								<option value="{$opt}">{$opt}</option>
							{/foreach}
						</select>
					</div>
				{elseif is_string($param->default)}
					<div class="form-group col-4">
						<label for="">{l s=$param->label d="clariprint"}</label>
							<input class="form-control" type="text" readonly name="{$product_key}[{$param->name}]"
							value="{$param->default}"/>
					</div>
				{elseif is_array($param->default)}
					<div class="form-group col-4">
						<label for="">{l s=$param->label d="clariprint"}</label>
						{foreach from=$param->default item="pval" key="pkey"}
							<input class="form-control" type="text" readonly name="{$product_key}[{$param->name}][$pkey]"
							value="{$pval}"/>
						{/foreach}
					</div>

				{else}

					{$param->name} ??
					{$param|print_r}
					
				{/if}
			{/foreach}
			</div>
		{/if}
			<pre>
				{$product|json_encode:JSON_PRETTY_PRINT}
			</pre>
	</div>