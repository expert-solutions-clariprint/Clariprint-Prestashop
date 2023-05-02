{*
* 2007-2013 EXPERT SOLUTIONS
*
* NOTICE OF LICENSE
* ALL RIGHT RESERVED TO EXPERT SOLUTIONS SARL

*  @author Expert Solutions SARL <contact@expert-solutions.fr>
*  @copyright  2013 EXPERT SOLUTIONS SARL
*  @license    proprietary
*}
<div class="card expandable">
	<div class="card-header">{l s='Folding' mod='clariprint'}</div>
	<div class="folding card-block">

	<div class="field">
		<label for="{$product_key}_options_folding">{l s='Mode :' mod='clariprint'}</label>
		<select name="{$product_key}[options][folding]" id="{$product_key}_options_folding">
			<option value="hidden" {if $product->options->folding == 'hidden'}selected{/if}>{l s='Hidden' mod='clariprint'}</option>
			<option value="show" {if $product->options->folding == 'show'}selected{/if}>{l s='Show' mod='clariprint'}</option>
		</select>
	</div>
	
	<div class="clear"></div>
	<label for="{$product_key}[flat_delivery]"> {l s='Delivered unfolded (if not: hand folding will be charged) ?' mod='clariprint'} :</label>
	<input type="checkbox" name="{$product_key}[flat_delivery]" value="1" id="{$product_key}[flat_delivery]" {if ($product->flat_delivery)}checked{/if}/>
	<div class="clear"></div>
	
	<div>
		<ul class="nav nav-tabs bordered" role="tablist">
			{assign var='tabk' value=uniqid('tab_')}
			{foreach from=$folds key=pages item=pfolds}
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#{$tabk}_{$pages}" role="tab">{l s="%s pages" sprintf=[$pages]}</a>
			</li>
			{/foreach}
		</ul>
		<div class="tab-content bordered">
			{foreach from=$folds key=pages item=pfolds}
			<div class="tab-pane" id="{$tabk}_{$pages}" role="tabpanel">
				<table class='table'>
					<thead>
						<tr>
							<th>{l s='available'}</th>
							<th>{l s='default'}</th>
							<th>{l s='name'}</th>
							<th>{l s='icon'}</th>
						</tr>
					</thead>
					<tbody>
						{foreach from=$pfolds item=f}
						<tr>
							<td><input type="checkbox" value="{$f['index']}" name="{$product_key}[options][folds][]" {if in_array_silent($f['index'],$product->options->folds)}checked="1"{/if}/></td>
							<td><input type="radio" value="{$f['index']}" name="{$product_key}[folds]" {if $f['index'] == $product->folds}checked="1"{/if} foldheight="{$f['height']}" foldwidth="{$f['width']}" onclick="Clariprint.setFolding(this)"/></td>
							<td>{l s=$f['title'] mod='clariprint'}</td>
							<td><img src="/modules/clariprint/img/folds/{$f['index']}.png"/></td>
						</tr>

						{/foreach}
					</tbody>
				</table>
			</div>
			{/foreach}
		</div>
	</div>

	<div class="clear"></div>
	{l s='User doc' mod='clariprint'}<br/>
	<textarea name="{$product_key}[options][folding_info]" class='clariprint_rte' rows="8" cols="40">{$product->options->folding_info|htmlentitiesUTF8}</textarea>
	<div class="clear"></div>
	 cms product-folding
	<label class="form-control-label" for="">{l s='Aide CMS' mod="clariprint"}</label>
	<input type="text" class="form-control"  name="{$product_key}[options][folding_info_cms]" value="{$product->options->folding_info_cms}"/>
	
</div>
</div>