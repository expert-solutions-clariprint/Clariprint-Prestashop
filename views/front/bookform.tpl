{*
* 2007-2013 EXPERT SOLUTIONS
*
* NOTICE OF LICENSE
* ALL RIGHT RESERVED TO EXPERT SOLUTIONS SARL

*  @author Expert Solutions SARL <contact@expert-solutions.fr>
*  @copyright  2013 EXPERT SOLUTIONS SARL
*  @license	proprietary
*}
{assign var=bookkey value=$key}
{if !$one_product}
<div class="card product-card">
<h2 class="accordion_header card-header">{if $product->name}{l s=$product->name mod='clariprint'}{else}{l s='Book' mod='clariprint'}{/if}{if $remove_product}<span class="remove_product ui-button-icon ui-icon ui-icon-closethick" style="float: right">{/if}</h2>
{/if}	
<div class="product_accordion clariprint_product ClariprintFrontBook clariprint_book {if !$one_product}card-body p-1{/if}">
	<input type="hidden" value="book" name="{$product_key}[kind]">
	{include file='./quantities.tpl'}
	{include file='./dimensions.tpl'}
	{include file='./binding.tpl'}
	{if in_array_silent('holes',$product->options->makeready)}
	<div class="card">
	<h3 class="accordion_header card-header">{l s='Holes' mod='clariprint'}</h3>
	<div class="holes card-block">
		<div class="form-check">
			<label class="form-check-label">
				<input type="radio" name="{$product_key}[holes]" value="0" {if $product->holes == 0}checked{/if}/> {l s='No hole	' mod='clariprint'}</label>
		</div>
		<div class="form-check">
			<label class="form-check-label">
			<input type="radio" name="{$product_key}[holes]" value="1" {if $product->holes == 1}checked{/if}/> {l s='1 file hole' mod='clariprint'}</label>
		</div>
		<div class="form-check">
			<label class="form-check-label">
			<input type="radio" name="{$product_key}[holes]" value="2" {if $product->holes == 2}checked{/if}/> {l s='2 file hole' mod='clariprint'}</label>
		</div>
		<div class="form-check">
			<label class="form-check-label">
			<input type="radio" name="{$product_key}[holes]" value="4" {if $product->holes == 4}checked{/if}/> {l s='4 file hole' mod='clariprint'}</label>
		</div>
			
		{if $product->options->holes_info}
		<div class="alert alert-info" role="alert">{$product->options->holes_info nofilter}</div>
		{/if}		
	</div>
	</div>
	{else}
		<input type="hidden" name="{$product_key}[holes]" value="{$product->holes}" />
	{/if}

	{if $product->options->manage_sections}
	<h3 class="accordion_header">{l s='Covers' mod='clariprint'}</h3>
	<div class="bookcovers">
		<input type="button" class='CLBookAddCover' value="{l s='Add a cover' mod='clariprint'}" {if $product->cover}style="display: none"{/if}/>
		<input type="button" class='CLBookRemoveCover' value="{l s='Remove the cover' mod='clariprint'}" {if !$product->cover}style="display: none"{/if}/>
	</div>
	{/if}
	
	 {if isset($product->cover)}
		{if $product->cover}
					{if $product->options->optional_cover}
					<div class="card">
						<div class="card-header">{l s='With ou without cover' mod='clariprint'}</div>
						<div class="card-block">
							<label class="form-check-label">
								<input type="checkbox"
										class="form-check-input"
										value="1" {if $product->cover->coverdie != 'none'}checked{/if}
										role="clariprint-cover-activation"
										name="{$product_key}[withcover]"
										/>
								{l s='With cover' mod='clariprint'}
							</label>
							
						</div>
					</div>
					{/if}
			{assign var=main_product_key value=$product_key}
			{assign var=product_key value="$main_product_key[cover]"}
			{assign var=main_product value=$product}
			{assign var=product value=$product->cover}
			{include file='./cover.tpl'}
			{assign var=product_key value=$main_product_key}
			{assign var=product value=$main_product}
		{/if}
	{/if}
	{assign var=components value=(array)$product->components}
	{assign var=unique_component value=(count($components) == 1)}
	{if count(components) > 0}
	{foreach from=$components item=component key=key}
		{if !$component->is_model}
			{assign var=main_product_key value=$product_key}
			{assign var=product_key value="$main_product_key[components][$key]"}
			{assign var=main_product value=$product}
			{assign var=product value=$component}
			{if $component->kind == 'section_leaflet'}
					{include "./section_leaflet.tpl"}
			{elseif $component->kind == 'section_folded'}
					{include "./section_folded.tpl"}
			{else}
					{include "./section.tpl"}
			{/if}
			{assign var=product_key value=$main_product_key}
			{assign var=product value=$main_product}
		{/if}
	{/foreach}
	{else}
		no components !!!!!
	{/if}
	{if $product->options->manage_sections}
	<div class="addcomponents_before" style="display: none"></div>
	<div class="card">
		<h3 class="accordion_header card-header">{l s='Add a book section' mod='clariprint'}</h3>
		<div class="card-body ">
			<div class="row">
		{foreach from=$components item=model key=ckey}
			{if $model->is_model}
			<div class="col-xs-3 col-sm-3">
			<button class="CLBookAddComponentModel" product_id="{$clariprint_product_id}" book="{$bookkey}" model="{$ckey}">
				<img src="/modules/clariprint/img/products/{$model->kind}.png"/><br/>{if $model->name}{$model->name}{else}{l s=$model->kind mod='clariprint'}{/if}</button>
			</div>
			{/if}
		{/foreach}
		</div>
	</div>
	</div>
	{/if}
	{include file='./wrapping.tpl'}
</div>
{if !$one_product}</div>{/if}