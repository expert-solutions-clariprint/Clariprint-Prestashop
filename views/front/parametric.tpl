{*
* 2007-2013 EXPERT SOLUTIONS
*
* NOTICE OF LICENSE
* ALL RIGHT RESERVED TO EXPERT SOLUTIONS SARL

*  @author Expert Solutions SARL <contact@expert-solutions.fr>
*  @copyright  2013 EXPERT SOLUTIONS SARL
*  @license    proprietary
*}
{assign "parametric_product_key" $product_key}
{assign "parametric_product" $product}
<div class="card expandable removable" role="ParametricController">
	{if $product->name}
	<div class="card-header">{$product->name}</div>
	{/if}
	<div class="card-block clariprint_product">
		<input type="hidden" value="parametric" name="{$product_key}[kind]">
		{if (isset($product->layout) && $product->layout)}
			<input type="hidden" value="{$product->layout}" name="{$product_key}[layout]">
			<div class="row">
			{if isset($product->parameters)}
				{assign "parameters" json_decode($product->parameters)}
			{else}
				{assign "parameters" []}
			{/if}
			{foreach from=$parameters item=param}
				{assign var="param_name" value="{$param->name}"}

				{if isset($param->unit)}
					{if $param->unit=='file'}

					<div class="form-group col-3 col-xs-3 col-xl-3">
						<label for="">{l s=$param->label d="clariprint"}</label>
						<div class="input-group">
							<input class="form-control text-right" 
								type="file" name="{$product_key}[{$param->name}]"
							 	value=""/>
							{if isset($param->unit)}
							<span class="input-group-addon">{$param->unit}</span>
							
							{/if}
						</div>
					</div>
					{else}
					<div class="col-3 col-xs-3 col-xl-3">
						<div class="form-group ">
							<label for="">{l s=$param->label d="clariprint"}</label>
							<div class="input-group">
								<input class="form-control text-right" 
								{if isset($param->min)}min="{$param->min}"{/if}
								{if isset($param->max)}max="{$param->max}"{/if}
								type="text" name="{$product_key}[{$param->name}]"
							 	value="{if isset($product->$param_name)}{$product->$param_name}{elseif isset($param->default)}{$param->default}{/if}"/>
							{if isset($param->unit)}
								<span class="input-group-addon">{$param->unit}</span>
							{/if}
						</div>
					</div>
					</div>
					{/if}
				{elseif isset($param->default->kind)}
					{getParametricTemplate kind=$param->default->kind}
					{if $parametric_tpl}
					<div class="col-12 col-xs-12">
						{assign "product_key" "$parametric_product_key[{$param->name}]"}
						{assign "param_pro" $param->name}
						{assign "product" $product->$param_pro->value}
						{assign "clariprint_card_label" $param->label}
						<input type="hidden" name="{$product_key}[kind]" value="{$param->default->kind}">

						{assign "product_key" "$parametric_product_key[{$param->name}][value]"}

						{include file="./$parametric_tpl"}
						{assign "product_key" "$parametric_product_key"}
						{assign "product" $parametric_product}
						{assign "clariprint_card_label" null}
					</div>
					{else}
						no  template for {$param->default->kind} 

					{/if}
				{elseif isset($param->default->kind) && $param->default->kind == 'select'}
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
				{/if}
			{/foreach}
			</div>

		{else}
		<div>
			<label>Choisir le modèle</label>
			<select name="{$product_key}[layout]" role="layout_selector">
				<option></option>
				{foreach from=$layouts item=layout}
				<option value="{$layout.uuid}" {if isset($product->layout) && ($product->layout == $layout.uuid)}selected{/if}>{$layout.name}</option>
				{/foreach}
			</select>
		</div>
		{/if}
		<div class="flex">
			<button class="btn" type="button" role="Show3D">Show 3D</button>
			<button class="btn" type="button" role="ShowModel">Show Model</button>
		</div>
		<div class="" role="ParametricOutput"></div>
		<script src="/modules/clariprint/js/xl_3D_sampler.js"></script>
		<script src="/modules/clariprint/js/gl-matrix-min.js"></script>
		

		{if _PS_DEBUG_}
			<pre>
				{$product|json_encode:JSON_PRETTY_PRINT}
			</pre>
		{/if}
	</div>
</div>
<script type="text/javascript">
	/* jQuery(function(){
		console.log('load parametricController');
		jQuery.widget('clariprint.parametricController',{
			$layout_selector:null,
			_create:function()
			{
				console.log('Create parametricController');
				console.log(this);

				this.$layout_selector = this.element.find('select[role=layout_selector]');
				this.$layout_selector.change($.proxy(this.changeLayout,this));

			},
			changeLayout:function(evt)
			{
				$.ajax({
					url:Clariprint.productController,
					datas:{
						ajax:1,
						action:'GetParametricLayout',
						layout:this.$layout_selector.val()
					},
					success:function(evt)
					{


					}
				})
				console.log(this.$layout_selector.val());
				console.log(Clariprint.productController);
			}

		});
		$('[role=ParametricController]').parametricController();
	});
	*/

</script>