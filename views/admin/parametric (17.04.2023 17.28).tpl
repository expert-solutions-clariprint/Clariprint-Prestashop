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
<div class="card expandable removable" role="ParametricController" product_key="{$product_key}">
	<div class="card-header">{l s='Parametric Product(s)' mod='clariprint'} <input type="text" class="ProductName" name="{$product_key}[name]" value="{$product->name}" placeholder="{l s='nom du produit' mod='clariprint'}" /></div>
	<div class="card-block">
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text" id="basic-addon1">Choisir le modèle :</span>
			</div>
			<select class="form-control" name="{$product_key}[layout]" role="layout_selector">
				{foreach from=$layouts item=layout}
				<option value="{$layout}" {if isset($product->layout) && ($product->layout == $layout)}selected{/if}>{$layout}</option>
				{/foreach}
			</select>
			<span class="input-group-btn">
				<button role="choose_layout" class="btn btn-primary">Choisir</button>
			</span>
		</div>
	</div>

	<div class="card-block clariprint_product">
		<input type="hidden" value="parametric" name="{$product_key}[kind]">
		{if (isset($product->layout) && $product->layout)}
			{* assign var=pconf value=getParametricConfig($product->layout) *}
			{setupParametricConfig product=$product}
			<input type="hidden" value="{$product->layout}" name="{$product_key}[layout]">
			<div class="row">
			{foreach from=$product->config.parameters item=param}
				{assign var="param_name" value="{$param.name}"}
				{if isset($param.unit)}
					{if $param.unit=='file'}
					<div class="form-group col-3">
						<label for="">{l s=$param.label d="clariprint"}</label>
						<div class="input-group">
							<input class="form-control text-right" 
								type="file" name="{$product_key}[{$param.name}]"
							 	value=""/>
							{if isset($param.unit)}
							<div class="input-group-append">
								<span class="input-group-text">{$param.unit}</span>
							</div>
							{/if}
						</div>
					</div>
					{else }
					<div class="form-group col-3">
						<label for="">{l s=$param.label d="clariprint"}</label>
						<div class="input-group">
							<input class="form-control text-right" 
								{if isset($param.min)}min="{$param.min}"{/if}
								{if isset($param.max)}max="{$param.max}"{/if}
								type="text" name="{$product_key}[{$param.name}]"
							 	value="{if isset($product->$param_name)}{$product->$param_name}{elseif isset($param.default)}{$param.default}{/if}"/>
							{if isset($param.unit)}
							<div class="input-group-append">
								<span class="input-group-text">{$param.unit}</span>
							</div>
							{/if}
						</div>
					</div>
					{/if}
				{elseif isset($param.default.kind)}
					{getParametricTemplate kind=$param.default.kind}
					{if $parametric_tpl}
					<div class="col-12">
					{assign "product_key" "$parametric_product_key[{$param.name}]"}
					{assign "param_pro" $param.name}
					{assign "product" $product->$param_pro->value}
						<input type="hidden" name="{$product_key}[kind]" value="{$param.default.kind}">
					{assign "clariprint_card_label" $param.label}
					{assign "product_key" "$parametric_product_key[{$param.name}][value]"}

							{include file="./$parametric_tpl"}
						{assign "product_key" "$parametric_product_key"}
					{assign "product" $parametric_product}
					{assign "clariprint_card_label" null}
					</div>
					{else}
						no  template for {$param.default.kind} 

					{/if}
				{elseif $param.default.kind == 'select'}
					<div class="form-group col-4">
						<label for="">{l s=$param.label d="clariprint"}</label>
						<select class="form-control" 
							name="{$product_key}[{$param.name}]">
							{foreach from=$param.options item='opt'}
								<option value="{$opt}">{$opt}</option>
							{/foreach}
						</select>
					</div>
				{elseif is_string($param.default)}
					<div class="form-group col-4">
						<label for="">{l s=$param.label d="clariprint"}</label>
							<input class="form-control" type="text" readonly name="{$product_key}[{$param.name}]"
							value="{$param.default}"/>
					</div>
				{elseif is_array($param.default)}
					<div class="form-group col-4">
						<label for="">{l s=$param.label d="clariprint"}</label>
						{foreach from=$param.default item="pval" key="pkey"}
							<input class="form-control" type="text" readonly name="{$product_key}[{$param.name}][$pkey]"
							value="{$pval}"/>
						{/foreach}
					</div>

				{else}
					{$param.name} ??
				{/if}
			{/foreach}
			</div>
		{/if}
			<pre>
				{$product|json_encode:JSON_PRETTY_PRINT}
			</pre>
	</div>
</div>
<script type="text/javascript">
	jQuery(function(){
		console.log('load parametricController');
		jQuery.widget('clariprint.parametricController',{
			$layout_selector:null,
			_create:function()
			{
				console.log('Create parametricController **************** ');
				this.element.find('[role=choose_layout]').click($.proxy(this.changeLayout,this));
				this.$layout_selector = this.element.find('select[role=layout_selector]');
				this.$layout_selector.change($.proxy(this.changeLayout,this));

			},
			changeLayout:function(evt)
			{
				console.log('changeLayout');
				evt.stopImmediatePropagation();
				evt.stopPropagation();
				this.element.find('div.clariprint_product').html('load ...');
				$.ajax({
					type:'POST',
					url:Clariprint.productController,
					data:{
						ajax:1,
						action:'ParametricTemplate',
						product_key: this.element.attr('product_key'),
						layout:this.$layout_selector.val()
					},
					success: 
						$.proxy(this.ajaxSetContent,this)
				});
				console.log(this.$layout_selector.val());
				console.log(Clariprint.productController);
			},
			ajaxSetContent:function(content)
			{
				console.log('ajaxSetContent');
				$e = $(content);
				this.element.append($e);
				Clariprint.setupProduct($e);
			}


		});
		$('[role=ParametricController]').parametricController();
	});

</script>