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
	{if isset($product->parameters)}
		{assign "parameters" json_decode($product->parameters)}
	{/if}
	{include file="./parametric_content.tpl"}
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