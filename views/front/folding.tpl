{*
* 2007-2013 EXPERT SOLUTIONS
*
* NOTICE OF LICENSE
* ALL RIGHT RESERVED TO EXPERT SOLUTIONS SARL

*  @author Expert Solutions SARL <contact@expert-solutions.fr>
*  @copyright  2013 EXPERT SOLUTIONS SARL
*  @license    proprietary
*}
{if $product->options->folding == 'show'}
<div class="card">
	<h3 class="accordion_header card-header">{l s='Folding' mod='clariprint'} :</h3>
<div class="folding card-block">
	{ClariprintSetupFolds product=$product}
	{$tabid=uniqid('foldtabs')}
	<div class="folding_tabs" role="fold-selector">
		<input type="hidden" value="{$product->folds}" name="{$product_key}[folds]" /> 
		<ul class="nav nav-tabs" role="tablist">
			{foreach from=$productfolds key=pages item=pfolds}
			<li class="nav-item">
				<a href="#{$tabid}_{$pages}" class="nav-link {if $pfolds@first}active{/if}" data-toggle="tab" role="tab">{l s="%s pages" sprintf=[$pages]}</a>
			</li>
			{/foreach}
		</ul>
		<div class="tab-content">
			{foreach from=$productfolds key=pages item=pfolds}
			<div class="tab-pane {if $pfolds@first}active{/if}" id="{$tabid}_{$pages}" role="tabpanel">
				<div class="card-deck-wrapper">
				  <div class="card-deck">
				{foreach from=$pfolds item=f}
				<div class="card fold text-xs-center {if $f.index == $product->folds}card-outline-primary{/if} mw-20 mb-xs-4" fold-index="{$f['index']}" foldheight="{$f['height']}"
						foldwidth="{$f['width']}"
						style="min-width: 2O0px;">
					<div class=card-header>
							{l s=$f['title'] mod='clariprint'}
					</div>
					<div class="card-block">
						<img src="/modules/clariprint/img/folds/{$f['index']}.png"/>
					</div>
				</div>
				{/foreach}
			</div></div>
			</div>
			{/foreach}
		</div>
	</div>
	<div class="clear"></div>
	<div class="form-group">
		<label for="{$product_key}[flat_delivery]">
			<input type="checkbox" name="{$product_key}[flat_delivery]" value="1" id="{$product_key}[flat_delivery]" {if ($product->flat_delivery)}checked{/if}/>
			{l s='Delivered unfolded (if not: hand folding will be charged) ?' mod='clariprint'}
		</label>
	</div>
	
	<div class="clear"></div>
	{if $product->options->folding_info}
	<div class="alert alert-info" role="alert">{$product->options->folding_info nofilter}</div>
	{/if}
	{if $product->options->folding_info_cms}
		{displayCMS cms=$product->options->folding_info_cms}
	{else}
		{displayCMS cms='product-folding'}
	{/if}
</div>
</div>
{else}
	<input type="hidden" name="{$product_key}[folds]" value="{$product->folds}"/>
	<input type="hidden" name="{$product_key}[flat_delivery]" value="{$product->flat_delivery}"/>
{/if}


