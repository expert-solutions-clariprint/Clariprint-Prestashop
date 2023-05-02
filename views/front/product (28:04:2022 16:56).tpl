{*
* 2007-2013 EXPERT SOLUTIONS
*
* NOTICE OF LICENSE
* ALL RIGHT RESERVED TO EXPERT SOLUTIONS SARL

*  @author Expert Solutions SARL <contact@expert-solutions.fr>
*  @copyright  2013 EXPERT SOLUTIONS SARL
*  @license    proprietary
*}
<script type="text/javascript" charset="utf-8">
	Clariprint_ui = '{$ui_mode}';
	Clariprint_api = '{$api_mode}';
	Clariprint_productController = '{$link->getModuleLink('clariprint', 'book', [], true)}';
</script>
<form class="ClariprintFront ClariprintSolverWidget" id="clariprint_form" product-id="{$clariprint_product_id}">
	{assign var=remove_product value=false}
	<div class="clariprint_products {if $product->options->display == 'accordion'}expandable{/if}">
	{l s="Product configuration" mod='clariprint'}
		{if $product->options->multiproducts}
			{assign var=remove_product value=true}
		<div class="card">
			<h3 class="card-header">{l s='Add product' mod='clariprint'}</h3>
			<div class="card-block row ClariprintProductController ">
			{foreach from=$product->parts item=model key=key}
				{if $model->is_model}
					<button class="add_model" product_id="{$clariprint_product_id}" value="{$key}"><img src="/modules/clariprint/img/products/{$model->kind}.png"/><br/>{if $model->model_name}{$model->model_name}{else}{l s=$model->kind mod='clariprint'}{/if}</button>
				{/if}
			{/foreach}
			</div>
		</div>
		{/if}
		<input type="hidden" value="{$product_name|escape:'htmlall':'UTF-8'}" name="clariprint_product[reference]">
		<input type="hidden" value="{$product_name|escape:'htmlall':'UTF-8'}" name="reference">
		<input type="hidden" value="{$customerName|escape:'htmlall':'UTF-8'}" name="clariprint_product[group]">
		<input type="hidden" value="{$customerName|escape:'htmlall':'UTF-8'}" name="group">
		
		<input type="hidden" value="{$product_name|escape:'htmlall':'UTF-8'}" name="{$product_key}[reference]">
		<input type="hidden" value="" id="clariprint_customization_id"/>
		{if $product->kind == 'multiple'}
			{assign var=one_product value=!$product->options->multiproducts}
			{if count((array)$product->parts) > 1}{assign var=one_product value=0}{/if}
			{assign var=base_product value=$product}
			{assign var=base_key value=$product_key}
			{if isset($product->parts)}
				{foreach from=$base_product->parts item=product key=key}
					{if !$product->is_model}
						{assign var=product_key value="clariprint_product[parts][{$key}]"}
						{include file="./{$product->kind}.tpl"}
					{/if}
				{/foreach}
			{/if}
			<div id="clariprint_insert_product_before_me" style="display:none"></div>
			
			{assign var=product value=$base_product}
			{assign var=product_key value=$base_key}
		{else}
			{assign var=one_product value=true}
			{include file=$product_template}
		{/if}
		{include file='./delivery.tpl'}
		{include file='./proof.tpl'}
		{include file='./delivery_time.tpl'}
		{include file='./discounts.tpl'}
		{include file='./calculation.tpl'}		
	</div>
	<hr>le
</form>
	<div>
		{* displayAllCMS *}
	</div>
{if true}	
<script>
	Clariprint_lang_SolverError = '{l s='Try another setup or contact us' mod='clariprint'}';
	Clariprint_lang_ConfigError = '{l s='Correct the errors above to get a quote'  mod='clariprint'}';
	Clariprint_lang_needRefresh = '{l s='The configuration has changed. You need to press the Rate Update button' mod='clariprint'}';
	
Clariprint_term_button_add_cart  = '{l s='Add to cart' mod='clariprint'}';
Clariprint_term_button_save_project  = '{l s='Save' mod='clariprint'}';
</Script>

<style type="text/css" media="screen">
	.product-prices {
		display: none;
	}
	.product-customization, 
	.product-add-to-cart,
	.product-actions,
	.product-add-to-cart span.control-label {
		display: none;
		
	}
	
</style>

<div class="modal fade" id="clariprintsavecustomization" tabindex="-1" role="dialog" aria-labelledby="ClariprintSaveCustomizationLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="ClariprintSaveCustomizationLabel">{l s='Set a project name' mod='clariprint'}</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon" id="clariprint_product_name_desc">{l s='Required product name' mod='clariprint'} : </span>
						<input type="text"
							class="form-control"
							name="clariprint_project_name"
							id="clariprint_project_name"
							value="" 
							placeholder="required product name"
							aria-describedby="clariprint_product_name_desc"/>
					</div>
					<div class="form-control-feedback">{l s='the project name is required' mod='clariprint'}</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">{l s='Annuler' mod='clariprint'}</button>
				<button type="button" class="btn btn-primary" role="clariprint-save-project">{l s='Save Project' mod='clariprint'}</button>
				<button type="button" class="btn btn-primary" role="clariprint-add-to-cart">{l s='Add to cart' mod='clariprint'}</button>
			</div>
		</div>
	</div>
</div>
<input type="hidden" name="clariprint_solver_uid" value="" id="clariprint_solver_uid"/>
{else}
<div id="clariprint_cart" style="display: none; width: 400px;" title="{l s='Connect to your account' mod='clariprint'}">
	{l s='You must be logged to you client account to purchase custom product. It\'s free to create your own account.' mod='clariprint'}
	<a href="{$link->getPageLink('my-account', true)|escape:'html'}" title="{l s='Login to your customer account' mod='blockuserinfo'}" class="login" rel="nofollow">{l s='Login' mod='blockuserinfo'}</a>
</div>
{/if}
