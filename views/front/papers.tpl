{*
* 2007-2013 EXPERT SOLUTIONS
*
* NOTICE OF LICENSE
* ALL RIGHT RESERVED TO EXPERT SOLUTIONS SARL

*  @author Expert Solutions SARL <contact@expert-solutions.fr>
*  @copyright  2013 EXPERT SOLUTIONS SARL
*  @license    proprietary
*}
{if ($product->options->papers->mode != 'hidden')}
<div class="card">
	<h3 class="card-header">{if isset($clariprint_card_label)}{$clariprint_card_label}{else}{l s='Papers' mod='clariprint'}{/if} :</h3>
	<div id="papers_{$product_key}" class="papers ClariprintPapers  card-block">
		<input type="hidden" class="PaperISO" name="{$product_key}[paper_iso]" default="{$product->paper_iso}" value="{$product->paper_iso}" id="{$product_key}_paper_iso"/>
	
	{if ($product->options->labels_mode == 'show')} 
	<div class="clear"></div>
	<label for="$product_key}_label_pefc">{l s='PEFC' mod='clariprint'}</label>
		<input type="checkbox" name="{$product_key}[label]" value="pefc" id="{$product_key}_label_pefc" class="ClariprintLabel"/>
	{else}
		<input type="hidden" name="{$product_key}[label]" value="{$product->label}" id="{$product_key}_label_pefc" class="ClariprintLabel"/>
	{/if}
	{if ($product->options->papers->mode == 'list')}
		{$pkk='custom'}
	<div role="paper-list-selector">
		<input type="hidden" readonly class="quality" name="{$product_key}[papers][{$pkk}][quality]" value="{$product->papers->$pkk->quality}"/>
		<input type="hidden" readonly class="brand" name="{$product_key}[papers][{$pkk}][brand]" value="{$product->papers->$pkk->brand}"/>
		<input type="hidden" readonly class="color" name="{$product_key}[papers][{$pkk}][color]" value="{$product->papers->$pkk->color}"/>
		<input type="hidden" readonly class="weight" name="{$product_key}[papers][{$pkk}][weight]" value="{$product->papers->$pkk->weight}"/>
		{$lpapers=explode("\n",$product->options->paper_list)}
		{if count($lpapers) > 10}
		<select class="form-control" role="paper-list-selector">
		{foreach from=$lpapers item=pap}
			{assign var=papl value=explode(';',$pap)}
			{assign var=txt value=end($papl)}
			{if $txt != ''}
				<option value="{$pap}">{l s=$txt mod='clariprint'}</option>
				{/if}
		{/foreach}
		</select>
		{else}
		<ul>
		{foreach from=$lpapers item=pap name=paps}
			{assign var=papl value=explode(';',$pap)}
			{assign var=txt value=end($papl)}
			{if $txt != ''}
			
				<li><label><input  {$product_key} {if $smarty.foreach.paps.first}checked{/if}            
							role="paper-list-selector" name="{$product_key}[paper_radio]" type="radio" value="{$pap}"> {l s=$txt mod='clariprint'}</label></li>
			{/if}	
		{/foreach}
		</ul>
		{/if}
	</div>
	{elseif ($product->options->papers->mode == 'graphic')}
		{if (isset($product->papers->of))}
			{assign var=paper value=$product->papers->of}
			{assign var=poptions value=$product->options->papers->of}
			{assign var=pkk value='of'}
		{else}
			{assign var=paper value=$product->papers->custom}
			{assign var=poptions value=$product->options->papers->custom}
			{assign var=pkk value='custom'}
		{/if}	
	<style type="text/css" media="screen">
/*		.PaperWidget2 td { vertical-align: top;
		} */
		ul.paper_property {
			max-height: 200px;
			overflow-y: scroll;
		}
/*		.PaperWidget2 ul.paper_property li.btn {
			display: block !important;
		}
		.clariprint_paper_search_results {
			background-color: white;
			border:1px solid #ddd;
		} */
	</style>
	<div class="clear"></div>

	<div class="PaperWidget2 container-fluid" id="ppw" clariprint_paper_process="{$pkk}">
		{if $product->options->process_show == 'show'}
		<div class="row">
			<div class="col-md-12">
				<h5>{l s='Allowed process' mod='clariprint'}</h5>
				<fieldset>
				{foreach from=$processes item=procs}
					<div>
					{foreach from=$procs item=kind key=kk}
					{if in_array_silent($kk,$product->options->papers->processes)}
						<label class="checkbox">
							<input type="checkbox"
								id="{$product_key}_paper_{$pkk}_{$kk}"
								class="processes"
								value="{$kk}"
								name="{$product_key}[papers][{$pkk}][processes][]"
								{if in_array_silent($kk,$product->papers->$pkk->processes)}checked="true"{/if} /> {$kind}
						    </label>
						{/if}
					{/foreach}
					</div>
				{/foreach}
				</fieldset>
			</div>
		</div>
		{else}
			{foreach from=$processes item=procs}
				{foreach from=$procs item=kind key=kk}
					{if in_array_silent($kk,$product->options->papers->processes)}
						{if in_array_silent($kk,$product->papers->$pkk->processes)}
						<input type="hidden" id="{$product_key}_paper_{$pkk}_{$kk}"
							class="processes"
							value="{$kk}"
							name="{$product_key}[papers][{$pkk}][processes][]" />
						{/if}
					{/if}
				{/foreach}
			{/foreach}
		{/if}
		
		<div class="row">
				
			<input type="search" class="searchbox search-query form-control" placeholder="{l s='Search paper by brand or quality' mod='clariprint'}"/>
		</div>
		<div class="row">
			<div class="col-md-4">
				<button class="revert btn">{l s='Revert to default values' mod='clariprint'}</button>
			</div>
			<div class="col-md-8 alerts">
						<div class="alert alert-success" role="alert" style="display: none">
							{l s='Current paper selection above is valid.' mod='clariprint'}
						</div>
						<div class="alert alert-warning" role="alert" style="display: none">
							{l s='Warning ; incorrect paper selection.' mod='clariprint'}
						</div>
					<div class="bar" style="width: 100%;"></div>
			</div>
		</div>
		
		<div class="row">
			
			<div class="col-md-9 col-sm-9" {if $poptions->fixed_quality}hidden{/if}>
				<h5>{l s='Quality' mod='clariprint'} *</h5>
				<input type="text" readonly="true" class="quality form-control ClInt"
						name="{$product_key}[papers][{$pkk}][quality]"
						value="{$paper->quality}"
						default-value="{$paper->quality}"
						required />
				<ul class="qualities paper_property list-group">
				</ul>
				{if $poptions->qualities}
					<select multiple class="PaperQualitiesFilter" style="display: none">
					{foreach from=$poptions->qualities item='item'}
						<option selected>{$item}</option>
					{/foreach}
					</select>
				{/if}
			</div>
			<div class="col-md-3  col-sm-3" {if $poptions->fixed_weight}hidden{/if}>
				<h5>{l s='Weight' mod='clariprint'} * </h5>
				<input type="text" readonly="true" class="weight form-control"
						name="{$product_key}[papers][{$pkk}][weight]"
						value="{$paper->weight}" 
						default-value="{$paper->weight}" 
						required />
						
				<ul class="weights paper_property">
				</ul>
				{if $poptions->weights}
					<select multiple class="PaperWeightsFilter" style="display: none">
					{foreach from=$poptions->weights item='item'}
						<option selected>{$item}</option>
					{/foreach}
					</select>
				{/if}
			</div>
			<div class="col-md-8  col-sm-8" {if $poptions->fixed_brand}hidden{/if}>
				<h5>{l s='Brand' mod='clariprint'}</h5>
				<input type="text" readonly="true" class="brand form-control"
						name="{$product_key}[papers][{$pkk}][brand]" 
						value="{$paper->brand}"
						default-value="{$paper->brand}"/>
				<ul class="brands paper_property">
				</ul>
				{if $poptions->brands}
					<select multiple class="PaperBrandsFilter" style="display: none">
					{foreach from=$poptions->brands item='item'}
						<option selected>{$item}</option>
					{/foreach}
					</select>
				{/if}
			</div>
			<div class="col-md-4  col-sm-4" {if $poptions->fixed_color}hidden{/if}>
				<h5>{l s='Color' mod='clariprint'}</h5>
				<input type="text" readonly="true" class="paper-color form-control" 
					name="{$product_key}[papers][{$pkk}][color]"
					value="{$paper->color}"
					default-value="{$paper->color}"/>
				<ul class="colors paper_property">
				</ul>
				{if $poptions->colors}
					<select multiple class="PaperColorsFilter" style="display: none">
					{foreach from=$poptions->color item='item'}
						<option selected>{$item}</option>
					{/foreach}
					</select>
				{/if}
			</div>
			
		</div>
		<div>
		* {l s='required' mod='clariprint'}
		</div>
	</div>
	{else}
	
	<div class="clear"></div>
		{if (isset($product->papers->of))}
			{assign var=paper value=$product->papers->of}
			{assign var=poptions value=$product->options->papers->of}
			{assign var=pkk value='of'}
		{else}
			{assign var=paper value=$product->papers->custom}
			{assign var=poptions value=$product->options->papers->custom}
			{assign var=pkk value='custom'}
		{/if}
		
		{if in_array_silent($product->options->papers2->$pkk->group,$user_groups)}
		{assign var=poptions value=$product->options->papers2->$pkk}
		{else}
		{assign var=poptions value=$product->options->papers->$pkk}
		{/if}
	<table class="papers PaperWidget" id="{$product_key}_paper_{$pkk}" clariprint_paper_process="{$pkk}" {if !$product->options->paper_kinds[$pkk]}style="display_: none"{/if}>
		<tbody>
			<tr>
				<td>
					{if $poptions->fixed_quality}
					<input type="hidden" class="quality" name="{$product_key}[papers][{$pkk}][quality]" value="{$product->papers->$pkk->quality}"/>
					{else}
					{l s='Quality' mod='clariprint'} : <br/>
					<select name="{$product_key}[papers][{$pkk}][quality]" class="quality" title="{$product->papers->$pkk->quality}">
						<option value="{$product->papers->$pkk->quality}" selected="true">{$item}</option>
					</select>
					{/if}
					{if $poptions->qualities}
						<select multiple class="PaperQualitiesFilter" style="display: none">
						{foreach from=$poptions->qualities item='item'}
							<option selected>{$item}</option>
						{/foreach}
						</select>
					{/if}
				</td>
				<td>
					{if $poptions->fixed_weight}
					<input type="hidden" class="weight" name="{$product_key}[papers][{$pkk}][weight]" value="{$product->papers->$pkk->weight}"/>
					{else}
					{l s='Weight' mod='clariprint'} : <br/>
					<select  name="{$product_key}[papers][{$pkk}][weight]" class="weight" title="{$product->papers->$pkk->weight}">
							<option value="{$product->papers->$pkk->weight}" selected="true">{$product->papers->$pkk->weight}</option>
					</select>
					{/if}
					{if $poptions->weights}
						<select   multiple class="PaperWeightsFilter" style="display: none">
						{foreach from=$poptions->weights item='item'}
							<option selected>{$item}</option>
						{/foreach}
						</select>
					{/if}
				</td>
			</tr>
			<tr>
				<td>
					{if $poptions->fixed_brand}
					<input type="hidden" class="brand" name="{$product_key}[papers][{$pkk}][brand]" value="{$product->papers->$pkk->brand}"/>
					{else}
					{l s='Brand:' mod='clariprint'} :<br/>
					<select name="{$product_key}[papers][{$pkk}][brand]" class="brand">
							<option value="{$product->papers->$pkk->brand}" selected="true">{l s=$product->papers->$pkk->brand mod='clariprint'}</option>
					</select>
					{/if}
					{if $poptions->brands}
						<select multiple class="PaperBrandsFilter" style="display: none">
						{foreach from=$poptions->brands item='item'}
							<option selected>{$item}</option>
						{/foreach}	
						</select>
					{/if}
				</td>
				<td>
					{if $poptions->fixed_color}
					<input type="hidden" class="color" name="{$product_key}[papers][{$pkk}][color]" value="{$product->papers->$pkk->color}"/>
					{else}
					{l s='Color' mod='clariprint'} :<br/>
					<select name="{$product_key}[papers][{$pkk}][color]" class="paper-color">
						<option value="{$product->papers->$pkk->color}" selected="true">{l s=$product->papers->$pkk->color mod='clariprint'}</option>
					</select>
					{/if}
					{if $poptions->colors}
						<select multiple class="PaperColorsFilter" style="display: none">
						{foreach from=$poptions->colors item='item'}
							<option selected>{$item}</option>
						{/foreach}
						</select>
					{/if}
				</td>
			</tr>
		</tbody>
		<tfooter>
			<tr>
				<td colspan="2"></td>
			</tr>
		</tfooter>
	</table>
	<div class="clear"></div>
	{/if}
	{if $product->options->papers_info}
	<div class="alert alert-info" role="alert">{$product->options->papers_info nofilter}</div>
	{/if}
	{if $product->options->papers_info_cms}
		{displayCMS cms=$product->options->papers_info_cms}
	{else}
		{displayCMS cms='product-papers'}
	{/if}
</div>	
</div>
{else}
	{foreach from=$product->papers key=pkk item=pk}
		<input type="hidden" name="{$product_key}[papers][{$pkk}][quality]" value="{$pk->quality}"/>
		<input type="hidden" name="{$product_key}[papers][{$pkk}][weight]" value="{$pk->weight}"/>
		<input type="hidden" name="{$product_key}[papers][{$pkk}][brand]" value="{$pk->brand}"/>
		<input type="hidden" name="{$product_key}[papers][{$pkk}][color]" value="{$pk->color}"/>
		<input type="hidden" name="{$product_key}[label]" value="{$product->label}" id="{$product_key}_label_pefc" class=".ClariprintLabel"/>
	{/foreach}
{/if}

