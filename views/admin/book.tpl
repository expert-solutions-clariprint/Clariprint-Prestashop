{* 2007-2013 EXPERT SOLUTIONS
*
* NOTICE OF LICENSE
* ALL RIGHT RESERVED TO EXPERT SOLUTIONS SARL

*  @author Expert Solutions SARL <contact@expert-solutions.fr>
*  @copyright  2013 EXPERT SOLUTIONS SARL
*  @license	proprietary
*}

<div class="card expandable removable">
	<div class="card-header">{l s='Book' mod='clariprint'} <input type="text" class="ProductName" name="{$product_key}[name]" value="{$product->name}" placeholder="{l s='nom du produit' mod='clariprint'}" /></div>
	<div class="product_accordion clariprint_product clariprint_book card-block" productkey="{$product_key}">
		<input type="hidden" value="book" name="{$product_key}[kind]">
		{include file='./productoptions.tpl'}
		{include file='./quantities.tpl'}
		{include file='./dimensions.tpl'}
		{include file='./binding.tpl'}
		{include file='./book_holes.tpl'}

		<div class="card expandable">
			<div class="card-header">{l s='Cover' mod='clariprint'}</div>
			<div class="bookcover card-block">
				<div class="card">
					<div class="card-header">{l s='Cover Options' mod='clariprint'}</div>
					<div class="card-block">
						<input type="button" class='CLBookAddCover' value="{l s='Add a cover' mod='clariprint'}" {if $product->cover}style="display: none"{/if}/>
						<input type="button" class='CLBookRemoveCover' value="{l s='Remove the cover' mod='clariprint'}" {if !$product->cover}style="display: none"{/if}/>
						
						
						<div class="form-check">
							<label class="form-check-label">
								<input type="checkbox" class="form-check-input" name="{$product_key}[options][optional_cover]" value="1"
									{if $product->options->optional_cover}checked{/if}
									/>
								{l s='Cover is optional' mod='clariprint'}
							</label>
							
						</div>
					</div>
				</div>
				{if $product->cover}
					{assign var=main_product_key value=$product_key}
					{assign var=product_key value="$main_product_key[cover]"}
					{assign var=main_product value=$product}
					{assign var=product value=$product->cover}
					{include file='./cover.tpl'}
					{assign var=product_key value=$main_product_key}
					{assign var=product value=$main_product}
				{/if}
			</div>
		</div>
		<h2 class="composants">{l s='Components' mod='clariprint'}</h2>
			{foreach from=get_object_vars($product->components) item=component key=key}
				{assign var=main_product_key value=$product_key}
				{assign var=product_key value="$main_product_key[components][$key]"}
				{assign var=product_id value=uniqid()}
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
			{/foreach}
		<div class="addcomponents_before" style="display:none"></div>
		<div class="addcomponents card">
			<h3 class="card-header">{l s='Add a book section' mod='clariprint'}</h3>
			<div class="card-body">
			<input type='checkbox' value='1' name="{$product_key}[options][manage_sections]" id="{$product_key}[options][manage_sections]"
				{if $product->options->manage_sections} checked{/if} />
			<label for="{$product_key}[options][manage_sections]">{l s='User can manage sections' mod='clariprint'}</label>
			<div class="clear"></div>
			<div class="row">
				<div class="col-xs-3 col-sm-3">
					<div class="CLBookAddComponent" componentkind='section'>
					<img src="/modules/clariprint/img/products/CahierNbrPages.png" width="43" height="50" alt="{l s='Section' mod='clariprint'}">
					{l s='Section' mod='clariprint'}
				</div>
			</div>
			<div class="col-xs-3 col-sm-3">
				<div class="CLBookAddComponent" componentkind='section_leaflet'>
					<img src="/modules/clariprint/img/products/FeuilletSimple.png" width="43" height="50" alt="{l s='Leaflet' mod='clariprint'}">
					{l s='Leaflet' mod='clariprint'}
				</div>
			</div>
			<div class="col-xs-3 col-sm-3">
				<div class="CLBookAddComponent" componentkind='section_folded'>
					<img src="/modules/clariprint/img/products/DepliantSimple.png" width="43" height="50" alt="{l s='Folded' mod='clariprint'}">
					{l s='Folded' mod='clariprint'}
				</div>
			</div>
		</div>
		</div>
		</div>
		{include file='./wrapping.tpl'}
	</div>
</div>