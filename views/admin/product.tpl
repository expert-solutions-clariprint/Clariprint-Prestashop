{*
* 2007-2013 EXPERT SOLUTIONS
*
* NOTICE OF LICENSE
* ALL RIGHT RESERVED TO EXPERT SOLUTIONS SARL

*  @author Expert Solutions SARL <contact@expert-solutions.fr>
*  @copyright  2013 EXPERT SOLUTIONS SARL
*  @license    proprietary
*}
{*
*}

<div id="clariprint" class="m-b-1">
	<h2>Clariprint</h2>


<script type="text/javascript">
	jQuery(function(){
		StartClariprint();

		var scriptTag = document.createElement('script');
    	scriptTag.src = 'https://lrdp.clariprint.com/js/gl-matrix-min.js';
    	document.body.appendChild(scriptTag);

		var scriptTag = document.createElement('script');
    	scriptTag.src = 'https://lrdp.clariprint.com/js/webgl-debug.js';
    	document.body.appendChild(scriptTag);

		var scriptTag = document.createElement('script');
    	scriptTag.src = 'https://lrdp.clariprint.com/js/xl_3D_sampler.js';
    	document.body.appendChild(scriptTag);



		
		Clariprint.admin = true;
		Clariprint.productController = '{$link->getAdminLink('AdminClariprintProducts',true)}';
		Clariprint.ui = '{$ui_mode}';
		$('#adminClariprintProduct').projectController();
		Clariprint.startAdmin('{getAdminToken tab='AdminClariprintProducts'}');
		
		$('#ClariprintAdminCopy').click(function(evt){
			evt.preventDefault();
			evt.stopImmediatePropagation();		
			var pid = parseInt(window.prompt("{l s='Indicate product source id to copy from' mod='clariprint'}",""));
			if (pid) {
				$.ajax({
					url:'{$link->getAdminLink('AdminClariprintProducts',true)}',
					data: {
						action:'copyConfig',
						from:pid,
						to:'{$presta_product->id}',
						ajax:1,
					}
				}).done(function() {
					window.location.reload(true);
					});
			}
			return false;
		});
	});
</script>

<div id="adminClariprintProduct" style="display: none">
	<input type="hidden" name="clariprint_update_config" value="{if $product}1{else}0{/if}" id="clariprint_update_config"/>
	<input type="hidden" name="clariprint_admin_price" value="1" id="clariprint_admin_price">
	<input type="hidden" name="clariprint_product_kind" value="multiple"/>
	<input type="hidden" name="clariprint_product[reference]" value="{current($presta_product->name)}"/>
	<input type="hidden" name="clariprint_product[group]" value="ADMIN"/>

	<nav class="subheader m-b-1">
		<!-- QuickNav -->
		<ul class="subheader-quicknav">
			<li class="subheader-quicknav-item">
				<button name="ClariprintUpdateConfiguration"
					class="btn btn-primary-outline" value="" id="ClariprintUpdateConfiguration"><i class="material-icons">save</i> {l s='Save configuration' mod='clariprint'}</button>
			</li>
			<li class="subheader-quicknav-item">
				<button name="ClariprintGetPrice" class="btn btn-success-outline" value="" id="ClariprintAdminGetPrice"><i class="material-icons">build</i> {l s='Get price' mod='clariprint'}</button>
			</li>
			<li class="subheader-quicknav-item">
				<button name="ClariprintAdminCopy" class="btn btn-success-outline" id="ClariprintAdminCopy"><i class="material-icons">cloud_download</i> {l s='Copy from existing product' mod='clariprint'}</button>
			</li>
			<li class="subheader-quicknav-item">
				<button name="ClariprintDelete" class="btn btn-danger-outline" id="ClariprintAdminDelete"><i class="material-icons">delete</i> {l s='Delete All' mod='clariprint'}</button>
			</li>
		</ul>
		<!-- Title -->
		<div class="subheader-title"><img src="/modules/clariprint/logo.png" width="30" height="30" alt=""></div>
	</nav>	
	<nav class="subheader">
		{foreach from=$productkinds key=key item=kind}
		<button class="add_product btn btn-primary btn-sm" value="{$key}"><i class="material-icons">add_circle</i> {$kind}</button>
		{/foreach}
	</nav>
	<div id="clariprint_solver_message"></div>
	<div id="clariprint_resume"></div>
</div>
<fieldset>
	<div class="clariprint_products ClariprintProducts cardion">
		<div class="card expandable">
			<div class="card-header">{l s="Global options" mod='clariprint'}</div>
			<div class="card-block">
				<div class="field">
						<label for="clariprint_options_multiproducts">{l s='Customer add/remove manage products ?' mod='clariprint'}</label>
						<input name="clariprint_product[options][multiproducts]"
								type="checkbox"
								id="clariprint_options_multiproducts"
								value="1"
								{if $product->options->multiproducts}checked{/if} />
				</div>
				<div class="clear"></div>
				<div class="field">
						<label for="clariprint_options_display">{l s='Display mode ?' mod='clariprint'}</label>
						<select name="clariprint_product[options][display]">
							<option value=''>{l s='auto' mod='clariprint'}</option>
							<option value='flat' {if $product->options->display == 'flat'}selected{/if}>{l s='flat' mod='clariprint'}</option>
							<option value='accordion' {if $product->options->display == 'accordion'}selected{/if}>{l s='accordion' mod='clariprint'}</option>
						</select>
				</div>
			</div>
		</div>
		
		{if $product->kind == 'multiple'}
			{assign var=base_product value=$product}
			{assign var=base_key value=$product_key}
			{if isset($product->parts)}
				{foreach from=$base_product->parts item=product key=key}
					{assign var=product_key value="clariprint_product[parts][{$key}]"}
					{if $product->kind}
						{include file="./{$product->kind}.tpl"}
					{/if}
				{/foreach}
			{/if}
			{assign var=product value=$base_product}
			{assign var=product_key value=$base_key}
		{elseif $product->kind}
			{assign var=product_key value="$product_key[pars][$key]"}
			{include file="./{$product->kind}.tpl"}
		{/if}
		<div id="clariprint_insert_product_before_me" style="display:none"></div>			
		{include file='./delivery.tpl'}
		{include file='./proof.tpl'}
		{include file='./delivery_time.tpl'}
		{include file='./discounts.tpl'}
		{include file='./devices.tpl'}
		{include file='./calculation.tpl'}
		{if defined(_PS_MODE_DEV_)}
		<div class="card expandable">
			<div class="card-header">Debug</div>
			<div class="card-block">
				<pre>{$product|print_r}</pre>
			</div>
		</div>
		{/if}
	</div>
	{if false}
	<div class="clariprint_product clariprint_element cl_accordions" id="clariprint_product_tab_content">
		<input type="hidden" name="{$product_key}[reference]" value="{$presta_product->reference}" id="{$product_key}_reference"> 
		{if $product_template}
			{* include file=$product_template *}
			{include file='./delivery.tpl'}
			{include file='./proof.tpl'}
			{include file='./discounts.tpl'}
			{include file='./devices.tpl'}
			{include file='./calculation.tpl'}
		{/if}
		</div>
	{/if}	
</fieldset>
</div>

