{* 2007-2013 EXPERT SOLUTIONS
*
* NOTICE OF LICENSE
* ALL RIGHT RESERVED TO EXPERT SOLUTIONS SARL

*  @author Expert Solutions SARL <contact@expert-solutions.fr>
*  @copyright  2013 EXPERT SOLUTIONS SARL
*  @license	proprietary
*}

	<h2 class="accordion_header">{l s='Snap Set / Carbonless Form' mod='clariprint'} <input type="text" class="ProductName" name="{$product_key}[name]" value="{$product->name}" placeholder="{l s='nom du produit' mod='clariprint'}" /><span class="remove_product ui-button-icon ui-icon ui-icon-closethick" style="float: right"></span></h2>
	<div class="product_accordion clariprint_product clariprint_formbook" productkey="{$product_key}">
		<input type="hidden" value="bookform" name="{$product_key}[kind]">
		{include file='./productoptions.tpl'}
		{include file='./quantities.tpl'}
		{include file='./dimensions.tpl'}
		{include file='./book_holes.tpl'}
		<h3 class="accordion_header">{l s='count of forms' mod='clariprint'}</h3>
		<div id="formscount" class="formscount">
				<div class="field">
					<label for="{$product_key}_option_formscount_mode">{l s='Mode :' mod='clariprint'}</label>
					<select name="{$product_key}[options][formscount]" id="{$product_key}_option_formscount_mode">
						<option value="hidden" {if $product->options->formscount == 'hidden'}selected{/if}>{l s='Hidden' mod='clariprint'}</option>
						<option value="combi" {if $product->options->formscount == 'combi'}selected{/if}>{l s='Combination' mod='clariprint'}</option>
						<option value="range" {if $product->options->formscount == 'range'}selected{/if}>{l s='Range' mod='clariprint'}</option>
						<option value="list" {if $product->options->formscount == 'list'}selected{/if}>{l s='List' mod='clariprint'}</option>
						<option value="free" {if $product->options->formscount == 'free'}selected{/if}>{l s='Free' mod='clariprint'}</option>
					</select>
				</div>

				<div class="clear"></div>

				<label for="{$product_key}[formscount]">{l s='Default :' }</label>
				<input type="text" name="{$product_key}[formscount]" class="CLInt" value="{$product->formscount}" id="clariprint_quantity"/><br/>

				<label for="{$product_key}[options][formscount_from]">{l s='From' mod='clariprint'}</label> : 
				<input type="text" name="{$product_key}[options][formscount_from]" value="{$product->options->formscount_from}" id="{$product_key}[options][formscount_from]" class="CLInt"/><br/>

				<label for="{$product_key}[options][formscount_to]">{l s='To' mod='clariprint'}</label> : 
				<input type="text" name="{$product_key}[options][formscount_to]" value="{$product->options->formscount_to}" id="{$product_key}[options][formscount_to]" class="CLInt"/><br/>

				<label for="{$product_key}[options][formscounts]">{l s='List' mod='clariprint'}</label> : 
				<textarea name="{$product_key}[options][formscounts]" rows="8" cols="5" id="{$product_key}[options][quantities]">{$product->options->formscounts}</textarea>
				<div class="clear"></div>
				{l s='User doc' mod='clariprint'}<br/>
				<textarea name="{$product_key}[options][formscount_info]" class='clariprint_rte' rows="8" cols="40">{$product->options->quantities_info|htmlentitiesUTF8}</textarea>
				<div class="clear"></div>
		</div>
		<h2 class="composants">{l s='Components' mod='clariprint'}</h2>
			{foreach from=get_object_vars($product->components) item=component key=key}
				{assign var=main_product_key value=$product_key}
				{assign var=product_key value="$main_product_key[components][$key]"}
				{assign var=product_id value=uniqid()}
				{assign var=main_product value=$product}
				{assign var=product value=$component}
				{$component->type}
				{include 'bookformpart.tpl'}
				{assign var=product_key value=$main_product_key}
				{assign var=product value=$main_product}
			{/foreach}
		<div class="addcomponents_before" style="display:none"></div>
		<div class="addcomponents">
			<h3>{l s='Add part' mod='clariprint'}</h3>
			<div class="clear"></div>
			<input type='checkbox' value='1' name="{$product_key}[options][manage_sections]" id="{$product_key}[options][manage_sections]"/>
			<label for="{$product_key}[options][manage_sections]">{l s='User can manage sections' mod='clariprint'}</label>
			<div class="clear"></div>
			<div class="row">
				<div class="CLBookAddComponent" componentkind='bookformpart'>
					<img src="/modules/clariprint/img/products/FeuilletSimple.png" width="43" height="50" alt="{l s='Leaflet' mod='clariprint'}">
					{l s='Leaflet' mod='clariprint'}
				</div>
			</div>
		</div>
		{include file='./wrapping.tpl'}
	</div>
